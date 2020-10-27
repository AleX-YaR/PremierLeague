<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGame;
use App\Http\Requests\UpdateGame;
use App\Models\Game;
use App\Models\Team;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class GameController extends Controller
{
    /**
     * Display League tables.
     *
     * @return View
     */
    public function index(): View
    {
        $teamsPredictions = [];
        $teams = Team::getAllSorted();
        if (!count($teams)) {
            throw new BadRequestHttpException('No teams found.');
        }

        $week = $teams[0]->getPlayed();

        $games = Game::getForWeekGrouped($week);

        if ($week >= 4) {
            $teamsPredictions = $this->getTeamsPredictions($teams);
        }

        return view(
            'game.index', [
            'teams'            => $teams,
            'games'            => $games,
            'teamsPredictions' => $teamsPredictions,
            'currentWeek'             => $week,
            ]
        );
    }

    /**
     * Simulate next week game.
     *
     * @param CreateGame $request
     *
     * @return RedirectResponse
     */
    public function nextWeek(CreateGame $request): RedirectResponse
    {
        $this->simulateGame($request->week);
        $this->simulateGame($request->week);

        return redirect('/');
    }

    /**
     * Simulate all games.
     *
     * @return View
     */
    public function playAll(): View
    {
        for($week = Game::getLastWeek() + 1; $week <= 6; $week++) {
            $this->simulateGame($week);
            $this->simulateGame($week);
        }

        $games = Game::getAllSortedGrouped();

        $teams = Team::getAllSorted();
        if (!$teams) {
            throw new BadRequestHttpException('No teams found.');
        }

        $teamsPredictions = $this->getTeamsPredictions($teams);

        return view(
            'game.index', [
            'teams'            => $teams,
            'games'            => $games,
            'teamsPredictions' => $teamsPredictions,
            'currentWeek'      => $week - 1,
            ]
        );
    }

    /**
     * Update week results.
     *
     * @param UpdateGame $request
     *
     * @return RedirectResponse
     */
    public function update(UpdateGame $request): RedirectResponse
    {
        foreach($request->games as $id => $scores){
            Game::updateScore($id, $scores['home_score'], $scores['away_score']);
        }

        return redirect('/');
    }

    /**
     * Edit week results.
     *
     * @param int $week
     *
     * @return View
     */
    public function edit(int $week): View
    {
        $teamsPredictions = [];
        $teams = Team::getAllSorted();
        if (!count($teams)) {
            throw new BadRequestHttpException('No teams found.');
        }

        $games = Game::getForWeekGrouped($week);
        if (!count($games)) {
            throw new BadRequestHttpException('No game found for week ' . $week . '.');
        }

        if ($week >= 4) {
            $teamsPredictions = $this->getTeamsPredictions($teams);
        }

        return view(
            'game.edit', [
                'teams'            => $teams,
                'games'            => $games,
                'teamsPredictions' => $teamsPredictions,
                'currentWeek'      => $week,
            ]
        );
    }

    /**
     * Remove all games from the DB.
     *
     * @return RedirectResponse
     */
    public function reset(): RedirectResponse
    {
        Game::truncate();

        return redirect('/');
    }

    /**
     * Precise teams predictions to be 100% in sum.
     *
     * @param array $teamsPredictions
     *
     * @return array
     */
    public function precisePredictions(array $teamsPredictions): array
    {
        $totalPrediction = 0;
        foreach($teamsPredictions as $teamsPrediction) {
            $totalPrediction += $teamsPrediction['prediction'];
        }

        $predictionDifference = 100 - $totalPrediction;
        if ($predictionDifference > 0) {
            foreach($teamsPredictions as &$teamsPrediction) {
                if ($predictionDifference < 1) {
                    break;
                }
                $teamsPrediction['prediction'] += 1;
                $predictionDifference--;
            }
        }

        return $teamsPredictions;
    }

    /**
     * Get teams predictions.
     *
     * @param Collection $teams
     *
     * @return array
     */
    private function getTeamsPredictions(Collection $teams): array
    {
        $totalWinRate = 0;
        foreach($teams as $team) {
            $totalWinRate += $team->getWinRate();
        }

        $teamsPredictions = [];
        foreach($teams as $team) {
            $prediction = $totalWinRate > 0 ? floor($team->getWinRate() * 100 / $totalWinRate) : 0;
            $teamsPredictions[] = ['name' => $team->name, 'prediction' => $prediction];
        }

        $teamsPredictions = $this->precisePredictions($teamsPredictions);

        return $teamsPredictions;
    }


    /**
     * Simulate game.
     *
     * @param int $week
     */
    private function simulateGame(int $week): void
    {
        $homeTeam = Team::getNotPlayedHomeTeam($week);

        if (!$homeTeam) {
            throw new BadRequestHttpException('No home team for game found.');
        }

        $awayTeam = Team::getNotPlayedAwayTeam($week, $homeTeam->id);

        if (!$awayTeam) {
            throw new BadRequestHttpException('No away team for game found.');
        }

        Game::create($homeTeam->id, $awayTeam->id, $week);
    }
}
