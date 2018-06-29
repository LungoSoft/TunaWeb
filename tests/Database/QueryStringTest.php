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
        $query = Query::table('users')
            ->__toString();
        $this->assertEquals('select * from users', $query);

        $query = Query::table('users')
            ->set('name', '=', '12')->
            __toString();
        $this->assertEquals('update users set name = 12', $query);

        $query = Query::table('users')
            ->set('name', '=', '12')
            ->set('username', '=', 'user')
            ->__toString();
        $this->assertEquals('update users set name = 12, username = user', $query);

        $query = Query::table('users')
            ->set('name', '=', '12')
            ->set('username', '=', 'user')
            ->where('name', '<=', 'test')
            ->__toString();
        $this->assertEquals('update users set name = 12, username = user where name <= test', $query);

        $query = Query::table('users')
            ->select('domains.*', 'users.name')
            ->join('domains', 'users.domain_id', '=', 'domains.id')
            ->__toString();
        $this->assertEquals('select domains.*, users.name from users join domains on users.domain_id = domains.id', $query);

        $query = Query::table('users')
            ->select('domains.*')
            ->join('domains', 'users.domain_id', '=', 'domains.id')
            ->where('domains.name', '=', 'name')
            ->__toString();
        $this->assertEquals("select domains.* from users join domains on users.domain_id = domains.id where domains.name = name", $query);

        $query = Query::table('users')
            ->select('domains.*')
            ->join('domains', 'users.domain_id', '=', 'domains.id')
            ->leftJoin('paragraph', 'domains.id', '=', 'paragraph.domain_id')
            ->where('domains.name', '=', 'name')
            ->__toString();
        $this->assertEquals("select domains.* from users join domains on users.domain_id = domains.id left join paragraph on domains.id = paragraph.domain_id where domains.name = name", $query);

        $query = Query::table('users')
            ->select('domains.*')
            ->join('domains', 'users.domain_id', '=', 'domains.id')
            ->leftJoin('paragraph', 'domains.id', '=', 'paragraph.domain_id')
            ->where('domains.name', '=', 'name')
            ->orWhere('domains.name', '=', 'name2')
            ->__toString();
        $this->assertEquals("select domains.* from users join domains on users.domain_id = domains.id left join paragraph on domains.id = paragraph.domain_id where domains.name = name or domains.name = name2", $query);
    }
}
