<?php
/**
 * Classe para gerenciar os shortcodes do plugin
 */

if (!defined('ABSPATH')) {
    exit;
}

class WP_ESPN_Shortcodes {

    /**
     * Registra todos os shortcodes
     */
    public function register() {
        add_shortcode('espn_scoreboard', array($this, 'scoreboard_shortcode'));
        add_shortcode('espn_standings', array($this, 'standings_shortcode'));
        add_shortcode('espn_upcoming', array($this, 'upcoming_games_shortcode'));
        add_shortcode('espn_team_schedule', array($this, 'team_schedule_shortcode'));
    }

    /**
     * Shortcode para exibir resultados de jogos
     *
     * Uso: [espn_scoreboard sport="nfl" limit="5" date="20231201"]
     *
     * @param array $atts Atributos do shortcode
     * @return string HTML do scoreboard
     */
    public function scoreboard_shortcode($atts) {
        $atts = shortcode_atts(array(
            'sport' => 'nfl',
            'limit' => 10,
            'date' => null,
            'title' => 'Resultados'
        ), $atts);

        $data = WP_ESPN_API::get_scoreboard($atts['sport'], $atts['date'], $atts['limit']);

        if (is_wp_error($data)) {
            return '<div class="espn-error">Erro ao carregar dados: ' . $data->get_error_message() . '</div>';
        }

        if (empty($data['events'])) {
            return '<div class="espn-no-games">Nenhum jogo disponível no momento.</div>';
        }

        ob_start();
        ?>
        <div class="espn-scoreboard">
            <?php if ($atts['title']): ?>
                <h3 class="espn-title"><?php echo esc_html($atts['title']); ?></h3>
            <?php endif; ?>

            <div class="espn-games-container">
                <?php foreach ($data['events'] as $game): ?>
                    <?php $this->render_game_card($game); ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Shortcode para exibir tabela de classificação
     *
     * Uso: [espn_standings sport="nfl" season="2023"]
     *
     * @param array $atts Atributos do shortcode
     * @return string HTML da tabela
     */
    public function standings_shortcode($atts) {
        $atts = shortcode_atts(array(
            'sport' => 'nfl',
            'season' => date('Y'),
            'title' => 'Classificação'
        ), $atts);

        $data = WP_ESPN_API::get_standings($atts['sport'], $atts['season']);

        if (is_wp_error($data)) {
            return '<div class="espn-error">Erro ao carregar dados: ' . $data->get_error_message() . '</div>';
        }

        if (empty($data['children'])) {
            return '<div class="espn-no-data">Dados de classificação não disponíveis.</div>';
        }

        ob_start();
        ?>
        <div class="espn-standings">
            <?php if ($atts['title']): ?>
                <h3 class="espn-title"><?php echo esc_html($atts['title']); ?></h3>
            <?php endif; ?>

            <?php foreach ($data['children'] as $conference): ?>
                <div class="espn-conference">
                    <h4 class="espn-conference-name"><?php echo esc_html($conference['name']); ?></h4>

                    <?php if (isset($conference['standings'])): ?>
                        <?php $this->render_standings_table($conference['standings']); ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Shortcode para exibir próximos jogos
     *
     * Uso: [espn_upcoming sport="nba" limit="5"]
     *
     * @param array $atts Atributos do shortcode
     * @return string HTML dos próximos jogos
     */
    public function upcoming_games_shortcode($atts) {
        $atts = shortcode_atts(array(
            'sport' => 'nfl',
            'limit' => 5,
            'title' => 'Próximos Jogos'
        ), $atts);

        $data = WP_ESPN_API::get_scoreboard($atts['sport'], null, $atts['limit']);

        if (is_wp_error($data)) {
            return '<div class="espn-error">Erro ao carregar dados: ' . $data->get_error_message() . '</div>';
        }

        if (empty($data['events'])) {
            return '<div class="espn-no-games">Nenhum jogo agendado.</div>';
        }

        // Filtra apenas jogos futuros
        $upcoming = array_filter($data['events'], function($game) {
            $status = $game['status']['type']['state'] ?? '';
            return $status === 'pre';
        });

        if (empty($upcoming)) {
            return '<div class="espn-no-games">Nenhum jogo agendado.</div>';
        }

        ob_start();
        ?>
        <div class="espn-upcoming">
            <?php if ($atts['title']): ?>
                <h3 class="espn-title"><?php echo esc_html($atts['title']); ?></h3>
            <?php endif; ?>

            <div class="espn-games-list">
                <?php foreach (array_slice($upcoming, 0, $atts['limit']) as $game): ?>
                    <?php $this->render_upcoming_game($game); ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Shortcode para exibir calendário de um time
     *
     * Uso: [espn_team_schedule sport="nfl" team="dal" season="2023"]
     *
     * @param array $atts Atributos do shortcode
     * @return string HTML do calendário
     */
    public function team_schedule_shortcode($atts) {
        $atts = shortcode_atts(array(
            'sport' => 'nfl',
            'team' => '',
            'season' => date('Y'),
            'title' => 'Calendário'
        ), $atts);

        if (empty($atts['team'])) {
            return '<div class="espn-error">ID do time é obrigatório.</div>';
        }

        $data = WP_ESPN_API::get_team_schedule($atts['sport'], $atts['team'], $atts['season']);

        if (is_wp_error($data)) {
            return '<div class="espn-error">Erro ao carregar dados: ' . $data->get_error_message() . '</div>';
        }

        ob_start();
        ?>
        <div class="espn-team-schedule">
            <?php if ($atts['title']): ?>
                <h3 class="espn-title"><?php echo esc_html($atts['title']); ?></h3>
            <?php endif; ?>

            <?php if (isset($data['events'])): ?>
                <div class="espn-schedule-list">
                    <?php foreach ($data['events'] as $event): ?>
                        <?php $this->render_schedule_item($event); ?>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="espn-no-data">Calendário não disponível.</div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Renderiza o card de um jogo
     *
     * @param array $game Dados do jogo
     */
    private function render_game_card($game) {
        $status = $game['status']['type']['detail'] ?? '';
        $state = $game['status']['type']['state'] ?? '';
        $competitions = $game['competitions'][0] ?? array();
        $competitors = $competitions['competitors'] ?? array();

        $home_team = null;
        $away_team = null;

        foreach ($competitors as $competitor) {
            if ($competitor['homeAway'] === 'home') {
                $home_team = $competitor;
            } else {
                $away_team = $competitor;
            }
        }

        if (!$home_team || !$away_team) {
            return;
        }

        $game_date = WP_ESPN_API::format_game_date($game['date']);
        ?>
        <div class="espn-game-card <?php echo esc_attr('status-' . $state); ?>">
            <div class="espn-game-header">
                <span class="espn-game-status"><?php echo esc_html($status); ?></span>
                <span class="espn-game-date"><?php echo esc_html($game_date); ?></span>
            </div>

            <div class="espn-game-teams">
                <div class="espn-team away-team">
                    <div class="espn-team-info">
                        <?php $logo = WP_ESPN_API::get_team_logo($away_team['team']); ?>
                        <?php if ($logo): ?>
                            <img src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr($away_team['team']['displayName']); ?>" class="espn-team-logo">
                        <?php endif; ?>
                        <span class="espn-team-name"><?php echo esc_html($away_team['team']['displayName']); ?></span>
                    </div>
                    <span class="espn-team-score"><?php echo esc_html($away_team['score'] ?? '-'); ?></span>
                </div>

                <div class="espn-vs">vs</div>

                <div class="espn-team home-team">
                    <div class="espn-team-info">
                        <?php $logo = WP_ESPN_API::get_team_logo($home_team['team']); ?>
                        <?php if ($logo): ?>
                            <img src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr($home_team['team']['displayName']); ?>" class="espn-team-logo">
                        <?php endif; ?>
                        <span class="espn-team-name"><?php echo esc_html($home_team['team']['displayName']); ?></span>
                    </div>
                    <span class="espn-team-score"><?php echo esc_html($home_team['score'] ?? '-'); ?></span>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Renderiza a tabela de classificação
     *
     * @param array $standings Dados da classificação
     */
    private function render_standings_table($standings) {
        if (empty($standings['entries'])) {
            return;
        }
        ?>
        <table class="espn-standings-table">
            <thead>
                <tr>
                    <th>Pos</th>
                    <th>Time</th>
                    <th>V</th>
                    <th>D</th>
                    <th>%</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($standings['entries'] as $entry): ?>
                    <tr>
                        <td><?php echo esc_html($entry['stats'][0]['value'] ?? '-'); ?></td>
                        <td class="espn-standings-team">
                            <?php $logo = WP_ESPN_API::get_team_logo($entry['team']); ?>
                            <?php if ($logo): ?>
                                <img src="<?php echo esc_url($logo); ?>" alt="" class="espn-team-logo-small">
                            <?php endif; ?>
                            <?php echo esc_html($entry['team']['displayName']); ?>
                        </td>
                        <td><?php echo esc_html($entry['stats'][7]['displayValue'] ?? '-'); ?></td>
                        <td><?php echo esc_html($entry['stats'][1]['displayValue'] ?? '-'); ?></td>
                        <td><?php echo esc_html($entry['stats'][3]['displayValue'] ?? '-'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
    }

    /**
     * Renderiza um jogo futuro
     *
     * @param array $game Dados do jogo
     */
    private function render_upcoming_game($game) {
        $competitions = $game['competitions'][0] ?? array();
        $competitors = $competitions['competitors'] ?? array();
        $game_date = WP_ESPN_API::format_game_date($game['date']);

        $home_team = null;
        $away_team = null;

        foreach ($competitors as $competitor) {
            if ($competitor['homeAway'] === 'home') {
                $home_team = $competitor;
            } else {
                $away_team = $competitor;
            }
        }

        if (!$home_team || !$away_team) {
            return;
        }
        ?>
        <div class="espn-upcoming-game">
            <div class="espn-upcoming-date"><?php echo esc_html($game_date); ?></div>
            <div class="espn-upcoming-matchup">
                <span class="espn-upcoming-team"><?php echo esc_html($away_team['team']['displayName']); ?></span>
                <span class="espn-upcoming-vs">@</span>
                <span class="espn-upcoming-team"><?php echo esc_html($home_team['team']['displayName']); ?></span>
            </div>
        </div>
        <?php
    }

    /**
     * Renderiza um item do calendário
     *
     * @param array $event Dados do evento
     */
    private function render_schedule_item($event) {
        $game_date = WP_ESPN_API::format_game_date($event['date']);
        $name = $event['name'] ?? 'Jogo';
        ?>
        <div class="espn-schedule-item">
            <div class="espn-schedule-date"><?php echo esc_html($game_date); ?></div>
            <div class="espn-schedule-name"><?php echo esc_html($name); ?></div>
        </div>
        <?php
    }
}
