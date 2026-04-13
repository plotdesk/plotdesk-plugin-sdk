<?php

namespace App\Plugins\Support;

use App\Plugins\Support\App\Models\Team;
use App\Plugins\Support\App\Models\User;

class PluginChat
{
    public static function run(Team $team, int $groupId, string $userMessage, string $contextType, ?User $user = null, ?string $model = null, bool $hidden = true): ?string {}
}
