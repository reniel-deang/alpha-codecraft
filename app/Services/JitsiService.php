<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JitsiService
{
    private $appId = 'your_app_id';
    private $appSecret = 'your_app_secret';
    private $domain = 'webapi.codecraftmeet.online';

    public function generateToken($user, $role, $room)
    {
        $payload = [
            "context" => [
                "user" => [
                    "avatar" => asset("storage/users-avatar/{$user->avatar}"),
                    "name" => $user->name,
                    "email" => $user->email,
                    "moderator" => $role === 'Teacher' ? 'true' : 'false'
                ]
            ],
            "room" => $room, 
            "aud" => $this->appId,
            "iss" => $this->appId,
            "sub" => $this->domain,
            "exp" => time() + 3600, // Token expiration (1 hour)
        ];

        // Generate the token using the app secret
        $jwt = JWT::encode($payload, $this->appSecret, 'HS256');

        return $jwt;
    }
}
