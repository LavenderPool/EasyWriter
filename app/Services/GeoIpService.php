<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeoIpService
{
    /**
     * Resolve country for an IP address.
     *
     * @return array{code: ?string, name: ?string}
     */
    public function lookup(string $ip): array
    {
        if ($this->isPrivateIp($ip)) {
            return ['code' => null, 'name' => 'Local'];
        }

        return Cache::remember("geoip:{$ip}", now()->addDays(7), function () use ($ip) {
            try {
                $response = Http::timeout(3)
                    ->acceptJson()
                    ->get("http://ip-api.com/json/{$ip}", [
                        'fields' => 'status,country,countryCode',
                    ]);

                if ($response->successful() && $response->json('status') === 'success') {
                    return [
                        'code' => $response->json('countryCode'),
                        'name' => $response->json('country'),
                    ];
                }
            } catch (\Throwable $e) {
                Log::warning('GeoIP lookup failed', [
                    'ip' => $ip,
                    'error' => $e->getMessage(),
                ]);
            }

            return ['code' => null, 'name' => 'Unknown'];
        });
    }

    private function isPrivateIp(string $ip): bool
    {
        return ! filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        );
    }
}
