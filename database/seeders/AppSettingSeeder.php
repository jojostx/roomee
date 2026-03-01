<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use App\Services\Settings\VerificationTimelineSettings;
use Illuminate\Database\Seeder;

class AppSettingSeeder extends Seeder
{
    public function run(): void
    {
        AppSetting::query()->updateOrCreate(
            ['key' => VerificationTimelineSettings::SETTING_KEY],
            [
                'value' => [
                    'min' => VerificationTimelineSettings::DEFAULT_MIN_HOURS,
                    'max' => VerificationTimelineSettings::DEFAULT_MAX_HOURS,
                ],
            ],
        );
    }
}

