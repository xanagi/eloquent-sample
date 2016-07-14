<?php
namespace Sample;

use Illuminate\Database\Eloquent\Model;

class Item extends Model {
  // Mass Assignment を許可するプロパティ.
  protected $fillable = array('name');
}
