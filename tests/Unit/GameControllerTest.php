<?php

namespace Tests\Unit;

use App\Http\Controllers\GameController;
use PHPUnit\Framework\TestCase;
use Mockery;

class GameControllerTest extends TestCase
{
    /**
     * Test GameController class precisePredictions method.
     *
     * @dataProvider precisePredictionsDataProvider
     */
    public function testPrecisePredictions($teamsPredictions, $expected): void
    {
        $gameControllerStub = Mockery::mock(GameController::class)->makePartial();

        $this->assertEquals($expected, $gameControllerStub->precisePredictions($teamsPredictions));
    }

    /**
     * @return int[][][][]
     */
    public function precisePredictionsDataProvider(): array
    {
        return [
            [
                [
                    ['prediction' => 40],
                    ['prediction' => 29],
                    ['prediction' => 18],
                    ['prediction' => 12],
                ],
                [
                    ['prediction' => 41],
                    ['prediction' => 29],
                    ['prediction' => 18],
                    ['prediction' => 12],
                ],
            ],
            [
                [
                    ['prediction' => 38],
                    ['prediction' => 31],
                    ['prediction' => 20],
                    ['prediction' => 8],
                ],
                [
                    ['prediction' => 39],
                    ['prediction' => 32],
                    ['prediction' => 21],
                    ['prediction' => 8],
                ],
            ],
            [
                [
                    ['prediction' => 42],
                    ['prediction' => 21],
                    ['prediction' => 20],
                    ['prediction' => 13],
                ],
                [
                    ['prediction' => 43],
                    ['prediction' => 22],
                    ['prediction' => 21],
                    ['prediction' => 14],
                ],
            ],
            [
                [
                    ['prediction' => 33],
                    ['prediction' => 32],
                    ['prediction' => 20],
                    ['prediction' => 15],
                ],
                [
                    ['prediction' => 33],
                    ['prediction' => 32],
                    ['prediction' => 20],
                    ['prediction' => 15],
                ],
            ],
        ];
    }
}
