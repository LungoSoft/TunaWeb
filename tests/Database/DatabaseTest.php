<?php

use PHPUnit\Framework\TestCase;
use Tuna\Database\Connect;
use Tuna\Database\Query;

class DatabaseTest extends TestCase
{
    public function testSimpleSelectQueryResult()
    {
        $dir = __DIR__.'/../../';
        \Tuna\Kernel\App::create($dir, true);

        $pdo = Connect::instance(true);
        $this->assertNotNull($pdo);

        $reference = $this;
        Query::transaction(function() use ($reference) { 
            $idDomain1 = Query::table('domains')
                ->insert('name', '?')
                ->insert('principal', '?')
                ->get(['pagina.com', true]);

            $idDomain2 = Query::table('domains')
                ->insert('name', '?')
                ->get(['otro.com']);
            
            $idUser1 = Query::table('users')
                ->insert('username', '?')
                ->insert('password', '?')
                ->insert('domain_id', '?')
                ->get(['administrator', '1234', $idDomain1]);

            $idUser2 = Query::table('users')
                ->insert('username', '?')
                ->insert('password', '?')
                ->insert('domain_id', '?')
                ->get(['otro', '54321', $idDomain2]);

            $idParagraph1 = Query::table('paragraphs')
                ->insert('name', '?')
                ->insert('content', '?')
                ->insert('domain_id', '?')
                ->get(['name1', 'con1', $idDomain1]);

            $idParagraph2 = Query::table('paragraphs')
                ->insert('name', '?')
                ->insert('content', '?')
                ->insert('domain_id', '?')
                ->get(['name2', 'con2', $idDomain2]);
                
            $query = Query::table('domains')
                ->get();
            $reference->assertEquals("pagina.com", $query[0]->name);
            $reference->assertEquals(2, count($query));

            $query = Query::table('users')
                ->get();
            $reference->assertEquals("administrator", $query[0]->username);
            $reference->assertEquals(2, count($query));

            $query = Query::table('paragraphs')
                ->get();
            $reference->assertEquals("name1", $query[0]->name);
            $reference->assertEquals(2, count($query));

            return false;
        });
    }
}
