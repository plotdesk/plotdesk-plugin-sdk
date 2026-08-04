<?php

namespace App\Plugins\Support;

use App\Models\Team;
use App\Models\User;

class PluginAi
{
    /**
     * @param  string|null  $contextId  Optionale Kennung des auslösenden Objekts.
     *                                  Wird als `context_id` in `ai_usages` mitgeschrieben, sodass ein Plugin
     *                                  die tatsächlich angefallenen Kosten einem eigenen Vorgang zuordnen kann
     *                                  statt sie nur global je Kontext-Typ zu sehen. Ohne Angabe bleibt das
     *                                  Feld leer — das bisherige Verhalten.
     *                                  Die Kennung mit einem eigenen Präfix versehen (z. B.
     *                                  `mein-plugin:run:123`): dasselbe Feld trägt bei Chat-Aufrufen die
     *                                  Chat-UUID, und die Kosten-Auswertungen fragen es danach ab. Ein Präfix
     *                                  hält die eigenen Zeilen strukturell aus diesen Abfragen heraus.
     *                                  Auf maximal 250 Zeichen gekürzt gespeichert.
     */
    public static function call(Team $team, string $systemPrompt, string $userPrompt, string $contextType, ?string $model = null, float $temperature = 0.7, bool $jsonMode = false, ?User $user = null, ?string $contextId = null): ?string {}

    /**
     * Wie call(), aber mit Werkzeugen: das Modell kann die übergebenen Tools
     * aufrufen, deren Handler werden hier ausgeführt und die Ergebnisse in die
     * Konversation zurückgefüttert, bis das Modell eine Textantwort gibt.
     * Versendet oder persistiert selbst nichts — was ein Tool bewirkt,
     * bestimmt allein sein Handler.
     *
     * Jedes Tool: `name` (^[a-zA-Z0-9_-]+$), optional `description` und
     * `parameters` (JSON-Schema-Objekt), plus `handler` als
     * `callable(array $arguments): string|array|null`. Array-Rückgaben werden
     * als JSON an das Modell gereicht, null als leeres Ergebnis. Wirft der
     * Handler, geht die Fehlermeldung als Tool-Ergebnis zurück ans Modell —
     * die übrigen Tools und der Lauf selbst laufen weiter.
     *
     * Bricht der Lauf am Rundenlimit oder an sich wiederholenden identischen
     * Tool-Aufrufen ab, wird eine letzte Anfrage OHNE Tools gestellt, damit
     * trotzdem eine Textantwort entsteht; `limitReached` ist dann gesetzt.
     *
     * @param  array<int, array{name: string, description?: string, parameters?: array<string, mixed>, handler: callable}>  $tools
     *
     * @throws InvalidArgumentException bei fehlerhaften Tool-Definitionen — ein
     *                                  Programmierfehler des Plugins, der laut scheitern soll statt still.
     */
    public static function callWithTools(Team $team, string $systemPrompt, string $userPrompt, string $contextType, array $tools, ?string $model = null, float $temperature = 0.7, int $maxToolRounds = 8, ?User $user = null, ?string $contextId = null): PluginAiToolResult {}
}
