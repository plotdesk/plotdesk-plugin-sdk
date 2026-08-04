<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Http\UploadedFile;

class Group extends Model
{
    public function parentGroup(): BelongsTo {}

    public function grandparentGroup(): BelongsTo {}

    public function groups(): HasMany {}

    public function grandchildGroups(): HasManyThrough {}

    public function team(): BelongsTo {}

    /**
     * @return BelongsTo<User, $this>
     */
    public function createdBy(): BelongsTo {}

    public function groupUsers(): HasMany {}

    public function startPreset(): BelongsTo {}

    public function startPresetWithoutContent(): BelongsTo {}

    /** @return BelongsTo<Workflow, $this> */
    public function startWorkflow(): BelongsTo {}

    public function teamAppSettings(): HasMany {}

    /**
     * Get the user roles for the group.
     */
    public function userRoles(): HasMany {}

    /**
     * Get the memories for the group.
     */
    public function memories(): HasMany {}

    public static function getChildGroupIds(string $groupId): array {}

    public static function getParentGroupId(string $groupId): ?int {}

    public static function getGrandparentGroupId(string $groupId): ?int {}

    public static function getParentGroupIds(string $groupId): array {}

    /**
     * Cached single-row lookup for a Group by its primary key.
     *
     * Used by hot-path permission checks (e.g. {@see \App\Models\User::hasGroupAccess()})
     * that previously fired an uncached `Group::find()` for every iteration. The
     * cache is busted via {@see \App\Observers\GroupObserver::bustHierarchyCaches()}
     * (same set of keys as the sibling `group_parent_id_v2_*` / `group_grandparent_id_v2_*`
     * helpers) so the in-memory model stays in sync with persisted state.
     *
     * Returns null when the group does not exist - never throws.
     */
    public static function findCached(int $id): ?Group {}

    /**
     * Resolve the AI response language by walking the group hierarchy
     * (self -> parent -> grandparent). Returns the first non-empty value
     * or null if none of the groups in the hierarchy define a language.
     *
     * Note: This only affects the AI response language used in system prompts.
     * It does NOT influence the UI/interface locale, which is resolved
     * independently via User::final_language and the SetLocale middleware.
     */
    public function resolveAiResponseLanguage(): ?string {}

    /**
     * Resolve the AI response language that this group would inherit if its
     * own language was unset. Walks parent -> grandparent only (excludes self).
     * Returns null if neither parent nor grandparent define a language.
     *
     * Useful for UIs that need to preview the effective fallback shown next
     * to an "Inherit" option in a language picker.
     */
    public function resolveInheritedAiResponseLanguage(): ?string {}

    public function translatedWelcomeMessage(): void {}

    public function translatedInitialSuggestions(?string $targetLanguage = null): void {}

    /**
     * Determine if the group is a level 1 group (root group)
     */
    public function isLevelOne(): bool {}

    /**
     * Determine if the group is a level 2 group (child of root)
     */
    public function isLevelTwo(): bool {}

    /**
     * Determine if the group is a level 3 group (grandchild)
     */
    public function isLevelThree(): bool {}

    /**
     * Get the level of the group (1, 2, or 3)
     */
    public function getLevel(): int {}

    /**
     * Get all ancestor groups (parent and grandparent)
     */
    public function getAncestorIds(): array {}

    /**
     * Check if this group is a descendant of the provided group
     */
    public function isDescendantOf(int $ancestorId): bool {}

    public static function flushUserHasAccessMemo(): void {}

    /**
     * Check if a user has access to this group.
     *
     * Originally fired 1-3 `exists()` subqueries per call (direct membership
     * on self, then on parent and/or grandparent depending on hierarchy /
     * privacy). The MTA listing hot path (PR #935) calls this thousands of
     * times per request via {@see \App\Models\User::hasGroupAccess()}, so it
     * is now collapsed to a single `GroupUser` pluck across `[self, parent,
     * grandparent]` plus cached metadata lookups via {@see self::findCached()}.
     *
     * Semantically identical to the previous implementation - returns `true`
     * iff the user is either directly attached to the group or to a private
     * ancestor in the public-chain, or the chain is fully public.
     */
    public function userHasAccess(int $userId): bool {}

    /**
     * Determine whether the given user may delete this group.
     *
     * Global `delete_teams` always satisfies the check. For sub-teams (groups
     * with a parent), holding `delete_sub_teams` on the direct parent group
     * also satisfies it. Top-level groups (no parent) always require the
     * global permission.
     */
    public function canBeDeletedByUser(User $user): bool {}

    /**
     * Resource models whose legacy `group_id` column represents a team scope
     * (i.e. it can dangle into an orphaned reference when its team is deleted).
     *
     * Filters {@see self::SCOPE_RESOURCE_MODELS} down to models that actually
     * have `group_id` in `$fillable` and treat it as a scope - Blueprint,
     * Table and AiModelDeployment have no such column, and Workflow stores its
     * "start team" there (`legacyGroupIdRepresentsScope() === false`).
     *
     * @return array<int,class-string<Model>>
     */
    public static function legacyScopeResourceModels(): array {}

    /**
     * Resources that would be orphaned (become platform-wide or a dangling
     * legacy reference) if this team were deleted, because this team is their
     * ONLY team scope.
     *
     * Deleting a team cascades its `group_assignments` rows away at the DB
     * level, so a resource scoped only to this team would silently turn
     * platform-wide ("visible to everyone"); a legacy `group_id` reference
     * would dangle and make the resource uneditable. The delete endpoints use
     * this to block the deletion (mirroring the child-group guard) so an admin
     * reassigns or deletes those resources first.
     *
     * Resources also scoped to another (surviving) team are NOT counted - they
     * keep that scope and are not orphaned.
     *
     * @return array<string,int> Friendly resource label (e.g. "Presets") => count.
     */
    public function blockingResourcesForDeletion(): array {}

    public function updateChatbotAvatar(UploadedFile $photo, string $storagePath = 'group-avatars'): void {}

    public function deleteChatbotAvatar(): void {}

    public function chatbotAvatarUrl(): Attribute {}

    public function getFinalEnableResponseSuggestionsAttribute(): bool {}

    public function getFinalVoiceModeAttribute(): bool {}

    public function getFinalHidePayloadsAttribute(): bool {}

    /**
     * Resolve the hide_payloads value this group would inherit if its own value
     * was 'inherit', together with the ancestor it comes from. Walks parent ->
     * grandparent only (excludes self and the platform).
     *
     * Returns null when no ancestor decides — the caller then applies the
     * platform default. Kept free of the team relation on purpose: the group is
     * serialized into an Inertia prop, and a loaded team would ship the whole
     * platform model (including provider credentials) to the client.
     *
     * @return array{value: bool, source: string}|null
     */
    public function resolveInheritedHidePayloadsFromAncestors(): ?array {}

    public function getFinalAgentModeAttribute(): string {}

    public function getFinalIsChatPrivacyForcedAttribute(): bool {}

    /**
     * Resolve the effective chat privacy decision for a given group id and a
     * requested visibility, eagerly loading the team relation. Returns the
     * boolean to persist on the resulting model.
     *
     * When the group id is absent, zero, or does not resolve to a row, tenant-level
     * rules from {@see Team::resolveSchedulerChatPrivacyPolicy()} apply when
     * `$teamId` is provided (tenant of the scheduler).
     */
    public static function resolveChatPrivacyForGroupId(?int $groupId = null, bool $requested, ?int $teamId = null): bool {}

    /**
     * Resolve the effective chat privacy policy for a chat created in this group.
     *
     * Combines tenant-level (Team) and group-level forcing rules with the requested
     * value. When forcing is active, the default of the level that performs the lock
     * applies (team default for team-level forcing, group default for group-only
     * forcing, with team fallback when the group has no own default). When no
     * forcing applies, the requested value wins, falling back to private when
     * nothing was requested.
     *
     * @return array{is_private: bool, is_forced: bool, forced_source: ?string, default_is_private: bool}
     */
    public function resolveChatPrivacyPolicy(?bool $requested = null): array {}

    public function isAgentModeAvailable(): bool {}

    public function isAgentModeDefault(): bool {}

    /**
     * Get a new factory instance for the model.
     *
     * @param  (callable(array<string, mixed>, static|null): array<string, mixed>)|array<string, mixed>|int|null  $count
     * @param  (callable(array<string, mixed>, static|null): array<string, mixed>)|array<string, mixed>  $state
     * @return TFactory
     */
    public static function factory($count = null, $state = []) {}
}
