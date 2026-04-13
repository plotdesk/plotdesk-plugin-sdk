<?php

namespace App\Plugins\Support;

use App\Plugins\Support\App\Models\Team;
use App\Plugins\Support\App\Models\User;

class PluginAi
{
    public static function call(Team $team, string $systemPrompt, string $userPrompt, string $contextType, ?string $model = null, float $temperature = 0.7, bool $jsonMode = false, ?User $user = null): ?string {}
}
