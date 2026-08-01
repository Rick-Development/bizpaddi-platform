<?php
namespace Modules\Settings\Models;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'description'];

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, $default = null)
    {
        return Cache::rememberForever("settings.{$key}", function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value by key.
     */
    public static function set(string $key, $value, $type = 'string', $description = null)
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'description' => $description
            ]
        );

        Cache::forget("settings.{$key}");

        return $setting;
    }
}

