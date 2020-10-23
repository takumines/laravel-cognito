<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $array = [1,2,3];
        $result = array_reduce($array, function ($carry, $item) {
            $lastIndex = array_key_last($carry);
            if (!is_null($lastIndex)) {
                return array_merge($carry, array($carry[$lastIndex] + $item));
            } else {
                return array_merge($carry, array($item));
            }
        }, []);

        $this->assertEquals([1,3,6], $result);
    }
}
