<?php

if (!defined('ABSPATH')) {
    exit;
}

class BLM_API {

    private const BASE_URL = 'https://v1.basketball.api-sports.io';

    public function __construct() {

        add_action(
            'admin_init',
            [$this, 'register_settings']
        );
    }

    public function register_settings() {

        register_setting(
            'blm_settings_group',
            'blm_api_key'
        );

        register_setting(
            'blm_settings_group',
            'blm_ticker_leagues'
        );

        register_setting(
            'blm_settings_group',
            'blm_standings_leagues'
        );
    }

    public static function get_api_key(): string
    {
        return get_option(
            'blm_api_key',
            ''
        );
    }

    private static function request(
        string $endpoint,
        array $params = []
    ): array {

        $api_key = self::get_api_key();

        if (empty($api_key)) {
            return [];
        }

        $url =
            self::BASE_URL .
            $endpoint;

        if (!empty($params)) {

            $url .= '?' .
                http_build_query(
                    $params
                );
        }

        /*
         * Daily Counter Reset
         */

        $today =
            current_time('Y-m-d');

        if (
            get_option(
                'blm_api_day'
            ) !== $today
        ) {

            update_option(
                'blm_api_day',
                $today
            );

            update_option(
                'blm_api_calls_today',
                0
            );
        }

        /*
         * API Call Counter
         */

        $count = (int) get_option(
            'blm_api_calls_today',
            0
        );

        update_option(
            'blm_api_calls_today',
            $count + 1
        );

		update_option(
            'blm_last_endpoint',
            $endpoint
        );
		
		$response = wp_remote_get(
		    $url,
		    [
		        'headers' => [
		            'x-apisports-key'
		                => $api_key
		        ],
		        'timeout' => 10
		    ]
		);

        

        if (
            is_wp_error(
                $response
            )
        ) {

            update_option(
                'blm_api_status',
                'error'
            );

            update_option(
                'blm_api_last_error',
                $response->get_error_message()
            );

            return [];
        }

        update_option(
            'blm_last_api_call',
            current_time('mysql')
        );

        $body = json_decode(
            wp_remote_retrieve_body(
                $response
            ),
            true
        );

        if (
            !empty(
                $body['errors']
            )
        ) {

            update_option(
                'blm_api_status',
                'error'
            );

            update_option(
                'blm_api_last_error',
                print_r(
                    $body['errors'],
                    true
                )
            );

            return [];
        }

        update_option(
            'blm_api_status',
            'ok'
        );

        update_option(
            'blm_api_last_error',
            ''
        );

        return
            $body['response']
            ?? [];
    }

    public static function get_leagues(): array
    {
        return BLM_Cache::remember(
            'blm_leagues',
            DAY_IN_SECONDS,
            function () {

                return self::request(
                    '/leagues'
                );
            }
        );
    }

    public static function get_games(
        ?string $date = null
    ): array {

        $date = $date ?: current_time('Y-m-d');

        return BLM_Cache::remember(
            'blm_games_' . $date,
            5 * MINUTE_IN_SECONDS,
            function () use ($date) {

                return self::request(
                    '/games',
                    [
                        'date' => $date
                    ]
                );

            }
        );
    }

    public static function get_standings(
        int $league_id,
        int $season
    ): array
    {

        return BLM_Cache::remember(
            "blm_standings_{$league_id}_{$season}",
            15 * MINUTE_IN_SECONDS,
            function () use (
                $league_id,
                $season
            ) {

                return self::request(
                    '/standings',
                    [
                        'league' => $league_id,
                        'season' => $season
                    ]
                );

            }
        );
    }
}