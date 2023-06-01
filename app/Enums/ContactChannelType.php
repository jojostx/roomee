<?php

namespace App\Enums;


enum ContactChannelType: string
{
    case WHATSAPP = 'whatsapp';
    case FACEBOOK = 'facebook';
    case INSTAGRAM = 'instagram';
    case TWITTER = 'twitter';
    case EMAIL = 'email';

    public static function getChannelNames(array $except = [])
    {
        $except = \collect($except)
            ->filter(function ($case) {
                $case = \is_string($case) ? ContactChannelType::tryfrom($case) : $case;

                return $case instanceof ContactChannelType;
            })
            ->map(fn ($case) => ContactChannelType::tryfrom($case));

        $cases = ContactChannelType::cases();

        return \collect($cases)
            ->reject(fn (ContactChannelType $case) => $except->contains($case))
            ->map(fn (ContactChannelType $case) => $case->value);
    }
}
