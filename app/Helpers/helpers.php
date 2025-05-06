<?php 

use Illuminate\Support\Facades\Cache;

if (!function_exists('getSettingValue')) {
    /**
     * Get the setting value by key, fetched from cache if available.
     *
     * @param string $key
     * @return string|null
     */
    function getSettingValue($key)
    {
        // Fetch the settings from cache
        $settings = Cache::get('settings');

        if (!$settings) {
            // If not in cache, fetch from database and cache it
            $settings = \App\Models\Setting::all();
            Cache::put('settings', $settings);
        }

        // Search for the setting by key
        $setting = $settings->firstWhere('key', $key);
        
        return $setting ? $setting->value : null;
    }
    
}
if (!function_exists('getPlainContent')) {
    /**
     * Get the setting value by key, fetched from cache if available.
     *
     * @param string $key
     * @return string|null
     */
    function getPlainContent($content)
    {
        $decodedContent = json_decode($content, true);
        $content = isset($decodedContent['content']) ? $decodedContent['content'] : $content;
        $plainTextContent = strip_tags($content);
        $plainTextContent = preg_replace('/\s+/', ' ', $plainTextContent); 
        return $plainTextContent;
    }
    if (!function_exists('getAuthUserId')) {
        function getAuthUserId()
        {
            return auth()->id();
        }
    }
}
