<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function general()
    {
        $settings = config('site_settings', []);

        return view('admin.pages.settings.general', [
            'settings' => $settings,
        ]);
    }

    public function updateGeneral(Request $request)
    {
        $data = $request->validate([
            'site_email' => ['nullable', 'email'],
            'site_phone' => ['nullable', 'string', 'max:50'],
            'site_address' => ['nullable', 'string', 'max:255'],
            'site_working_hours' => ['nullable', 'string', 'max:255'],
            'site_facebook_url' => ['nullable', 'url', 'max:255'],
            'site_instagram_url' => ['nullable', 'url', 'max:255'],
            'site_google_map_embed_url' => ['nullable', 'url', 'max:1000'],
            'home_hero_background_image' => ['nullable', 'image', 'max:5120'],
            'home_hero_title' => ['nullable', 'string', 'max:255'],
            'home_hero_subtitle' => ['nullable', 'string', 'max:255'],
            'home_announcement_text' => ['nullable', 'string', 'max:500'],
            'client_show_vouchers' => ['nullable', 'in:1'],
        ]);

        $kv = [
            'site_email' => $data['site_email'] ?? null,
            'site_phone' => $data['site_phone'] ?? null,
            'site_address' => $data['site_address'] ?? null,
            'site_working_hours' => $data['site_working_hours'] ?? null,
            'site_facebook_url' => $data['site_facebook_url'] ?? null,
            'site_instagram_url' => $data['site_instagram_url'] ?? null,
            'site_google_map_embed_url' => $data['site_google_map_embed_url'] ?? null,
            'home_hero_title' => $data['home_hero_title'] ?? null,
            'home_hero_subtitle' => $data['home_hero_subtitle'] ?? null,
            'home_announcement_text' => $data['home_announcement_text'] ?? null,
            'client_show_vouchers' => isset($data['client_show_vouchers']) ? '1' : '0',
        ];

        if ($request->hasFile('home_hero_background_image')) {
            $path = $request->file('home_hero_background_image')->store('images', 'public');
            $kv['home_hero_background_path'] = $path;
        }

        foreach ($kv as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Cache::forget('settings.kv');

        return redirect()
            ->route('admin.settings.general')
            ->with('success', 'Đã cập nhật cài đặt tổng quan.');
    }

    public function shipping()
    {
        $settings = config('site_settings', []);

        return view('admin.pages.settings.shipping', [
            'settings' => $settings,
        ]);
    }

    public function updateShipping(Request $request)
    {
        $data = $request->validate([
            'shipping_fee' => ['required', 'numeric', 'min:0'],
            'free_shipping_threshold' => ['required', 'numeric', 'min:0'],
        ]);

        $kv = [
            'shipping_fee' => (string) $data['shipping_fee'],
            'free_shipping_threshold' => (string) $data['free_shipping_threshold'],
        ];

        foreach ($kv as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Cache::forget('settings.kv');

        return redirect()
            ->route('admin.settings.shipping')
            ->with('success', 'Đã cập nhật chính sách vận chuyển.');
    }
}
