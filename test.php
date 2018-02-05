<?php

use PHPUnit\Framework\TestCase;

require './StringPartitions.php'; 

class StringPartitionTest extends TestCase
{
     public function testEmpty()
    {
        $test = processString("", 4);
        $this->assertEquals([[""]], $test);

        return $test;
    }

    public function testShort()
    {
        $test = count(partitions(10, 4));
        $this->assertEquals(4, $test);

        return $test;
    }

    public function testLong()
    {
        $test = count(partitions(25, 4));
        $this->assertEquals(476, $test);

        return $test;
    }
}
?>
