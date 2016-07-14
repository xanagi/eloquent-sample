<?php
namespace Sample;

use Illuminate\Database\Capsule\Manager as Capsule;
use Sample\Item;

/**
 * Model の使用方法サンプル.
 * https://laravel.com/docs/5.2/database
 */
class ItemTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
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
        $capsule->bootEloquent();

        Item::truncate();
        for ($i = 1; $i <= 10; $i++) {
            Item::create(['name' => "item${i}"]);
        }
    }

    /**
     * 全件取得
     */
    public function testAll()
    {
        $items = Item::all();
        $this->assertEquals(10, count($items));
    }

    /**
     * IDで1件取得
     */
    public function testFind()
    {
        $item = Item::find(5);
        $this->assertEquals('item5', $item->name);
    }

    /**
     * 条件指定取得
     */
    public function testWhere()
    {
        $items = Item::where('id', '>', 3)->take(3)->orderBy('id')->get();
        $this->assertEquals(3, count($items));
        $this->assertEquals('item4', $items[0]->name);
        $this->assertEquals('item5', $items[1]->name);
        $this->assertEquals('item6', $items[2]->name);

        $item = Item::where('name', 'item7')->first();
        $this->assertEquals(7, $item->id);
    }

    /**
     * findOrFail
     * @expectedException   Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function testFindOrFail()
    {
        $item = Item::findOrFail(5);
        $this->assertEquals('item5', $item->name);

        $item = Item::findOrFail(15);
    }



}
