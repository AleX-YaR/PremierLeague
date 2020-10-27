<?php

namespace Tests\Unit;

use App\Models\Team;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PHPUnit\Framework\TestCase;
use Mockery;

class TeamTest extends TestCase
{
    /**
     * Test Team class getPoints method.
     *
     * @dataProvider getPointsDataProvider
     */
    public function testGetPoints($getWonResult, $getDrawnResult, $expected): void
    {
        $teamStub = Mockery::mock(Team::class)->makePartial();
        $teamStub->shouldReceive('getWon')->andReturn($getWonResult);
        $teamStub->shouldReceive('getDrawn')->andReturn($getDrawnResult);

        $this->assertEquals($expected, $teamStub->getPoints());
    }

    /**
     * @return int[][]
     */
    public function getPointsDataProvider(): array
    {
        return [
            [0, 0, 0],
            [0, 1, 1],
            [1, 0, 3],
            [1, 1, 4],
            [2, 1, 7],
            [1, 3, 6],
            [9, 8, 35],
        ];
    }

    /**
     * Test Team class getPlayed method.
     *
     * @dataProvider getPlayedDataProvider
     */
    public function testGetPlayed($homeGamesCountResult, $awayGamesCountResult, $expected): void
    {
        $hasManyHomeStub = Mockery::mock(HasMany::class);
        $hasManyHomeStub->shouldReceive('count')->andReturn($homeGamesCountResult);

        $hasManyAwayStub = Mockery::mock(HasMany::class);
        $hasManyAwayStub->shouldReceive('count')->andReturn($awayGamesCountResult);

        $teamStub = Mockery::mock(Team::class)->makePartial();
        $teamStub->shouldReceive('homeGames')->andReturn($hasManyHomeStub);
        $teamStub->shouldReceive('awayGames')->andReturn($hasManyAwayStub);

        $this->assertEquals($expected, $teamStub->getPlayed());
    }

    /**
     * @return int[][]
     */
    public function getPlayedDataProvider(): array
    {
        return [
            [0, 0, 0],
            [0, 1, 1],
            [1, 0, 1],
            [1, 1, 2],
            [3, 1, 4],
            [7, 8, 15],
        ];
    }

    /**
     * Test Team class getWinRate method.
     *
     * @dataProvider getWinRateDataProvider
     */
    public function testGetWinRate($getPlayedResult, $getPointsResult, $expected): void
    {
        $teamStub = Mockery::mock(Team::class)->makePartial();
        $teamStub->shouldReceive('getPlayed')->andReturn($getPlayedResult);
        $teamStub->shouldReceive('getPoints')->andReturn($getPointsResult);

        $this->assertEquals($expected, $teamStub->getWinRate());
    }

    /**
     * @return int[][]
     */
    public function getWinRateDataProvider(): array
    {
        return [
            [0, 0, 0],
            [0, 1, 0],
            [1, 0, 0],
            [1, 1, 1],
            [2, 1, 0.5],
            [1, 3, 3],
            [6, 9, 1.5],
        ];
    }
}
