<?php

namespace Milos\JobsApi\Services;

use Cassandra\DateTime;
use Firebase\JWT\JWT;

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
}