<?php

namespace Tests;

use Jeidison\NativeQuery\NativeQuery\NativeQuery;
use PHPUnit\Framework\TestCase;

class NativeQueryTest extends TestCase
{

    public function testRun()
    {
        $response = ModelTest::nativeQuery('findTab1')
            ->param('par1', 'value1')
            ->param('par2', 'value2')
            ->exec();

        $this->assertNotNull($response);
    }

    public function testRunWithParamsAsArray()
    {
        $response = ModelTest::nativeQuery('findTab1')
            ->param(['par1' => 'value1'])
            ->exec();

        $this->assertNotNull($response);
    }

    public function testDebug()
    {
        $response = ModelTest::nativeQuery('findTab1')
            ->param('par1', 'value1')
            ->param('par2', 'value2')
            ->debug();

        $this->assertNotNull($response);
    }

    public function testRunWithoutModel()
    {
        $response = NativeQuery::query('findTab1')
            ->queryFile('/path/file-with-queries')
            ->param('par1', 'value1')
            ->exec();

        $this->assertNotNull($response);
    }

}
