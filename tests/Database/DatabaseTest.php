<?php

use PHPUnit\Framework\TestCase;
use Tuna\Database\Connect;

class DatabaseTest extends TestCase
{
    public function testDatabaseIsConnected()
    {
        $pdo = Connect::instance();
        $this->assertNotNull($pdo);

        return $pdo;
    }
}
