<?php

if (!defined('ABSPATH')) {
    exit;
}

class BLM_Standings_Frontend
{
    private BLM_Standings_Repository $repository;

    private BLM_Standings_Season $season;

    private BLM_Standings_Renderer $renderer;

    public function __construct(
        BLM_Standings_Repository $repository,
        BLM_Standings_Season $season,
        BLM_Standings_Renderer $renderer
    ) {
        $this->repository = $repository;
        $this->season     = $season;
        $this->renderer   = $renderer;
    }

    public function render(array $atts = []): string
    {
        BLM_Assets::standings();

        $enabled = $this->repository->getEnabledLeagues();

        if (empty($enabled)) {
            return $this->renderer->noStandings(
                __('No leagues configured.', 'basketa')
            );
        }

        $league = reset($enabled);

        if (!$league) {
            return $this->renderer->noStandings(
                __('No leagues configured.', 'basketa')
            );
        }

        $leagueId = (int) $league['id'];

        $season = (int) (
            $league['season']
            ?? date('Y')
        );

        $availableSeasons = $this->season->buildSeasonOptions(
            $league['seasons'],
            $leagueId
        );

        $standings = $this->repository->getStandings(
            $leagueId,
            $season
        );

        if (empty($standings[0])) {
            return $this->renderer->noStandings(
                __('Standings are not available.', 'basketa')
            );
        }

        $view = [

            'league' => $league,

            'enabled' => $enabled,

            'season' => $season,

            'season_label' => $this->season->getSeasonLabel(
                $leagueId,
                $season
            ),

            'available_seasons' => $availableSeasons,

            'stage_name' =>
                $standings[0][0]['group']['name']
                ?? __('Standings', 'basketa'),

            'standings' => $standings,

        ];

        return $this->renderer->render($view);
    }
}