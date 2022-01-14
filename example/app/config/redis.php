<?php

namespace nuke2015\api\config;

class redis
{
    public static function config()
    {
        $config =            [
            'REDIS_HOST' => ENV_REDIS_HOST,
            'REDIS_PORT' => ENV_REDIS_PORT,
            'REDIS_PWD'  => ENV_REDIS_PWD,
        ];
        return $config;
    }
}
