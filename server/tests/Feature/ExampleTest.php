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

    public function testPagination()
    {
        $str = "34 10 3";
        $nums = explode(" ", $str);
        $start = $nums[1] * ($nums[2] - 1) + 1;
        $max_page = ceil($nums[0] / $nums[1]);

        if ($nums[2] <= $max_page) {
            for($i = 0; $i < $nums[1]; $i++) {
                $value[] = $start;
                $start = $start + 1;
            }
        } else {
            $start = 'none';
        }

        $this->assertEquals("21 22 23 24 25 26 27 28 29 30", implode(" ", $value));
    }
}
