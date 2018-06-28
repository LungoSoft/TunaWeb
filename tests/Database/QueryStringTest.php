<?php

use PHPUnit\Framework\TestCase;
use Tuna\Database\Connect;
use Tuna\Database\Query;

class QueryStringTest extends TestCase
{
    public function testDatabaseIsConnected()
    {
        $pdo = Connect::instance();
        $this->assertNotNull($pdo);

        return $pdo;
    }

    /**
     * @depends testDatabaseIsConnected
     */
    public function testSimpleSelectQuery($pdo)
    {
        $query = Query::instance()->table('users')->select('a, b')->query();
        $this->assertEquals('select a, b from users', $query);

        $query = Query::instance()
            ->table('users')
            ->select('domains.*')
            ->join('domains', 'users.domain_id', '=', 'domains.id')
            ->query();
        $this->assertEquals('select domains.* from users join domains on users.domain_id = domains.id', $query);

        $query = Query::instance()
            ->table('users')
            ->select('domains.*')
            ->join('domains', 'users.domain_id', '=', 'domains.id')
            ->where('domains.name', '=', 'name')
            ->query();
        $this->assertEquals("select domains.* from users join domains on users.domain_id = domains.id where domains.name = name", $query);

        $query = Query::instance()
            ->table('users')
            ->select('domains.*')
            ->join('domains', 'users.domain_id', '=', 'domains.id')
            ->join('paragraph', 'domains.id', '=', 'paragraph.domain_id')
            ->where('domains.name', '=', 'name')
            ->query();
        $this->assertEquals("select domains.* from users 
            join domains on users.domain_id = domains.id 
            join domains on domains.id = paragraph.domain_id 
            where domains.name = name", $query);
    }
}
