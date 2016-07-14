<?php
namespace Sample;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\DatabaseManager as DB;

/**
 * Query Builder の使用方法サンプル.
 * https://laravel.com/docs/5.2/queries
 */
class QueryBuilderTest extends \PHPUnit_Framework_TestCase
{
    static function setUpBeforeClass()
    {
        $capsule = new Capsule;
        $capsule->addConnection([
            'driver'   => 'mysql',
            'host'     => 'mysql',
            'database' => 'global',
            'username' => 'root',
            'password' => 'pass',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'   => '',
        ]);
        $capsule->setAsGlobal();
    }

    public function setUp()
    {
        Capsule::table('items')->truncate();
        for ($i = 1; $i <= 10; $i++) {
            Capsule::table('items')->insert(
                ['name' => "item${i}", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]
            );
        }
    }

    public function testGet()
    {
        $items = Capsule::table('items')->get();
        $this->assertEquals(10, count($items));

        $item = Capsule::table('items')->where('name', 'item5')->first();
        $this->assertEquals(5, $item->id);
    }

    public function testInsert()
    {
        $id = Capsule::table('items')->insertGetId(
            ['name' => 'test-item', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]
        );
        $this->assertEquals(11, $id);

        $item = Capsule::table('items')->where('id', 11)->first();
        $this->assertNotNull($item);
    }

    public function testUpdate()
    {
        $affected = Capsule::table('items')
            ->where('id', 5)
            ->update(['name' => 'item5 renamed.']);
        $this->assertEquals(1, $affected);
        $item = Capsule::table('items')->where('id', 5)->first();
        $this->assertEquals('item5 renamed.', $item->name);
    }

    public function testDelete()
    {
        $deleted = Capsule::delete('delete from items where id = ?', [5]);
        $item = Capsule::table('items')->where('id', 11)->first();
        $this->assertNull($item);
    }
}
