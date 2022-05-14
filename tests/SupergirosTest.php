<?php

namespace Mlopez\Supergiros\Tests;

use Mlopez\Supergiros\Supergiros;
use PHPUnit\Framework\TestCase;

class SupergirosTest extends TestCase
{

    /**
     * @test
     */
    public function it_can_test_instance_class()
    {
        $data = [
            [
                'loteria' => 'test',
                'fecha' => 'test',
                'resultados' => 'test',
                'serie' => 'test'
            ]
        ];

        $stub = $this->createMock(Supergiros::class);
        $stub->method('call')
            ->withAnyParameters()
            ->willReturn(json_encode($data));


        $this->assertEquals(json_encode($data), $stub->call(2022 - 05 - 13));

    }

}
