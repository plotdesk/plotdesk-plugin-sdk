<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\UploadedFile;

class User
{
    public function setEmailAttribute(mixed $value = null): void {}

    public function getNameAttribute(?string $value = null): ?string {}

    public function isDeactivated(): bool {}

    public function chats() {}

    public function chatMessages() {}

    public function teamUsers() {}

    public function microsoftToken() {}

    public function getCurrentTeamId() {}

    public function chatbot() {}

    public static function getTeamId() {}

    public static function getTeam() {}

    public function getCachedCurrentTeam(): ?Team {}

    /**
     * Get all groups for the current team with proper filtering for admin view.
     *
     * Returns a flat collection: every accessible group sits at the top level,
     * children included. The frontend rebuilds the tree from `group_id` /
     * `grandparent_group_id`, so eager-loading the nested `groups` relation here
     * would only duplicate every subgroup into the payload.
     *
     * Do not widen this set to "everything reachable in the tree": it is not just
     * display data. {@see self::getGroupIds()} derives access decisions from it in
     * Workflow, Report, Chatbot, TableConnection, GlobalSearch and Billing, so any
     * extra id here grants access to that group's resources.
     *
     * @return Collection
     */
    public static function getGroups(?self $user = null) {}

    public static function getGroupIds(?self $user = null) {}

    /**
     * Direct group membership of a user (via group_users pivot) scoped to the
     * user's active team, without considering public groups or hierarchy
     * inheritance. Used for permission checks where only explicit memberships
     * in the CURRENT tenant should count.
     *
     * Cross-team scoping matters: the polymorphic group-assignment system
     * (see {@see \App\Concerns\HasGroupAssignments::scopeAvailableForUser()}
     * and {@see \App\Concerns\HasGroupAssignments::isExcludedForUser()}) feeds
     * these IDs into the inclusion/exclusion logic. A membership in another
     * team would otherwise leak across tenants - e.g. for an exclude-only
     * assignment in the current team, every group the user belongs to in any
     * other team would falsely satisfy `isAvailableForGroup()`, masking an
     * intentional exclusion in the current team.
     *
     * @return array<int,int>
     */
    public static function getDirectGroupIds(?self $user = null): array {}

    /**
     * Get all root groups for team admin views
     *
     * @return Collection
     */
    public static function getRootGroupsForAdmin() {}

    /**
     * Get all users for the current team formatted for directory display
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getTeamUsers() {}

    public static function hasGroupAccess($groupId, ?self $user = null) {}

    /**
     * Flush the {@see self::hasGroupAccess()} per-request memo.
     *
     * Called from observers when group memberships / role assignments change,
     * and from tests that switch the authenticated user inside a single PHP
     * process (so the previous user's cached `true/false` does not leak into
     * the next subject's permission checks).
     */
    public static function flushHasGroupAccessMemo(): void {}

    public function getIsPlatformAdminAttribute(): bool {}

    /**
     * Check if the user is a super platform admin (can manage other platform admins)
     */
    public function isSuperPlatformAdmin(): bool {}

    /**
     * Get the user who granted platform admin access
     *
     * @return BelongsTo<User, $this>
     */
    public function grantedByUser(): BelongsTo {}

    public function getFinalMessageSortingAttribute() {}

    public function getFinalNewChatPrivacyAttribute() {}

    public function getFinalSendShortcutAttribute() {}

    public function getFinalLanguageAttribute() {}

    public function getFinalDefaultModelAttribute() {}

    public function getFinalDefaultGroupModel($group) {}

    public function getFinalUserNameAttribute() {}

    public function getInitialsAttribute(): string {}

    public function toFlare(): array {}

    public function groups() {}

    /**
     * @return HasMany<EmailAccount, $this>
     */
    public function emailAccounts(): HasMany {}

    /**
     * Get the user roles for the user.
     */
    public function userRoles() {}

    /**
     * Get the roles for the user.
     */
    public function roles() {}

    /**
     * Check if the user has a specific permission globally.
     */
    public function hasPermission(string $permission): bool {}

    /**
     * Get all teams the user belongs to.
     * This overrides the Jetstream method to include open teams for platform admins.
     *
     * @return Collection<int, Team>
     */
    public function allTeams(): Collection {}

    /**
     * Determine if the user belongs to the given team.
     * This overrides the Jetstream method to include open teams for platform admins.
     */
    public function belongsToTeam(mixed $team = null): bool {}

    /**
     * Check if the user has a specific permission for a group.
     */
    public function hasPermissionForGroup(string $permission, int $groupId): bool {}

    public function hasAnyPermission(array $permissions, ?int $groupId = null): bool {}

    /**
     * Reset the per-instance permission cache so the next permission check
     * recomputes against the current `current_team_id`. Required when the team
     * context is switched on an existing User instance within a single request
     * - e.g. resolving AI-writable file containers for a chat whose team
     * differs from the user's active team. The Redis batch cache is already
     * team-keyed, so only the instance memo can go stale on a team switch.
     */
    public function resetCachedPermissionsData(): void {}

    /**
     * @param  int|string|null  $teamId
     */
    public static function clearPermissionsBatchCache(int $userId, mixed $teamId = null): void {}

    /**
     * Get all permissions for this user (both global and for specified group).
     */
    public function getAllPermissions(?int $groupId = null): array {}

    public static function getRecentChatsLimit() {}

    /**
     * Get the access tokens that belong to model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<TToken, $this>
     */
    public function tokens() {}

    /**
     * Determine if the current API token has a given scope.
     *
     * @param  string  $ability
     * @return bool
     */
    public function tokenCan(string $ability) {}

    /**
     * Determine if the current API token does not have a given scope.
     *
     * @param  string  $ability
     * @return bool
     */
    public function tokenCant(string $ability) {}

    /**
     * Create a new personal access token for the user.
     *
     * @param  string  $name
     * @param  array  $abilities
     * @param  \DateTimeInterface|null  $expiresAt
     * @return \Laravel\Sanctum\NewAccessToken
     */
    public function createToken(string $name, array $abilities = ['*'], ?DateTimeInterface $expiresAt = null) {}

    /**
     * Generate the token string.
     *
     * @return string
     */
    public function generateTokenString() {}

    /**
     * Get the access token currently associated with the user.
     *
     * @return TToken
     */
    public function currentAccessToken() {}

    /**
     * Set the current access token for the user.
     *
     * @param  TToken  $accessToken
     * @return $this
     */
    public function withAccessToken($accessToken) {}

    /**
     * Get a new factory instance for the model.
     *
     * @param  (callable(array<string, mixed>, static|null): array<string, mixed>)|array<string, mixed>|int|null  $count
     * @param  (callable(array<string, mixed>, static|null): array<string, mixed>)|array<string, mixed>  $state
     * @return TFactory
     */
    public static function factory($count = null, $state = []) {}

    /**
     * Update the user's profile photo.
     *
     * @param  \Illuminate\Http\UploadedFile  $photo
     * @param  string  $storagePath
     * @return void
     */
    public function updateProfilePhoto(UploadedFile $photo, $storagePath = 'profile-photos') {}

    /**
     * Delete the user's profile photo.
     *
     * @return void
     */
    public function deleteProfilePhoto() {}

    /**
     * Determine if the given team is the current team.
     *
     * @param  mixed  $team
     * @return bool
     */
    public function isCurrentTeam($team) {}

    /**
     * Get the current team of the user's context.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currentTeam() {}

    /**
     * Switch the user's context to the given team.
     *
     * @param  mixed  $team
     * @return bool
     */
    public function switchTeam($team) {}

    /**
     * Get all of the teams the user owns.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ownedTeams() {}

    /**
     * Get all of the teams the user belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teams() {}

    /**
     * Get the user's "personal" team.
     *
     * @return \App\Models\Team
     */
    public function personalTeam() {}

    /**
     * Determine if the user owns the given team.
     *
     * @param  mixed  $team
     * @return bool
     */
    public function ownsTeam($team) {}

    /**
     * Get the role that the user has on the team.
     *
     * @param  mixed  $team
     * @return \Laravel\Jetstream\Role|null
     */
    public function teamRole($team) {}

    /**
     * Determine if the user has the given role on the given team.
     *
     * @param  mixed  $team
     * @param  string  $role
     * @return bool
     */
    public function hasTeamRole($team, string $role) {}

    /**
     * Get the user's permissions for the given team.
     *
     * @param  mixed  $team
     * @return array
     */
    public function teamPermissions($team) {}

    /**
     * Determine if the user has the given permission on the given team.
     *
     * @param  mixed  $team
     * @param  string  $permission
     * @return bool
     */
    public function hasTeamPermission($team, string $permission) {}

    /**
     * Get the entity's notifications.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<DatabaseNotification, $this>
     */
    public function notifications() {}

    /**
     * Get the entity's read notifications.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<DatabaseNotification, $this>
     */
    public function readNotifications() {}

    /**
     * Get the entity's unread notifications.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<DatabaseNotification, $this>
     */
    public function unreadNotifications() {}

    /**
     * Send the given notification.
     *
     * @param  mixed  $instance
     * @return void
     */
    public function notify($instance) {}

    /**
     * Send the given notification immediately.
     *
     * @param  mixed  $instance
     * @param  array|null  $channels
     * @return void
     */
    public function notifyNow($instance, ?array $channels = null) {}

    /**
     * Get the notification routing information for the given driver.
     *
     * @param  string  $driver
     * @param  \Illuminate\Notifications\Notification|null  $notification
     * @return mixed
     */
    public function routeNotificationFor($driver, $notification = null) {}

    /**
     * Determine if two-factor authentication has been enabled.
     *
     * @return bool
     */
    public function hasEnabledTwoFactorAuthentication() {}

    /**
     * Get the user's two factor authentication recovery codes.
     *
     * @return array
     */
    public function recoveryCodes() {}

    /**
     * Replace the given recovery code with a new one in the user's stored codes.
     *
     * @param  string  $code
     * @return void
     */
    public function replaceRecoveryCode($code) {}

    /**
     * Get the QR code SVG of the user's two factor authentication QR code URL.
     *
     * @return string
     */
    public function twoFactorQrCodeSvg() {}

    /**
     * Get the two factor authentication QR code URL.
     *
     * @return string
     */
    public function twoFactorQrCodeUrl() {}
}
