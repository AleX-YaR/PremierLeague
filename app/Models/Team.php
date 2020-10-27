<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Collection;

class Team extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the home games for the team.
     *
     * @return HasMany
     */
    public function homeGames(): HasMany
    {
        return $this->hasMany('App\Models\Game', 'home_team_id');
    }

    /**
     * Get the away games for the team.
     *
     * @return HasMany
     */
    public function awayGames(): HasMany
    {
        return $this->hasMany('App\Models\Game', 'away_team_id');
    }

    /**
     * Scope a query to only include not played teams on defined week.
     *
     * @param Builder $query
     * @param int     $week
     *
     * @return Builder
     */
    public function scopeNotPlayedWeek(Builder $query, int $week): Builder
    {
        return $query->whereDoesntHave(
            'homeGames', function (Builder $query) use ($week) {
                $query->where('week', $week);
            }
        )->whereDoesntHave(
            'awayGames', function (Builder $query) use ($week) {
                    $query->where('week', $week);
            }
        );
    }

    /**
     * Get not played yet home team.
     *
     * @param int $week
     *
     * @return Team|null
     */
    public static function getNotPlayedHomeTeam(int $week)
    {
        $teams = self::notPlayedWeek($week)->has('homeGames', '<', 3)->inRandomOrder()->get();

        if (count($teams) === 2 && $teams[0]->homeGames()->where('away_team_id', $teams[1]->id)->exists()) {
            return $teams[1];
        } else {
            return $teams[0] ?? null;
        }
    }

    /**
     * Get not played yet away team.
     *
     * @param int      $week
     * @param int|null $homeTeamId
     *
     * @return Team
     */
    public static function getNotPlayedAwayTeam(int $week, int $homeTeamId = null): Team
    {
        $query = self::notPlayedWeek($week)->has('awayGames', '<', 3);

        if ($homeTeamId !== null) {
            $query->where('id', '!=', $homeTeamId)
                ->whereDoesntHave(
                    'awayGames', function (Builder $query) use ($week, $homeTeamId) {
                        $query->where('home_team_id', $homeTeamId);
                    }
                );
        }

        return $query->inRandomOrder()->first();
    }

    /**
     * Get all teams sorted by scores.
     *
     * @return Collection
     */
    public static function getAllSorted(): Collection
    {
        return Team::all()->sort(
            function ($team1, $team2) {
                if ($team1->getPoints() == $team2->getPoints()) {
                    if ($team1->getGoalDifference() == $team2->getGoalDifference()) {
                        return 0;
                    }

                    return ($team1->getGoalDifference() > $team2->getGoalDifference()) ? -1 : 1;
                }

                return ($team1->getPoints() > $team2->getPoints()) ? -1 : 1;
            }
        );
    }

    /**
     * Get team points.
     *
     * @return int
     */
    public function getPoints(): int
    {
        return $this->getWon() * 3 + $this->getDrawn();
    }

    /**
     * Get team played games number.
     *
     * @return int
     */
    public function getPlayed(): int
    {
        return $this->homeGames()->count() + $this->awayGames()->count();
    }

    /**
     * Get team won games number.
     *
     * @return int
     */
    public function getWon(): int
    {
        $homeGameWon = $this->homeGames()->whereColumn('home_score', '>', 'away_score')->count();
        $awayGameWon = $this->awayGames()->whereColumn('away_score', '>', 'home_score')->count();

        return $homeGameWon + $awayGameWon;
    }

    /**
     * Get team drawn games number.
     *
     * @return int
     */
    public function getDrawn(): int
    {
        $homeGameDrawn = $this->homeGames()->whereColumn('home_score', '=', 'away_score')->count();
        $awayGameDrawn = $this->awayGames()->whereColumn('away_score', '=', 'home_score')->count();

        return $homeGameDrawn + $awayGameDrawn;
    }

    /**
     * Get team lost games number.
     *
     * @return int
     */
    public function getLost(): int
    {
        $homeGameLost = $this->homeGames()->whereColumn('home_score', '<', 'away_score')->count();
        $awayGameLost = $this->awayGames()->whereColumn('away_score', '<', 'home_score')->count();

        return $homeGameLost + $awayGameLost;
    }

    /**
     * Get team goal difference number.
     *
     * @return int
     */
    public function getGoalDifference(): int
    {
        $homeGameScored = $this->homeGames()->sum("home_score");
        $homeGameConceded = $this->homeGames()->sum("away_score");
        $awayGameScored = $this->awayGames()->sum("away_score");
        $awayGameConceded = $this->awayGames()->sum("home_score");

        return $homeGameScored + $awayGameScored - $homeGameConceded - $awayGameConceded;
    }

    /**
     * Get team win rate.
     *
     * @return float|int
     */
    public function getWinRate()
    {
        return $this->getPlayed() > 0 ? ($this->getPoints() / $this->getPlayed()) : 0;
    }
}
