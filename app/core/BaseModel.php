<?php

require_once __DIR__ . '/../config/config.php';

class BaseModel
{
    protected static function db(): PDO
    {
        return getDB();
    }
}
