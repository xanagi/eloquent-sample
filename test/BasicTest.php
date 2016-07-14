<?php
namespace Sample;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\DatabaseManager as DB;

/**
 * データベースの使用方法サンプル.
 * https://laravel.com/docs/5.2/database
 */
class BasicTest extends \PHPUnit_Framework_TestCase
{
    static function setUpBeforeClass()
    {
        $capsule = new Capsule;
        $capsule->addConnection([
            'driver'   => 'mysql',
            //'host'     => 'mysql',
            'read' => [
                'host' => 'mysql'
            ],
            'write' => [
                'host' => 'mysql'
            ],
            'database' => 'global',
            'username' => 'root',
            'password' => 'pass',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'   => '',
        ]);
        $capsule->addConnection([
            'driver'   => 'mysql',
            'host'     => 'mysql',
            'database' => 'shard1',
            'username' => 'root',
            'password' => 'pass',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'   => '',
        ], 'shard1');
        $capsule->setAsGlobal();
    }

    public function setUp()
    {
        Capsule::statement('truncate items');
        for ($i = 1; $i <= 10; $i++) {
            Capsule::insert('insert into items (name, created_at, updated_at) values (?, now(), now())', ["item${i}"]);
        }
    }

    public function testSelect()
    {
        $items = Capsule::select('select * from items where id > ?', [5]);
        $this->assertEquals(5, count($items));
    }

    public function testInsert()
    {
        Capsule::insert('insert into items (name, created_at, updated_at) values (?, now(), now())', ["test-item"]);
        $id = Capsule::connection()->getPdo()->lastInsertId('items');
        $this->assertEquals(11, $id);

        $items = Capsule::select('select * from items where id = ?', [11]);
        $this->assertEquals(1, count($items));
    }

    public function testUpdate()
    {
        $affected = Capsule::update('update items set name = ? where id =?', ['item5 renamed.', 5]);
        $this->assertEquals(1, $affected);
        $item = Capsule::select('select * from items where id = ?', [5])[0];
        $this->assertEquals('item5 renamed.', $item->name);
    }

    public function testDelete()
    {
        $deleted = Capsule::delete('delete from items where id = ?', [5]);
        $items = Capsule::select('select * from items where id = ?', [5]);
        $this->assertEquals(0, count($items));
    }

    public function testTransaction()
    {
        // TODO
    }

    public function testConnection()
    {
        $pdo = Capsule::connection()->getPdo();
        $this->assertEquals('global', $pdo->query('select database()')->fetchColumn());

        $pdo = Capsule::connection('shard1')->getPdo();
        $this->assertEquals('shard1', $pdo->query('select database()')->fetchColumn());
    }
}
