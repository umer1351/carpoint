<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Badge;
use App\Models\UserBadge;

class BadgeHelper
{
    public static function assignBadge($userId, $badgeName, $assignedBy = null)
    {
        $user = User::find($userId);
        $badge = Badge::where('name', $badgeName)->first();

        if ($user && $badge) {
            // Check if badge is already assigned
            if (!$user->badges()->where('badge_id', $badge->id)->exists()) {
                $user->badges()->attach($badge->id, ['assigned_by' => $assignedBy]);
            }
        }
    }

    public static function checkAndAssignAutoBadges($user)
    {
        // "Top Seller" badge if user has sold more than 10 listings
        if ($user->listings()->where('status', 'sold')->count() >= 10) {
            self::assignBadge($user->id, 'Top Seller');
        }

        // "Engaged User" badge if user has more than 20 listings
        if ($user->listings()->count() >= 20) {
            self::assignBadge($user->id, 'Engaged User');
        }
    }
}
