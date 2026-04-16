<?php

namespace App\Http\Controllers\Admin\Perfume;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ActivityLogService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class WebsiteSettingController extends Controller
{
    use ResponseTrait;

    /**
     * Display the website settings page.
     * Ported from perfume_admin with professional audit and caching logic.
     */
    public function index()
    {
        $settings = Setting::getAll();
        return view('admin.perfume.website_settings.index', compact('settings'));
    }

    /**
     * Update site settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'site_name'            => 'nullable|string|max:100',
            'site_name_en'         => 'nullable|string|max:100',
            'site_description'     => 'nullable|string|max:500',
            'site_description_en'  => 'nullable|string|max:500',
            'logo'                 => 'nullable|image|mimes:png,jpg,jpeg,svg,webp',
            'favicon'              => 'nullable|image|mimes:png,ico,jpg',
            'primary_color'        => 'nullable|string|max:20',
            'contact_email'        => 'nullable|email|max:150',
            'contact_phone'        => 'nullable|string|max:30',
            'contact_whatsapp'     => 'nullable|string|max:30',
        ]);

        $textFields = [
            'site_name', 'site_name_en',
            'site_description', 'site_description_en',
            'primary_color', 'secondary_color', 'accent_color',
            'contact_email', 'contact_phone', 'contact_whatsapp', 'contact_address',
            'social_facebook', 'social_instagram', 'social_twitter',
            'social_tiktok', 'social_youtube', 'social_snapchat',
            'footer_text', 'footer_text_en',
            'google_analytics', 'meta_keywords',
        ];

        foreach ($textFields as $key) {
            if ($request->has($key)) {
                Setting::updateOrCreate(
                    ['id_key' => $key],
                    ['key_value' => $request->input($key) ?? '']
                );
            }
        }

        // Updating Images via professional UploadTrait logic
        $imageFields = ['logo', 'favicon', 'og_image'];
        $model = new Setting();
        foreach ($imageFields as $imgKey) {
            if ($request->hasFile($imgKey)) {
                $path = $model->uploadAllTyps($request->file($imgKey), 'settings');
                Setting::updateOrCreate(
                    ['id_key' => $imgKey],
                    ['key_value' => $path]
                );
            }
        }

        Cache::forget('settings');

        (new ActivityLogService())->log('admin.logs.update_settings');

        return response()->json([
            'status' => 'success',
            'msg' => __('admin.updated'),
        ]);
    }
}
