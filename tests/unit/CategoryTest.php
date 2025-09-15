<?php

namespace tests\unit;

use app\models\Category;
use Codeception\Test\Unit;

class CategoryTest extends Unit
{
  public function testValidation()
  {
    $category = new Category();
    $category->name = '';

    $this->assertFalse($category->validate(['name']));

    $category->name = 'Alimentação';
    $this->assertTrue($category->validate());
  }
}
