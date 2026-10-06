<?php

namespace ME\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use ME\Models\UserActivity;

/**
 * System numbers for a dashboard widget: users, roles, logins, activity, mail and SMS.
 * Data only — show it with @include('me::widgets.system-overview') or use the arrays in your own view.
 * Every query is guarded, so a missing table returns 0 / an empty list instead of an error.
 */
class SystemOverview
{
    /**
     * @return array{users: int, active_users: int, roles: int, logins_today: int, failed_today: int, activities_today: int, mail_month: int, sms_month: int}
     */
    public function stats(): array
    {
        $today = now()->startOfDay();
        $monthStart = now()->startOfMonth();
        $logins = fn (string $status) => DB::table('user_activities')->where('activity_type', 'login')->where('status', $status)->where('activity_at', '>=', $today)->count();

        return [
            'users' => $this->safe(fn () => DB::table('users')->count(), 0),
            'active_users' => $this->safe(fn () => DB::table('users')->where('is_active', 1)->count(), 0),
            'roles' => $this->safe(fn () => DB::table('roles')->count(), 0),
            'logins_today' => $this->safe(fn () => $logins('success'), 0),
            'failed_today' => $this->safe(fn () => $logins('failed'), 0),
            'activities_today' => $this->safe(fn () => DB::table('user_activities')->where('activity_at', '>=', $today)->count(), 0),
            'mail_month' => $this->safe(fn () => DB::table('mail_logs')->where('created_at', '>=', $monthStart)->count(), 0),
            'sms_month' => $this->safe(fn () => DB::table('sms_logs')->where('created_at', '>=', $monthStart)->count(), 0),
        ];
    }

    /**
     * Activities and successful logins per day.
     *
     * @return array{labels: array<int, string>, activities: array<int, int>, logins: array<int, int>}
     */
    public function chart(int $days = 7): array
    {
        $dates = collect(range($days - 1, 0))->map(fn ($i) => now()->subDays($i)->toDateString());
        $since = now()->subDays($days - 1)->startOfDay();
        $perDay = fn (bool $loginsOnly) => $this->safe(fn () => DB::table('user_activities')
            ->selectRaw('DATE(activity_at) as day, COUNT(*) as total')
            ->where('activity_at', '>=', $since)
            ->when($loginsOnly, fn ($q) => $q->where('activity_type', 'login')->where('status', 'success'))
            ->groupBy('day')
            ->pluck('total', 'day'), collect());

        $activities = $perDay(false);
        $logins = $perDay(true);

        return [
            'labels' => $dates->map(fn ($d) => Carbon::parse($d)->format('d M'))->all(),
            'activities' => $dates->map(fn ($d) => (int) ($activities[$d] ?? 0))->all(),
            'logins' => $dates->map(fn ($d) => (int) ($logins[$d] ?? 0))->all(),
        ];
    }

    /**
     * @return Collection<int, object{name: string, total: int}>
     */
    public function roleDistribution(): Collection
    {
        return $this->safe(fn () => DB::table('roles')
            ->leftJoin('role_user', 'roles.id', '=', 'role_user.role_id')
            ->select('roles.name', DB::raw('COUNT(role_user.user_id) as total'))
            ->groupBy('roles.id', 'roles.name')
            ->orderByDesc('total')
            ->get(), collect());
    }

    /**
     * @return Collection<int, UserActivity>
     */
    public function recentActivities(int $limit = 8): Collection
    {
        return $this->safe(fn () => UserActivity::with('user:id,name')->orderByDesc('activity_at')->limit($limit)->get(), collect());
    }

    /**
     * Shortcuts the logged-in user may open.
     *
     * @return array<int, array{route: string, icon: string, label: string}>
     */
    public function quickLinks(): array
    {
        $user = auth()->user();
        $links = [
            ['route' => 'users.create', 'permit' => 'me_user.create', 'icon' => 'fas fa-user-plus', 'label' => __('me::me.dash_add_user')],
            ['route' => 'roles.create', 'permit' => 'me_role.create', 'icon' => 'fas fa-user-shield', 'label' => __('me::me.dash_add_role')],
            ['route' => 'configurations.edit', 'permit' => 'me_setting.configurations', 'icon' => 'fas fa-wrench', 'label' => __('me::me.Configurations')],
            ['route' => 'mail-config.edit', 'permit' => 'me_setting.mail', 'icon' => 'fas fa-at', 'label' => __('me::me.Mail Configuration')],
            ['route' => 'sms-config.edit', 'permit' => 'me_setting.sms', 'icon' => 'fas fa-sms', 'label' => __('me::me.SMS Configuration')],
            ['route' => 'profile.edit', 'permit' => null, 'icon' => 'fas fa-id-badge', 'label' => __('me::me.dash_my_profile')],
        ];

        return array_values(array_map(
            fn ($link) => array_diff_key($link, ['permit' => true]),
            array_filter($links, fn ($link) => Route::has($link['route']) && (! $link['permit'] || $user?->can($link['permit'])))
        ));
    }

    /**
     * @template T
     *
     * @param  callable(): T  $query
     * @param  T  $default
     * @return T
     */
    private function safe(callable $query, mixed $default): mixed
    {
        try {
            return $query();
        } catch (\Throwable) {
            return $default;
        }
    }
}
