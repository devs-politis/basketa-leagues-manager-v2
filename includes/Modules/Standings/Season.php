<?php

if (!defined('ABSPATH')) {
    exit;
}

class BLM_Standings_Season
{
    private BLM_Standings_Repository $repository;

    /**
     * Leagues that use a single-year season.
     *
     * @var int[]
     */
    private array $singleYearLeagues = [
        120,
    ];

    public function __construct()
    {
        $this->repository = new BLM_Standings_Repository();
    }

    /**
     * Returns all seasons for a league.
     */
    public function getSeasons(int $leagueId): array
    {
        return array_slice(
            $this->repository->getLeagueSeasons($leagueId),
            0,
            5
        );
    }

    /**
     * Returns only seasons that contain standings.
     */
    public function getValidSeasons(int $leagueId): array
    {
        $valid = [];

        foreach ($this->getSeasons($leagueId) as $season) {

            $year = (int) ($season['season'] ?? 0);

            if (!$year) {
                continue;
            }

            $standings = $this->repository->getStandings(
                $leagueId,
                $year
            );

            if (!empty($standings[0])) {
                $valid[] = $season;
            }

            if (count($valid) === 5) {
                break;
            }
        }

        return $valid;
    }

    /**
     * Returns the newest available season.
     */
    public function getDefaultSeason(int $leagueId): ?int
    {
        $valid = $this->getValidSeasons($leagueId);

        if (empty($valid)) {
            return null;
        }

        return (int) $valid[0]['season'];
    }

    /**
     * Returns the formatted season label.
     */
    public function getSeasonLabel(
        int $leagueId,
        int $season
    ): string {

        if ($this->isSingleYearLeague($leagueId)) {
            return (string) $season;
        }

        return sprintf(
            '%d-%d',
            $season,
            $season + 1
        );
    }

    /**
     * Checks whether the league uses a single-year season.
     */
    public function isSingleYearLeague(int $leagueId): bool
    {
        return in_array(
            $leagueId,
            $this->singleYearLeagues,
            true
        );
    }
}