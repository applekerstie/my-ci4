<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class JWT extends BaseConfig
{
    public string $key = '3sfe@1!d4<8qF-5o92$9s6aG'; // 비밀 키
    public string $issuer = 'http://dev.myapp.com'; // 발급자
    public string $audience = 'http://dev.myapp.com'; // 대상자
    public int $expiration = 3600; // 토큰 만료 시간 (1시간)

    public $issuedAt = null; // JWT 발급 시간
    public $notBefore = null; // JWT 유효 시작 시간

    public function __construct()
    {
        parent::__construct();
        $this->issuedAt = time();
        $this->notBefore = $this->issuedAt;
    }
}