<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SecurityShieldMiddleware
{
    /**
     * SQL Injection Patterns
     */
    protected array $sqlPatterns = [
        // Refined to avoid false positives on dotted route names like admin.admins.create
        '/(?<![a-zA-Z._])\b(SELECT|INSERT|UPDATE|DELETE|DROP|ALTER|CREATE|TRUNCATE|EXEC|EXECUTE)\b(?![a-zA-Z._])/i',
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
     * XSS Patterns
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
     * Bad Bots
     */
    protected array $badBots = [
        'curl', 'wget', 'python', 'perl', 'ruby', 'php', 'postman', 'insomnia', 
        'sqlmap', 'nikto', 'nmap', 'masscan', 'dirbuster', 'gobuster', 'wfuzz', 
        'burp', 'zap', 'acunetix', 'nessus', 'openvas', 'w3af', 'arachni', 
        'scanner', 'scraper', 'crawler', 'spider'
    ];

    /**
     * Enhanced Firewall
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Inputs Filtering (SQL Injection & XSS)
        $this->filterPayload($request);

        // 2. Bots Protection
        $this->blockBots($request);

        // 3. Host Validation
        $this->validateHost($request);

        $response = $next($request);

        // 4. Security Headers
        $response = $this->addSecurityHeaders($response, $request);

        // 5. Mask Sensitive Data
        $response = $this->maskSensitiveData($response);

        return $response;
    }

    protected function addSecurityHeaders(Response $response, Request $request): Response
    {
        if (method_exists($response, 'header')) {
            $response->header('X-Content-Type-Options', 'nosniff');
            $response->header('X-Frame-Options', 'DENY');
            $response->header('X-XSS-Protection', '1; mode=block');
            $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
            $response->header('Referrer-Policy', 'strict-origin-when-cross-origin');
        }
        return $response;
    }

    protected function filterPayload(Request $request): void
    {
        $input = $request->all();
        $sanitized = false;

        array_walk_recursive($input, function (&$item) {
            if (!is_string($item)) return;

            foreach ($this->sqlPatterns as $pattern) {
                if (preg_match($pattern, $item)) {
                    $this->blockRequest('SQL Injection attempt detected', ['input' => substr($item, 0, 100)]);
                }
            }

            foreach ($this->xssPatterns as $pattern) {
                if (preg_match($pattern, $item)) {
                    $this->blockRequest('XSS attempt detected', ['input' => substr($item, 0, 100)]);
                }
            }

            $original = $item;
            $item = htmlspecialchars($item, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $item = strip_tags($item);
            if ($original !== $item) $GLOBALS['sanitized'] = true;
        });

        if (isset($GLOBALS['sanitized']) && $GLOBALS['sanitized']) {
            $request->merge($input);
            unset($GLOBALS['sanitized']);
        }
    }

    protected function blockBots(Request $request): void
    {
        $userAgent = $request->header('User-Agent', '');
        foreach ($this->badBots as $bot) {
            if (stripos($userAgent, $bot) !== false) {
                $this->blockRequest('Automated tool detected', ['bot' => $bot]);
            }
        }
    }

    protected function validateHost(Request $request): void
    {
        if (config('app.env') !== 'production') return;
        
        $currentHost = $request->getHost();
        $allowed = [parse_url(config('app.url'), PHP_URL_HOST), 'localhost', '127.0.0.1'];
        
        if (!in_array($currentHost, $allowed)) {
            $this->blockRequest('Invalid Host header', ['host' => $currentHost]);
        }
    }

    protected function maskSensitiveData(Response $response): Response
    {
        if (config('app.env') === 'production') {
            $content = $response->getContent();
            if ($content) {
                $content = preg_replace('/SQLSTATE\[.*?\]/i', 'Database error', $content);
                $response->setContent($content);
            }
        }
        return $response;
    }

    protected function blockRequest(string $message, array $context = []): void
    {
        Log::channel('security')->warning($message, $context);
        abort(403, config('app.env') === 'production' ? 'Access denied. Your request has been logged.' : $message);
    }
}
