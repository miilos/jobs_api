<?php

namespace Milos\JobsApi\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHandler
{
    public static function sign(object $payloadData): string
    {
        $issuedAt = new \DateTime();
        $payload = [
            'iat' => $issuedAt->getTimestamp(),
            'data' => $payloadData,
        ];

        return JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');
    }

    public static function setCookie(string $token): void
    {
        setCookie('jwt', $token, [
            'expires' => time() + (int) $_ENV['JWT_EXPIRES_IN'],
            'path' => '/',
            'httponly' => true,
            'secure' => true,
            'samesite' => 'Strict',
        ]);
    }

    public static function deleteCookie(): void
    {
        if (isset($_COOKIE['jwt'])) {
            unset($_COOKIE['jwt']);
            setcookie('jwt', '', time() - 3600, '/');
        }
    }

    public static function getTokenFromCookie(): string
    {
        return $_COOKIE['jwt'] ?? '';
    }

    public static function decode(string $jwt): object
    {
        $decoded = JWT::decode($jwt, new Key($_ENV['JWT_SECRET'], 'HS256'));
        return $decoded->data;
    }
}