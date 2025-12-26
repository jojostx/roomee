<?php

namespace App\Filament\Widgets;

use App\Enums\RoommateRequestStatus;
use App\Models\RoommateRequest;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class SystemStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Users', User::count()),
            Stat::make('Verified Users', User::whereNotNull('email_verified_at')->count()),
            Stat::make('Pending Requests', RoommateRequest::where('status', RoommateRequestStatus::PENDING->value)->count()),
            Stat::make('Accepted Requests', RoommateRequest::where('status', RoommateRequestStatus::ACCEPTED->value)->count()),
            Stat::make('Favorites', DB::table('favorites')->count()),
            Stat::make('Blocks', DB::table('blocklists')->count()),
            Stat::make('Reports', DB::table('report_user')->count()),
        ];
    }
}
