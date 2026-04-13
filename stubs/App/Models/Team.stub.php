<?php

namespace App\Models;

class Team extends Team
{
    /**
     * @return BelongsTo<User, $this>
     */
    public function teamOwner(): Illuminate\Database\Eloquent\Relations\BelongsTo {}

    public function groups() {}

    public function emailProviders() {}

    public function emails() {}

    public function emailAccounts() {}

    public function presets() {}

    public function statusChecks() {}

    public function disabledPredefinedDocumentationPages() {}

    /**
     * Get the roles for the team.
     */
    public function roles() {}

    /**
     * @return HasMany<AzureGroupMapping, $this>
     */
    public function azureGroupMappings(): Illuminate\Database\Eloquent\Relations\HasMany {}

    /**
     * @return HasMany<AzureGroupSyncLog, $this>
     */
    public function azureGroupSyncLogs(): Illuminate\Database\Eloquent\Relations\HasMany {}

    /**
     * @return HasMany<AiUsage, $this>
     */
    public function aiUsages(): Illuminate\Database\Eloquent\Relations\HasMany {}

    /**
     * @return HasMany<Chat, $this>
     */
    public function chats(): Illuminate\Database\Eloquent\Relations\HasMany {}

    /**
     * @return HasMany<Instruction, $this>
     */
    public function instructions(): Illuminate\Database\Eloquent\Relations\HasMany {}

    /**
     * @return HasMany<Table, $this>
     */
    public function tables(): Illuminate\Database\Eloquent\Relations\HasMany {}

    /**
     * @return HasMany<AiProvider, $this>
     */
    public function providers(): Illuminate\Database\Eloquent\Relations\HasMany {}

    /**
     * @return HasMany<AiModelDeployment, $this>
     */
    public function aiModelDeployments(): Illuminate\Database\Eloquent\Relations\HasMany {}

    /**
     * @return HasMany<TeamAppSetting, $this>
     */
    public function teamAppSettings(): Illuminate\Database\Eloquent\Relations\HasMany {}

    public function translatedWelcomeMessage(): Illuminate\Database\Eloquent\Casts\Attribute {}

    public function updateLogo(Illuminate\Http\UploadedFile $photo, $storagePath = 'logos'): void {}

    public function deleteLogo(): void {}

    public function logoUrl(): Illuminate\Database\Eloquent\Casts\Attribute {}

    public static function getOneTeamLogoUrl() {}

    public static function getOneTeamLogoPath() {}

    public function updateLogoDark(Illuminate\Http\UploadedFile $photo, $storagePath = 'logos'): void {}

    public function deleteLogoDark(): void {}

    public function logoDarkUrl(): Illuminate\Database\Eloquent\Casts\Attribute {}

    public static function getOneTeamLogoDarkUrl(): ?string {}

    public static function getOneTeamEmailDomain() {}

    public function updateFavicon(Illuminate\Http\UploadedFile $photo, $storagePath = 'favicons'): void {}

    public function deleteFavicon(): void {}

    public function faviconUrl(): Illuminate\Database\Eloquent\Casts\Attribute {}

    public static function getOneTeamFaviconUrl() {}

    public function updateAppicon(Illuminate\Http\UploadedFile $photo, $storagePath = 'appicons'): void {}

    public function deleteAppicon(): void {}

    public function appiconUrl(): Illuminate\Database\Eloquent\Casts\Attribute {}

    public static function getOneTeamAppiconUrl() {}

    public function updateAvatar(Illuminate\Http\UploadedFile $photo, $storagePath = 'avatars'): void {}

    public function deleteAvatar(): void {}

    public function chatbotAvatarUrl(): Illuminate\Database\Eloquent\Casts\Attribute {}

    public static function getTeamData($key, $fallback = null) {}

    public static function getEmailSignature(): ?string {}

    public static function getOneTeam() {}

    public function getInviteHash($invitation): string {}

    public static function findByDomain(string $domain): ?self {}

    public static function getCurrentTeamFromRequest(): ?self {}

    /**
     * @return array<string, mixed>|null
     */
    public function getSsoConfig(string $provider): ?array {}

    /**
     * @param  array<string, mixed>  $config
     */
    public function setSsoConfig(string $provider, array $config): void {}

    public function removeSsoConfig(string $provider): void {}

    public function isSsoActive(string $provider): bool {}

    /**
     * @return array<string, mixed>|null
     */
    public static function getSsoConfigForCurrentTeam(string $provider): ?array {}

    public function isAgentModeAvailable(): bool {}

    public function isAgentModeDefault(): bool {}

    public static function getCustomerDomain(?App\Models\Team $team = null): string {}

    /**
     * Get a new factory instance for the model.
     *
     * @param  (callable(array<string, mixed>, static|null): array<string, mixed>)|array<string, mixed>|int|null  $count
     * @param  (callable(array<string, mixed>, static|null): array<string, mixed>)|array<string, mixed>  $state
     * @return TFactory
     */
    public static function factory($count = null, $state = [
    ]) {}
}
