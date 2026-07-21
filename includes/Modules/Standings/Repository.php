<?php

if (!defined('ABSPATH')) {
    exit;
}

class BLM_Standings_Repository
{
    /**
     * Cached leagues for the current request.
     *
     * @var array|null
     */
    private ?array $leagues = null;

    /**
     * Returns all standings settings.
     */
    public function getSettings(): array
    {
        return get_option(
            'blm_standings_leagues',
            []
        );
    }

    /**
     * Returns all leagues from the API.
     * Cached in memory for the current request.
     */
    public function getLeagues(): array
    {
        if ($this->leagues !== null) {
            return $this->leagues;
        }

        $this->leagues = BLM_API::get_leagues();

        return $this->leagues;
    }

    /**
     * Returns a single league.
     */
    public function getLeague(int $leagueId): ?array
    {
        foreach ($this->getLeagues() as $league) {

            if ((int) $league['id'] === $leagueId) {
                return $league;
            }
        }

        return null;
    }

    /**
     * Checks if a league exists.
     */
    public function leagueExists(int $leagueId): bool
    {
        return $this->getLeague($leagueId) !== null;
    }

    /**
     * Returns league name.
     */
    public function getLeagueName(int $leagueId): string
    {
        $league = $this->getLeague($leagueId);

        return $league['name'] ?? '';
    }

    /**
     * Returns league logo.
     */
    public function getLeagueLogo(int $leagueId): string
    {
        $league = $this->getLeague($leagueId);

        return $league['logo'] ?? '';
    }

    /**
     * Returns all available seasons for a league.
     */
    public function getLeagueSeasons(int $leagueId): array
    {
        $league = $this->getLeague($leagueId);

        if (!$league) {
            return [];
        }

        return $league['seasons'] ?? [];
    }

    /**
     * Returns standings.
     */
    public function getStandings(
        int $leagueId,
        int $season
    ): array {

        return BLM_API::get_standings(
            $leagueId,
            $season
        );
    }

    /**
     * Returns enabled leagues sorted by admin order.
     */
    public function getEnabledLeagues(): array
    {
        $saved = $this->getSettings();

        if (empty($saved)) {
            return [];
        }

        $enabled = array_filter(
            $saved,
            static function ($league) {
                return !empty($league['enabled']);
            }
        );

        uasort(
            $enabled,
            static function ($a, $b) {

                return
                    ($a['sort'] ?? 999)
                    <=>
                    ($b['sort'] ?? 999);

            }
        );

        return $enabled;
    }

    /**
     * Returns a league setting from the admin configuration.
     */
    public function getLeagueSettings(int $leagueId): array
    {
        $settings = $this->getSettings();

        return $settings[$leagueId] ?? [];
    }

    /**
     * Returns whether a league is enabled.
     */
    public function isLeagueEnabled(int $leagueId): bool
    {
        $settings = $this->getLeagueSettings($leagueId);

        return !empty($settings['enabled']);
    }
}