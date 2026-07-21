<?php

if (!defined('ABSPATH')) {
    exit;
}

class BLM_Standings_Renderer
{
    /**
     * Render empty state.
     */
    public function noStandings(string $message): string
    {
        return sprintf(
            '<div class="blm-no-standings"><p>%s</p></div>',
            esc_html($message)
        );
    }

    /**
     * Render standings table rows.
     */
    public function renderRows(array $standings, int $leagueId): string
    {
        if (empty($standings[0])) {
            return '';
        }

        ob_start();

        foreach ($standings[0] as $team) {

            $position = (int) ($team['position'] ?? 0);

            $played = $team['games']['played'] ?? 0;

            $wins = $team['games']['win']['total'] ?? 0;

            $losses = $team['games']['lose']['total'] ?? 0;

            $winPercentage = $team['games']['win']['percentage'] ?? 0;

            $form = $team['form'] ?? '-';

            $rowClass = '';

            if ($leagueId === 120) {

                if ($position === 7) {
                    $rowClass = 'blm-playin-start';
                }

                if ($position === 11) {
                    $rowClass = 'blm-eliminated-start';
                }
            }

            ?>

            <tr class="<?php echo esc_attr($rowClass); ?>">

                <td><?php echo esc_html($position); ?></td>

                <td class="club">

                    <div class="blm-team">

                        <img
                            src="<?php echo esc_url($team['team']['logo'] ?? ''); ?>"
                            alt=""
                        >

                        <span>
                            <?php echo esc_html($team['team']['name'] ?? ''); ?>
                        </span>

                    </div>

                </td>

                <td><?php echo esc_html($played); ?></td>

                <td><?php echo esc_html($wins); ?></td>

                <td><?php echo esc_html($losses); ?></td>

                <td><?php echo esc_html($winPercentage); ?></td>

                <td><?php echo esc_html($form); ?></td>

            </tr>

            <?php
        }

        return ob_get_clean();
    }

    /**
     * Render standings.
     */
    public function render(array $view): string
        {
        $leagueId = (int) ($view['league_id'] ?? 0);
        $leagueName = $view['league_name'] ?? '';
        $leagueLogo = $view['league_logo'] ?? '';
        $season = (int) ($view['season'] ?? 0);
        $seasonLabel = $view['season_label'] ?? '';
        $stageName = $view['stage_name'] ?? '';
        $enabled = $view['enabled'] ?? [];
        $availableSeasons = $view['available_seasons'] ?? [];
        $standings = $view['standings'] ?? [];

        if (empty($enabled)) {
            return $this->noStandings(__('No leagues available.', 'basketa'));
        }

        if (empty($standings[0])) {
            return $this->noStandings(__('Standings are not available.', 'basketa'));
        }

        ob_start();

    ?>

        <div class="blm-standings-tabs">

    <?php 

    foreach ($enabled as $id => $league) : ?>

        <button
           class="blm-tab league-<?php echo esc_attr(sanitize_title($league['name'] ?? 'league')); ?> <?php echo (int) $id === $leagueId ? 'active' : ''; ?>"
            data-league="<?php echo esc_attr($id); ?>"
            data-season="<?php echo esc_attr($season); ?>"
        >

            <?php if (!empty($league['logo'])) : ?>

                <img
                    src="<?php echo esc_url($league['logo']); ?>"
                    alt=""
                    class="blm-tab-logo"
                >

            <?php endif; ?>

            <span>
                <?php echo esc_html($league['name']); ?>
            </span>

        </button>

    <?php endforeach; ?>

</div>

<div class="blm-standings-card">

    <div class="blm-season-selector">

        <label for="blm-season">
            <?php esc_html_e('Season', 'basketa'); ?>
        </label>

        <select id="blm-season">

            <?php foreach ($availableSeasons as $seasonData) : ?>

                <option
                    value="<?php echo esc_attr($seasonData['season']); ?>"
                    <?php selected($seasonData['season'], $season); ?>
                >
                    <?php echo esc_html($seasonData['label'] ?? $seasonData['season']); ?>
                </option>

            <?php endforeach; ?>

        </select>

    </div>

    <div class="blm-standings-header">

        <?php if (!empty($leagueLogo)) : ?>

            <img
                src="<?php echo esc_url($leagueLogo); ?>"
                alt=""
                class="blm-header-logo"
            >

        <?php endif; ?>

        <div class="blm-header-text">

            <h2>
                <?php echo esc_html($leagueName); ?>
            </h2>

            <p>
                <?php echo esc_html($stageName . ' • ' . $seasonLabel); ?>
            </p>

        </div>

    </div>

    <table class="blm-standings-table">

        <thead>

            <tr>

                <th>#</th>
                <th class="club"><?php esc_html_e('Club', 'basketa'); ?></th>
                <th>GP</th>
                <th>W</th>
                <th>L</th>
                <th>Win%</th>
                <th>L5</th>

            </tr>

        </thead>

        <tbody id="blm-standings-body">

            <?php

            echo $this->renderRows(
                $standings,
                $leagueId
            );
            ?>

        </tbody>

    </table>

</div>

<?php

return ob_get_clean();
    }
}

        