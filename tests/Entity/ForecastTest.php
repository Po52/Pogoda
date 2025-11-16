<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Forecast;
use PHPUnit\Framework\Attributes\DataProvider;

class ForecastTest extends TestCase
{
    public static function dataGetFahrenheit():array
    {
        return [
            ['0', '32'],
            ['-100', '-148'],
            ['100', '212'],
            ['31.5', '88.7'],
            ['12', '53.6'],
            ['18', '64.4'],
            ['-14', '6.80'],
            ['-23', '-9.40'],
            ['0.5', '32.9'],
            ['-0.5', '31.1'],

        ];
    
    }


    #[DataProvider('dataGetFahrenheit')]
    public function testGetFahrenheit(string $celsius, string $expectedFahrenheit): void
    {
        $t1 = new Forecast();
        $t1->setTemperatureInCelsius($celsius);
        $this->assertEquals(round($expectedFahrenheit,2), round($t1->getFahrenheit(),2));
    }
}
