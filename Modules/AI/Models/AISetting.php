<?php

namespace Modules\AI\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AISetting extends Model
{
    use HasFactory;

    protected $table = 'ai_settings';

    protected $fillable = [
        'provider',
        'key',
        'value',
        'type',
        'is_encrypted',
        'is_active',
    ];

    protected $casts = [
        'is_encrypted' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get a setting value.
     */
    public static function getSetting(string $key, ?string $provider = null)
    {
        $setting = self::where('key', $key)
            ->where('provider', $provider)
            ->first();

        return $setting ? $setting->value : null;
    }

    /**
     * Set a setting value.
     */
    public static function setSetting(string $key, $value, ?string $provider = null, string $type = 'string', bool $isEncrypted = false)
    {
        return self::updateOrCreate(
            ['key' => $key, 'provider' => $provider],
            [
                'value' => $value,
                'type' => $type,
                'is_encrypted' => $isEncrypted,
                'is_active' => true,
            ]
        );
    }
}
