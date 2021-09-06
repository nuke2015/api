<?php

namespace nuke2015\api\mongo;

// mongo-web日志库
class LogCenter extends MongoBase
{
    public function __construct($db = 'LogCenter')
    {
        return parent::__construct($db);
    }
}
