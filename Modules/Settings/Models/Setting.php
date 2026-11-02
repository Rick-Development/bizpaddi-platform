<?php
namespace Modules\Settings\Models;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'description'];

    protected static ?array $runtimeCache = null;

    /**
     * Get a setting value by key (loaded once per request from a single cached map).
     */
    public static function get(string $key, $default = null)
    {
        if (self::$runtimeCache === null) {
            try {
                self::$runtimeCache = Cache::rememberForever('settings.all_map', function () {
                    return self::pluck('value', 'key')->toArray();
                });
            } catch (\Throwable $e) {
                self::$runtimeCache = [];
            }
        }

        return array_key_exists($key, self::$runtimeCache) && self::$runtimeCache[$key] !== null
            ? self::$runtimeCache[$key]
            : $default;
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

        self::$runtimeCache = null;
        Cache::forget("settings.{$key}");
        Cache::forget('settings.all_map');

        return $setting;
    }
}

