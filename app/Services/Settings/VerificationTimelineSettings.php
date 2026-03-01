<?php

namespace App\Services\Settings;

use App\Models\AppSetting;
use Illuminate\Support\Facades\Cache;

class VerificationTimelineSettings
{
    public const SETTING_KEY = 'verification.pending_timeline_hours';
    public const DEFAULT_MIN_HOURS = 24;
    public const DEFAULT_MAX_HOURS = 72;
    private const CACHE_KEY = 'settings.verification.pending_timeline_hours';

    /**
     * @return array{min:int,max:int}
     */
    public static function get(): array
    {
        /** @var array{min:int,max:int} */
        return Cache::rememberForever(self::CACHE_KEY, function (): array {
            $savedValue = AppSetting::query()
                ->where('key', self::SETTING_KEY)
                ->value('value');

            $value = is_array($savedValue) ? $savedValue : [];
            $min = intval($value['min'] ?? self::DEFAULT_MIN_HOURS);
            $max = intval($value['max'] ?? self::DEFAULT_MAX_HOURS);

            if ($min < 1) {
                $min = self::DEFAULT_MIN_HOURS;
            }

            if ($max < $min) {
                $max = max($min, self::DEFAULT_MAX_HOURS);
            }

            return [
                'min' => $min,
                'max' => $max,
            ];
        });
    }

    public static function getMinHours(): int
    {
        return self::get()['min'];
    }

    public static function getMaxHours(): int
    {
        return self::get()['max'];
    }

    public static function getDisplayText(): string
    {
        return self::getMinHours() . '-' . self::getMaxHours() . ' hours';
    }

    public static function set(int $minHours, int $maxHours): void
    {
        $minHours = max(1, $minHours);
        $maxHours = max($minHours, $maxHours);

        AppSetting::query()->updateOrCreate(
            ['key' => self::SETTING_KEY],
            [
                'value' => [
                    'min' => $minHours,
                    'max' => $maxHours,
                ],
            ],
        );

        Cache::forget(self::CACHE_KEY);
    }
}

