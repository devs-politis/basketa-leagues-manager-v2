<?php

if (!defined('ABSPATH')) {
    exit;
}

class BLM_Standings_Ajax
{
    private BLM_Standings_Repository $repository;

    private BLM_Standings_Renderer $renderer;

    public function __construct(
        BLM_Standings_Repository $repository,
        BLM_Standings_Renderer $renderer
    ) {
        $this->repository = $repository;
        $this->renderer   = $renderer;

        $this->register();
    }

    /**
     * Register AJAX actions.
     */
    public function register(): void
    {
        add_action(
            'wp_ajax_blm_load_standings',
            [$this, 'handle']
        );

        add_action(
            'wp_ajax_nopriv_blm_load_standings',
            [$this, 'handle']
        );
    }

    /**
     * Handle AJAX request.
     */
    public function handle(): void
    {
        check_ajax_referer(
            'blm_standings',
            'nonce'
        );

        $leagueId = absint($_POST['league'] ?? 0);
        $season   = absint($_POST['season'] ?? 0);

        if (!$leagueId || !$season) {
            wp_send_json_error([
                'message' => __('Invalid request.', 'basketa'),
            ]);
        }

        $standings = $this->repository->getStandings(
            $leagueId,
            $season
        );

        if (empty($standings[0])) {
            wp_send_json_error([
                'message' => __('Standings are not available.', 'basketa'),
            ]);
        }

        wp_send_json_success([
            'rows' => $this->renderer->renderRows(
                $standings,
                $leagueId
            ),
        ]);
    }
}