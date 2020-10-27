<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Game extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the home team record associated with the game.
     *
     * @return BelongsTo
     */
    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo('App\Models\Team', 'home_team_id');
    }

    /**
     * Get the away team record associated with the game.
     *
     * @return BelongsTo
     */
    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo('App\Models\Team', 'away_team_id');
    }

    /**
     * Create new game.
     *
     * @param int $homeTeamId
     * @param int $awayTeamId
     * @param int $week
     */
    public static function create(int $homeTeamId, int $awayTeamId, int $week): void
    {
        $game = new self;
        $game->home_team_id = $homeTeamId;
        $game->away_team_id = $awayTeamId;
        $game->home_score = rand(0, 5);
        $game->away_score = rand(0, 5);
        $game->week = $week;
        $game->save();
    }

    /**
     * Update game score by id.
     *
     * @param int $id
     */
    public static function updateScore(int $id, int $home_score, int $away_score): void
    {
        $game = self::find($id);
        $game->home_score = $home_score;
        $game->away_score = $away_score;
        $game->save();
    }

    /**
     * Get games for week grouped by week.
     *
     * @param int $week
     *
     * @return Collection
     */
    public static function getForWeekGrouped(int $week): Collection
    {
        return self::where('week', $week)->get()->groupBy('week');
    }

    /**
     * Get all games sorted and grouped by week.
     *
     * @return Collection
     */
    public static function getAllSortedGrouped(): Collection
    {
        return self::orderBy('week')->get()->groupBy('week');
    }

    /**
     * Get the last week number.
     *
     * @return int
     */
    public static function getLastWeek(): int
    {
        return self::max('week') ?? 0;
    }
}
