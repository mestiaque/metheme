<?php

namespace ME\Services;

use ME\Models\UserActivity;
use Illuminate\Http\Request;

class ActivityLoggerService
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Log user activity
     */
    public function logActivity(
        ?int $userId,
        string $activityType,
        string $status = 'success',
        ?string $description = null
    ): UserActivity {
        $browser = $this->detectBrowser();
        $os = $this->detectOs();

        $activityData = [
            'user_id' => $userId,
            'activity_type' => $activityType,
            'ip_address' => $this->getClientIp(),
            'browser_name' => $browser['name'],
            'browser_version' => $browser['version'],
            'device_name' => $this->detectDeviceName(),
            'device_type' => $this->getDeviceType(),
            'os_name' => $os['name'],
            'os_version' => $os['version'],
            'user_agent' => $this->request->userAgent(),
            'status' => $status,
            'description' => $description,
            'activity_at' => now(),
        ];

        return UserActivity::create($activityData);
    }

    /**
     * Get client IP address
     */
    protected function getClientIp(): string
    {
        return $this->request->ip() ?? 'Unknown';
    }

    /**
     * Get device type
     */
    protected function getDeviceType(): string
    {
        $userAgent = strtolower($this->request->userAgent() ?? '');

        if (preg_match('/mobile|android|iphone|ipod|blackberry|iemobile|opera mini/', $userAgent)) {
            return 'phone';
        }

        if (preg_match('/tablet|ipad|playbook|silk/', $userAgent)) {
            return 'tablet';
        }

        return 'desktop';
    }

    protected function detectBrowser(): array
    {
        $ua = $this->request->userAgent() ?? '';

        $browsers = [
            'Edg' => 'Edge',
            'OPR' => 'Opera',
            'Chrome' => 'Chrome',
            'Firefox' => 'Firefox',
            'Safari' => 'Safari',
            'MSIE' => 'Internet Explorer',
            'Trident' => 'Internet Explorer',
        ];

        foreach ($browsers as $token => $name) {
            if (strpos($ua, $token) !== false) {
                $version = $this->extractVersion($ua, $token);

                if ($token === 'Trident') {
                    $version = $this->extractVersion($ua, 'rv');
                }

                return [
                    'name' => $name,
                    'version' => $version,
                ];
            }
        }

        return [
            'name' => 'Unknown',
            'version' => null,
        ];
    }

    protected function detectOs(): array
    {
        $ua = $this->request->userAgent() ?? '';

        $systems = [
            'Windows NT 10.0' => 'Windows',
            'Windows NT 6.3' => 'Windows',
            'Windows NT 6.2' => 'Windows',
            'Windows NT 6.1' => 'Windows',
            'Android' => 'Android',
            'iPhone OS' => 'iOS',
            'iPad; CPU OS' => 'iOS',
            'Mac OS X' => 'macOS',
            'Linux' => 'Linux',
        ];

        foreach ($systems as $token => $name) {
            if (strpos($ua, $token) !== false) {
                return [
                    'name' => $name,
                    'version' => $this->extractVersion($ua, $token),
                ];
            }
        }

        return [
            'name' => 'Unknown',
            'version' => null,
        ];
    }

    protected function detectDeviceName(): ?string
    {
        $ua = $this->request->userAgent() ?? '';

        if (strpos($ua, 'iPhone') !== false) {
            return 'iPhone';
        }

        if (strpos($ua, 'iPad') !== false) {
            return 'iPad';
        }

        if (strpos($ua, 'Android') !== false) {
            return 'Android Device';
        }

        return 'Desktop';
    }

    protected function extractVersion(string $userAgent, string $token): ?string
    {
        $pattern = '/'.preg_quote($token, '/').'[\\/\\s\\:_]?([0-9\\._]+)/i';

        if (preg_match($pattern, $userAgent, $matches)) {
            return str_replace('_', '.', $matches[1]);
        }

        return null;
    }
}
