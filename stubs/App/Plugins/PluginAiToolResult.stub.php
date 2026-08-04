<?php

namespace App\Plugins\Support;

class PluginAiToolResult
{
    public readonly ?string $text;

    /** @var list<array{name: string, arguments: array<string, mixed>, result: string}> */
    public readonly array $toolCalls;

    public readonly bool $limitReached;

    public function succeeded(): bool {}
}
