<?php

namespace App\Http;

use Illuminate\Http\Request;
use Illuminate\Support\Env;

class HttpHelper
{
    public static function resolveRequestFingerprint(Request $request): string
    {
        return sprintf("request:%s:%s", self::requestType($request), self::getRequestUriHash($request));
    }

    public static function hasError($response): bool
    {
        return isset($response->original['error']);
    }

    public static function requestType(Request $request): string
    {
        $requestType = $request->segments()[1];
        if (!\in_array($request->segments()[0], ['v1', 'v2', 'v3', 'v4'])) {
            $requestType = $request->segments()[0];
        }

        return $requestType;
    }

    public static function requestCacheExpiry(string $requestType): int
    {
        $requestType = strtoupper($requestType);
        return (int) (Env::get("CACHE_{$requestType}_EXPIRE") ?? Env::get('CACHE_DEFAULT_EXPIRE'));
    }

    public static function requestAPIVersion(Request $request) : int
    {
        return (int) str_replace('v', '', $request->segment(1));
    }

    public static function serializeEmptyObjects(string $requestType, array $data): array
    {
        if (!($requestType === 'anime' || $requestType === 'manga')) {
            return $data;
        }

        return self::serializeEmptyObjectsControllerLevel($data);
    }

    public static function serializeEmptyObjectsControllerLevel(array $data): array
    {
        if (isset($data['related']) && \count($data['related']) === 0) {
            $data['related'] = new \stdClass();
        }

        if (isset($data['related'])) {
            $related = $data['related'];
            $data['related'] = [];

            foreach ($related as $relation => $items) {
                $data['related'][] = [
                    'relation' => $relation,
                    'entry' => $items
                ];
            }
        }

        return $data;
    }

    public static function getRouteName(Request $request) : string
    {
        $route = explode('\\', $request->route()[1]['uses'] ?? 'App\Undefined');

        return end($route);
    }

    public static function getRequestUriHash(Request $request) : string
    {
        return sha1($request->getRequestUri());
    }
}
