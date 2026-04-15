<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SecurityShieldMiddleware
{
    /**
     * قائمة الكلمات المحظورة لاكتشاف هجمات SQL Injection
     */
    protected array $sqlPatterns = [
        // '/(\b(SELECT|INSERT|UPDATE|DELETE|DROP|ALTER|CREATE|TRUNCATE|EXEC|EXECUTE)\b)/i', // Too aggressive (blocks 'create', 'update', 'delete' strings)
        '/(\b(UNION|JOIN|WHERE|FROM|INTO|VALUES|SET)\b.*\b(SELECT|INSERT|UPDATE|DELETE|DROP)\b)/i',
        '/(--|\#|\/\*|\*\/)/',
        '/(\b(OR|AND)\b\s+\d+\s*=\s*\d+)/i',
        '/(\b(OR|AND)\b\s+\'[^\']*\'\s*=\s*\'[^\']*\')/i',
        '/(;\s*(SELECT|INSERT|UPDATE|DELETE|DROP|ALTER|CREATE|TRUNCATE))/i',
        '/(UNION\s+(ALL\s+)?SELECT)/i',
        '/(LOAD_FILE|OUTFILE|INFILE|BENCHMARK)/i',
        '/(SLEEP|WAITFOR|DELAY|BENCHMARK)\s*\(/i',
        '/(CONCAT|CHAR|HEX|UNHEX|ASCII|SUBSTRING|GROUP_CONCAT)\s*\(/i',
        '/(INFORMATION_SCHEMA|SYS\.|SYSOBJECTS|SYSCOLUMNS)/i',
        '/(DECLARE|CAST|CONVERT|CHARACTER_LENGTH)\s*\(/i',
        '/(0x[0-9a-fA-F]+)/',
        '/(\b(DATABASE|VERSION|USER|CURRENT_USER)\s*\(\s*\))/i',
    ];

    /**
     * قائمة أنماط XSS المحظورة
     */
    protected array $xssPatterns = [
        '/<\s*script\b/i',
        '/javascript\s*:/i',
        '/on\w+\s*=/i',
        '/<\s*iframe\b/i',
        '/<\s*object\b/i',
        '/<\s*embed\b/i',
        '/<\s*svg\b.*\bon\w+\s*=/i',
        '/expression\s*\(/i',
        '/vbscript\s*:/i',
        '/data\s*:\s*text\/html/i',
        '/<\s*img\b.*\bonerror\b/i',
        '/<\s*body\b.*\bonload\b/i',
        '/<\s*input\b.*\bonfocus\b/i',
        '/document\.(cookie|location|write)/i',
        '/window\.(location|open|close)/i',
        '/eval\s*\(/i',
        '/alert\s*\(/i',
        '/prompt\s*\(/i',
        '/confirm\s*\(/i',
    ];

    /**
     * قائمة البوتات والأدوات المحظورة
     */
    protected array $badBots = [
        'curl', 'wget', 'python', 'perl', 'ruby', 'php', 'postman', 
        'insomnia', 'httpie', 'httpie-cli', 'sqlmap', 'nikto', 'nmap', 
        'masscan', 'dirbuster', 'gobuster', 'wfuzz', 'burp', 'zap', 
        'acunetix', 'nessus', 'openvas', 'w3af', 'arachni', 'scanner', 
        'scraper', 'crawler', 'spider', 'fetch', 'go-http-client', 'java/', 
        'apache-httpclient', 'node-fetch', 'axios', 'got/', 'request', 'superagent',
    ];

    /**
     * تفعيل 5 جدران حماية لتعزيز أمن التطبيق
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. جدار حماية الـ Headers (Security Headers)
        $response = $next($request);
        $response = $this->addSecurityHeaders($response, $request);

        // 2. جدار تصفية المدخلات (SQL Injection & XSS Prevention)
        $this->filterPayload($request);

        // 3. جدار فحص الـ User Agent (Bot Protection)
        $this->blockBots($request);

        // 4. جدار حماية الـ Host & Origin
        $this->validateHost($request);

        // 5. جدار حماية البيانات الحساسة في الـ Response
        $response = $this->maskSensitiveData($response);

        // تسجيل محاولات الاختراق
        $this->logSuspiciousActivity($request);

        return $response;
    }

    /**
     * الجدار الأول: إضافة ترويسات الأمان
     */
    protected function addSecurityHeaders(Response $response, Request $request): Response
    {
        if (method_exists($response, 'header')) {
            // منع MIME sniffing
            $response->header('X-Content-Type-Options', 'nosniff');

            // منع Clickjacking
            $response->header('X-Frame-Options', 'DENY');

            // حماية XSS في المتصفحات القديمة
            $response->header('X-XSS-Protection', '1; mode=block');

            // فرض HTTPS
            $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');

            // سياسة محتوى الأمان (CSP)
            $cspPolicy = implode('; ', [
                "default-src 'self' https: data: 'unsafe-inline' 'unsafe-eval'",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com",
                "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com",
                "img-src 'self' data: https: blob: http:",
                "font-src 'self' data: https://fonts.gstatic.com https://cdnjs.cloudflare.com",
                "connect-src 'self' https: wss: ws:",
                "frame-ancestors 'self'",
                "form-action 'self'",
                "base-uri 'self'",
                "object-src 'none'",
            ]);
            $response->header('Content-Security-Policy', $cspPolicy);

            // سياسة Referrer
            $response->header('Referrer-Policy', 'strict-origin-when-cross-origin');

            // سياسة Permissions
            $response->header('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

            // إخفاء إصدار PHP
            $response->header('X-Powered-By', '');
            $response->headers->remove('X-Powered-By');

            // منع التخزين المؤقت للصفحات الحساسة
            if ($request->isMethod('POST') || $request->routeIs('*admin*') || $request->routeIs('*auth*')) {
                $response->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
                $response->header('Pragma', 'no-cache');
                $response->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
            }
        }

        return $response;
    }

    /**
     * الجدار الثاني: تصفية المدخلات ومنع SQL Injection & XSS
     */
    protected function filterPayload(Request $request): void
    {
        $input = $request->all();
        $sanitized = false;

        array_walk_recursive($input, function (&$item) use (&$sanitized, $request) {
            if (!is_string($item)) {
                return;
            }

            // فحص أنماط SQL Injection
            foreach ($this->sqlPatterns as $pattern) {
                if (preg_match($pattern, $item)) {
                    $this->blockRequest('SQL Injection attempt detected', [
                        'pattern' => $pattern,
                        'input' => substr($item, 0, 100),
                        'url' => request()->fullUrl(),
                        'ip' => request()->ip(),
                    ]);
                }
            }

            // فحص أنماط XSS
            foreach ($this->xssPatterns as $pattern) {
                if (preg_match($pattern, $item)) {
                    $this->blockRequest('XSS attempt detected', [
                        'pattern' => $pattern,
                        'input' => substr($item, 0, 100),
                        'url' => request()->fullUrl(),
                        'ip' => request()->ip(),
                    ]);
                }
            }

            // تنظيف المدخلات
            $original = $item;
            $item = htmlspecialchars($item, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $item = strip_tags($item);
            $item = trim($item);

            if ($original !== $item) {
                $sanitized = true;
            }
        });

        if ($sanitized) {
            $request->merge($input);
        }
    }

    /**
     * الجدار الثالث: منع البوتات والأدوات الآلية
     */
    protected function blockBots(Request $request): void
    {
        $userAgent = $request->header('User-Agent', '');
        $ip = $request->ip();

        if (empty($userAgent)) {
            $this->blockRequest('Empty User-Agent', ['ip' => $ip]);
        }

        foreach ($this->badBots as $bot) {
            if (stripos($userAgent, $bot) !== false) {
                $this->blockRequest('Automated tool detected', [
                    'bot' => $bot,
                    'user_agent' => substr($userAgent, 0, 200),
                    'ip' => $ip,
                ]);
            }
        }

        $this->checkRateLimit($request);
    }

    /**
     * فحص معدل الطلبات البسيط
     */
    protected function checkRateLimit(Request $request): void
    {
        if (config('app.env') !== 'production') {
            return;
        }

        $ip = $request->ip();
        $key = 'rate_limit:' . $ip . ':' . date('Y-m-d-H-i');
        $count = cache()->get($key, 0);

        if ($count > 100) {
            $this->blockRequest('Rate limit exceeded', [
                'ip' => $ip,
                'count' => $count,
            ]);
        }

        cache()->increment($key);
        cache()->put($key, $count + 1, 60);
    }

    /**
     * الجدار الرابع: التحقق من صحة الـ Host
     */
    protected function validateHost(Request $request): void
    {
        if (config('app.env') !== 'production') {
            return;
        }

        $allowedHosts = [
            parse_url(config('app.url'), PHP_URL_HOST),
            'localhost',
            '127.0.0.1',
            '::1',
        ];

        if (config('app.trusted_hosts')) {
            $allowedHosts = array_merge($allowedHosts, explode(',', config('app.trusted_hosts')));
        }

        $currentHost = $request->getHost();

        if (!in_array($currentHost, $allowedHosts) && !in_array($currentHost, array_map('trim', $allowedHosts))) {
            $this->blockRequest('Invalid Host header', [
                'host' => $currentHost,
                'allowed' => $allowedHosts,
                'ip' => $request->ip(),
            ]);
        }

        if ($request->isMethod('POST') || $request->isMethod('PUT') || $request->isMethod('DELETE')) {
            $referer = $request->header('Referer');
            if ($referer) {
                $refererHost = parse_url($referer, PHP_URL_HOST);
                if ($refererHost && !in_array($refererHost, $allowedHosts)) {
                    $this->blockRequest('Invalid Referer', [
                        'referer' => $referer,
                        'ip' => $request->ip(),
                    ]);
                }
            }
        }
    }

    /**
     * الجدار الخامس: إخفاء البيانات الحساسة في الاستجابة
     */
    protected function maskSensitiveData(Response $response): Response
    {
        if (config('app.env') === 'production') {
            $content = $response->getContent();
            if ($content) {
                $content = preg_replace('/\/home\/[a-zA-Z0-9_]+\/public_html/i', '', $content);
                $content = preg_replace('/\/var\/www\/html/i', '', $content);
                $content = preg_replace('/\/Applications\/XAMPP/i', '', $content);
                $content = preg_replace('/SQLSTATE\[.*?\]/i', 'Database error', $content);
                $content = preg_replace('/syntax error at/i', 'Query error', $content);
                $content = preg_replace('/Stack trace:/i', '', $content);
                $content = preg_replace('/#\d+\s/i', '', $content);

                $response->setContent($content);
            }
        }

        return $response;
    }

    /**
     * حظر الطلب وتسجيله
     */
    protected function blockRequest(string $message, array $context = []): void
    {
        Log::channel('security')->warning($message, $context);

        abort(
            403,
            config('app.env') === 'production'
            ? 'Access denied. Your request has been logged.'
            : $message
        );
    }

    /**
     * تسجيل النشاط المشبوه
     */
    protected function logSuspiciousActivity(Request $request): void
    {
        if ($request->isMethod('POST') || $request->isMethod('PUT') || $request->isMethod('DELETE')) {
            Log::channel('security')->info('Security check', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'user_agent' => substr($request->userAgent() ?? '', 0, 100),
            ]);
        }
    }
}
