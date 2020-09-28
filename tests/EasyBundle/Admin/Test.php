<?php

namespace Tests\AppBundle\Entity;

use App\EasyBundle\Entity\Category;
use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    public function testThatYourComputerWorks()
    {
        $category = new Category();

        $category2 = new Category();

        $this->assertSame($category2->getSlug(), $category->getSlug());
    }
}