<?php

namespace Mlopez\Supergiros\Tests;

use Mlopez\Supergiros\Supergiros;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class SupergirosTest extends TestCase
{
    /**
     * @test
     * @throws Exception
     */
    public function it_can_test_instance_class(): void
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
            ->willReturn($data);


        $this->assertEquals($data, $stub->call('2022-05-13'));
    }
}
