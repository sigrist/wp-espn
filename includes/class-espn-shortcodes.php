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
        // Shortcodes genéricos (mantidos para compatibilidade)
        add_shortcode('espn_scoreboard', array($this, 'scoreboard_shortcode'));
        add_shortcode('espn_standings', array($this, 'standings_shortcode'));
        add_shortcode('espn_upcoming', array($this, 'upcoming_games_shortcode'));
        add_shortcode('espn_team_schedule', array($this, 'team_schedule_shortcode'));
        add_shortcode('espn_season_navigator', array($this, 'season_navigator_shortcode'));

        // Shortcodes específicos para NFL
        add_shortcode('espn_nfl_scoreboard', array($this, 'nfl_scoreboard_shortcode'));
        add_shortcode('espn_nfl_standings', array($this, 'nfl_standings_shortcode'));
        add_shortcode('espn_nfl_navigator', array($this, 'nfl_navigator_shortcode'));
        add_shortcode('espn_nfl_upcoming', array($this, 'nfl_upcoming_shortcode'));

        // Shortcodes específicos para NBA
        add_shortcode('espn_nba_scoreboard', array($this, 'nba_scoreboard_shortcode'));
        add_shortcode('espn_nba_standings', array($this, 'nba_standings_shortcode'));
        add_shortcode('espn_nba_upcoming', array($this, 'nba_upcoming_shortcode'));

        // Shortcodes específicos para Soccer (Futebol)
        add_shortcode('espn_soccer_scoreboard', array($this, 'soccer_scoreboard_shortcode'));
        add_shortcode('espn_soccer_standings', array($this, 'soccer_standings_shortcode'));
        add_shortcode('espn_soccer_upcoming', array($this, 'soccer_upcoming_shortcode'));
    }

    /**
     * Shortcode para exibir resultados de jogos
     *
     * Uso: [espn_scoreboard sport="nfl" limit="5" week="1" seasontype="2"]
     * Uso soccer: [espn_scoreboard sport="soccer" league="bra.1" limit="10"]
     *
     * @param array $atts Atributos do shortcode
     * @return string HTML do scoreboard
     */
    public function scoreboard_shortcode($atts) {
        $atts = shortcode_atts(array(
            'sport' => 'nfl',
            'limit' => 10,
            'date' => null,
            'week' => null,
            'seasontype' => null,
            'league' => null,
            'title' => 'Resultados'
        ), $atts);

        $data = WP_ESPN_API::get_scoreboard(
            $atts['sport'],
            $atts['date'],
            $atts['limit'],
            $atts['week'],
            $atts['seasontype'],
            $atts['league']
        );

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
            'title' => 'Classificação',
            'group_by' => 'conference' // 'conference' ou 'division'
        ), $atts);

        $data = WP_ESPN_API::get_standings($atts['sport'], $atts['season']);

        if (is_wp_error($data)) {
            return '<div class="espn-error">Erro ao carregar dados: ' . $data->get_error_message() . '</div>';
        }

        // DEBUG: Mostrar estrutura da API temporariamente (adicione ?debug_espn=1 na URL)
        if (isset($_GET['debug_espn'])) {
            ob_start();
            echo '<pre style="background:#f0f0f0;padding:20px;overflow:auto;max-height:500px;border:2px solid #333;">';
            echo "=== DEBUG STANDINGS API ===\n\n";
            echo "Chaves principais: " . (is_array($data) ? implode(', ', array_keys($data)) : 'Não é array') . "\n\n";
            echo "Estrutura completa:\n";
            print_r($data);
            echo '</pre>';
            $debug = ob_get_clean();
        } else {
            $debug = '';
        }

        // A API pode retornar dados em diferentes estruturas
        $conferences = array();

        if (isset($data['children']) && !empty($data['children'])) {
            // Estrutura 1: com children (conferências/divisões)
            $conferences = $data['children'];
        } elseif (isset($data['standings']) && !empty($data['standings'])) {
            // Estrutura 2: com standings direto (geralmente vem do scoreboard)
            // Pode ser um array de grupos ou já ter a estrutura correta
            if (is_array($data['standings']) && isset($data['standings'][0])) {
                $conferences = $data['standings'];
            } else {
                $conferences = array(array(
                    'name' => $atts['sport'] === 'nfl' ? 'NFL' : strtoupper($atts['sport']),
                    'standings' => $data['standings']
                ));
            }
        } elseif (is_array($data) && isset($data[0]) && isset($data[0]['entries'])) {
            // Estrutura 3: array direto de standings com entries
            $conferences = $data;
        }

        if (empty($conferences)) {
            return $debug . '<div class="espn-no-data">Dados de classificação não disponíveis.</div>';
        }

        // Se group_by for 'division', expande para mostrar divisões
        if ($atts['group_by'] === 'division') {
            $conferences = $this->expand_to_divisions($conferences);
        }

        ob_start();
        echo $debug;
        ?>
        <div class="espn-standings">
            <?php if ($atts['title']): ?>
                <h3 class="espn-title"><?php echo esc_html($atts['title']); ?></h3>
            <?php endif; ?>

            <?php foreach ($conferences as $conference): ?>
                <div class="espn-conference">
                    <?php
                    $conference_name = isset($conference['name']) ? $conference['name'] : '';
                    $conference_name = WP_ESPN_i18n::translate_conference($conference_name, $atts['sport']);
                    ?>
                    <?php if ($conference_name): ?>
                        <h4 class="espn-conference-name"><?php echo esc_html($conference_name); ?></h4>
                    <?php endif; ?>

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

        $game_date = WP_ESPN_i18n::format_date($game['date']);
        $game_date_iso = isset($game['date']) ? $game['date'] : '';
        $status_translated = WP_ESPN_i18n::translate_text($status);
        ?>
        <div class="espn-game-card <?php echo esc_attr('status-' . $state); ?>">
            <div class="espn-game-header">
                <span class="espn-game-status"><?php echo esc_html($status_translated); ?></span>
                <span class="espn-game-date" data-timestamp="<?php echo esc_attr($game_date_iso); ?>"><?php echo esc_html($game_date); ?></span>
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
                    <?php
                    // Busca stats por nome ao invés de usar índices fixos
                    $stats = isset($entry['stats']) ? $entry['stats'] : array();
                    $stats_map = array();
                    foreach ($stats as $stat) {
                        if (isset($stat['name'])) {
                            $stats_map[$stat['name']] = $stat;
                        }
                    }

                    // Extrai valores comuns
                    $rank = $stats_map['rank']['value'] ?? ($stats_map['playoffSeed']['value'] ?? '-');
                    $wins = $stats_map['wins']['displayValue'] ?? ($stats_map['wins']['value'] ?? '-');
                    $losses = $stats_map['losses']['displayValue'] ?? ($stats_map['losses']['value'] ?? '-');
                    $winPercent = $stats_map['winPercent']['displayValue'] ?? ($stats_map['gamesBehind']['displayValue'] ?? '-');
                    ?>
                    <tr>
                        <td><?php echo esc_html($rank); ?></td>
                        <td class="espn-standings-team">
                            <?php $logo = WP_ESPN_API::get_team_logo($entry['team']); ?>
                            <?php if ($logo): ?>
                                <img src="<?php echo esc_url($logo); ?>" alt="" class="espn-team-logo-small">
                            <?php endif; ?>
                            <?php echo esc_html($entry['team']['displayName'] ?? $entry['team']['name'] ?? 'Time'); ?>
                        </td>
                        <td><?php echo esc_html($wins); ?></td>
                        <td><?php echo esc_html($losses); ?></td>
                        <td><?php echo esc_html($winPercent); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
    }

    /**
     * Expande a estrutura de conferências para mostrar divisões
     *
     * @param array $conferences Array de conferências
     * @return array Array com divisões expandidas
     */
    private function expand_to_divisions($conferences) {
        $divisions = array();

        foreach ($conferences as $conference) {
            // Se a conferência tem children (divisões), usa eles
            if (isset($conference['children']) && !empty($conference['children'])) {
                foreach ($conference['children'] as $division) {
                    $divisions[] = $division;
                }
            } else {
                // Se não tem divisões, mantém a conferência como está
                $divisions[] = $conference;
            }
        }

        return $divisions;
    }

    /**
     * Renderiza um jogo futuro
     *
     * @param array $game Dados do jogo
     */
    private function render_upcoming_game($game) {
        $competitions = $game['competitions'][0] ?? array();
        $competitors = $competitions['competitors'] ?? array();
        $game_date = WP_ESPN_i18n::format_date($game['date']);
        $game_date_iso = isset($game['date']) ? $game['date'] : '';

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
            <div class="espn-upcoming-date" data-timestamp="<?php echo esc_attr($game_date_iso); ?>"><?php echo esc_html($game_date); ?></div>
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
        $game_date = WP_ESPN_i18n::format_date($event['date']);
        $game_date_iso = isset($event['date']) ? $event['date'] : '';
        $name = $event['name'] ?? 'Jogo';
        ?>
        <div class="espn-schedule-item">
            <div class="espn-schedule-date" data-timestamp="<?php echo esc_attr($game_date_iso); ?>"><?php echo esc_html($game_date); ?></div>
            <div class="espn-schedule-name"><?php echo esc_html($name); ?></div>
        </div>
        <?php
    }

    /**
     * Shortcode para navegação completa de temporada
     *
     * Uso: [espn_season_navigator sport="nfl"]
     *
     * @param array $atts Atributos do shortcode
     * @return string HTML com navegação e resultados
     */
    public function season_navigator_shortcode($atts) {
        $atts = shortcode_atts(array(
            'sport' => 'nfl',
            'limit' => 20,
            'show_regular' => true,
            'show_playoffs' => true,
            'regular_weeks' => 18,
            'title' => ''
        ), $atts);

        // Detecta semana e tipo de temporada atual se não houver parâmetros na URL
        if (!isset($_GET['week']) || !isset($_GET['seasontype'])) {
            $current_data = $this->detect_current_week($atts['sport']);
            $default_week = $current_data['week'];
            $default_seasontype = $current_data['seasontype'];
        } else {
            $default_week = 1;
            $default_seasontype = 2;
        }

        // Pega parâmetros da URL ou usa os detectados
        $week = isset($_GET['week']) ? intval($_GET['week']) : $default_week;
        $seasontype = isset($_GET['seasontype']) ? intval($_GET['seasontype']) : $default_seasontype;

        // Define títulos baseados no tipo de temporada
        $season_names = array(
            1 => 'Pré-temporada',
            2 => 'Temporada Regular',
            3 => 'Playoffs',
            4 => 'Pro Bowl'
        );
        $season_name = isset($season_names[$seasontype]) ? $season_names[$seasontype] : 'Temporada Regular';

        // Para playoffs, nomes específicos das semanas
        if ($seasontype == 3) {
            $week_names = array(
                1 => 'Wild Card',
                2 => 'Divisional Round',
                3 => 'Conference Championships',
                4 => 'Super Bowl'
            );
            $week_title = isset($week_names[$week]) ? $week_names[$week] : "Semana $week";
        } else {
            $week_title = "Semana $week";
        }

        // Gera URL base para navegação
        $base_url = esc_url(remove_query_arg(array('week', 'seasontype')));

        ob_start();
        ?>
        <div class="espn-season-navigator">
            <?php if ($atts['title']): ?>
                <h1 class="espn-nav-main-title"><?php echo esc_html($atts['title']); ?></h1>
            <?php else: ?>
                <h1 class="espn-nav-main-title"><?php echo esc_html(strtoupper($atts['sport'])) . ' - ' . esc_html($season_name); ?></h1>
            <?php endif; ?>

            <h2 class="espn-nav-sub-title"><?php echo esc_html($week_title); ?></h2>

            <!-- Toggle entre Temporada Regular e Playoffs -->
            <div class="espn-season-toggle">
                <?php if ($atts['show_regular']): ?>
                    <a href="<?php echo esc_url(add_query_arg(array('week' => 1, 'seasontype' => 2), $base_url)); ?>"
                       class="espn-season-btn <?php echo $seasontype == 2 ? 'active' : ''; ?>">
                        📅 Temporada Regular
                    </a>
                <?php endif; ?>
                <?php if ($atts['show_playoffs']): ?>
                    <a href="<?php echo esc_url(add_query_arg(array('week' => 1, 'seasontype' => 3), $base_url)); ?>"
                       class="espn-season-btn <?php echo $seasontype == 3 ? 'active' : ''; ?>">
                        🏆 Playoffs
                    </a>
                <?php endif; ?>
            </div>

            <!-- Navegação entre Semanas -->
            <?php if ($seasontype == 2): // Temporada Regular ?>
                <div class="espn-week-navigation">
                    <?php for ($w = 1; $w <= $atts['regular_weeks']; $w++): ?>
                        <a href="<?php echo esc_url(add_query_arg(array('week' => $w, 'seasontype' => 2), $base_url)); ?>"
                           class="espn-week-btn <?php echo $w == $week ? 'active' : ''; ?>">
                            Semana <?php echo $w; ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php elseif ($seasontype == 3): // Playoffs ?>
                <div class="espn-playoff-navigation">
                    <a href="<?php echo esc_url(add_query_arg(array('week' => 1, 'seasontype' => 3), $base_url)); ?>"
                       class="espn-playoff-btn <?php echo $week == 1 ? 'active' : ''; ?>">
                        Wild Card
                    </a>
                    <a href="<?php echo esc_url(add_query_arg(array('week' => 2, 'seasontype' => 3), $base_url)); ?>"
                       class="espn-playoff-btn <?php echo $week == 2 ? 'active' : ''; ?>">
                        Divisional
                    </a>
                    <a href="<?php echo esc_url(add_query_arg(array('week' => 3, 'seasontype' => 3), $base_url)); ?>"
                       class="espn-playoff-btn <?php echo $week == 3 ? 'active' : ''; ?>">
                        Conference
                    </a>
                    <a href="<?php echo esc_url(add_query_arg(array('week' => 4, 'seasontype' => 3), $base_url)); ?>"
                       class="espn-playoff-btn <?php echo $week == 4 ? 'active' : ''; ?>">
                        Super Bowl
                    </a>
                </div>
            <?php endif; ?>

            <!-- Resultados -->
            <div class="espn-nav-results">
                <?php
                $data = WP_ESPN_API::get_scoreboard(
                    $atts['sport'],
                    null,
                    $atts['limit'],
                    $week,
                    $seasontype
                );

                if (is_wp_error($data)) {
                    echo '<div class="espn-error">Erro ao carregar dados: ' . esc_html($data->get_error_message()) . '</div>';
                } elseif (empty($data['events'])) {
                    echo '<div class="espn-no-games">Nenhum jogo disponível para esta semana.</div>';
                } else {
                    echo '<div class="espn-games-container">';
                    foreach ($data['events'] as $game) {
                        $this->render_game_card($game);
                    }
                    echo '</div>';
                }
                ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Detecta a semana atual do esporte
     *
     * @param string $sport Código do esporte
     * @return array Array com 'week' e 'seasontype'
     */
    private function detect_current_week($sport) {
        // Faz uma chamada à API sem especificar semana para pegar a atual
        $data = WP_ESPN_API::get_scoreboard($sport, null, 1);

        $default = array('week' => 1, 'seasontype' => 2);

        if (is_wp_error($data) || empty($data)) {
            return $default;
        }

        // Tenta pegar informações da semana da resposta da API
        $week = 1;
        $seasontype = 2;

        // A API da ESPN geralmente retorna informações sobre a semana atual
        if (isset($data['week'])) {
            $week = intval($data['week']['number'] ?? 1);
        } elseif (isset($data['season'])) {
            $week = intval($data['season']['week'] ?? 1);
        }

        // Verifica o tipo de temporada
        if (isset($data['season']['type'])) {
            $seasontype = intval($data['season']['type']);
        } elseif (isset($data['type'])) {
            $seasontype = intval($data['type']);
        }

        // Se for playoffs e não houver eventos, volta para a última semana da temporada regular
        if ($seasontype == 3 && empty($data['events'])) {
            $week = 18; // Última semana da temporada regular NFL
            $seasontype = 2;
        }

        return array(
            'week' => max(1, $week),
            'seasontype' => in_array($seasontype, array(1, 2, 3, 4)) ? $seasontype : 2
        );
    }

    // ========================================
    // Shortcodes Específicos para NFL
    // ========================================

    /**
     * Shortcode específico para scoreboard NFL
     *
     * Uso: [espn_nfl_scoreboard week="1" seasontype="2" limit="10"]
     *
     * @param array $atts Atributos do shortcode
     * @return string HTML do scoreboard
     */
    public function nfl_scoreboard_shortcode($atts) {
        $atts = shortcode_atts(array(
            'week' => null,
            'seasontype' => null,
            'limit' => 10,
            'title' => 'NFL - Resultados'
        ), $atts);

        // Força o esporte para NFL
        $atts['sport'] = 'nfl';

        return $this->scoreboard_shortcode($atts);
    }

    /**
     * Shortcode específico para standings NFL
     *
     * Uso: [espn_nfl_standings group_by="division" season="2023"]
     *
     * @param array $atts Atributos do shortcode
     * @return string HTML da tabela
     */
    public function nfl_standings_shortcode($atts) {
        $atts = shortcode_atts(array(
            'group_by' => 'division', // Por padrão, NFL mostra por divisão
            'season' => date('Y'),
            'title' => 'NFL - Classificação'
        ), $atts);

        // Força o esporte para NFL
        $atts['sport'] = 'nfl';

        return $this->standings_shortcode($atts);
    }

    /**
     * Shortcode específico para navegador de temporada NFL
     *
     * Uso: [espn_nfl_navigator title="Temporada NFL 2023-2024"]
     *
     * @param array $atts Atributos do shortcode
     * @return string HTML do navegador
     */
    public function nfl_navigator_shortcode($atts) {
        $atts = shortcode_atts(array(
            'title' => 'NFL - Temporada ' . date('Y')
        ), $atts);

        // Força o esporte para NFL
        $atts['sport'] = 'nfl';

        return $this->season_navigator_shortcode($atts);
    }

    /**
     * Shortcode específico para próximos jogos NFL
     *
     * Uso: [espn_nfl_upcoming limit="5"]
     *
     * @param array $atts Atributos do shortcode
     * @return string HTML dos próximos jogos
     */
    public function nfl_upcoming_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => 5,
            'title' => 'NFL - Próximos Jogos'
        ), $atts);

        // Força o esporte para NFL
        $atts['sport'] = 'nfl';

        return $this->upcoming_games_shortcode($atts);
    }

    // ========================================
    // Shortcodes Específicos para NBA
    // ========================================

    /**
     * Shortcode específico para scoreboard NBA
     *
     * Uso: [espn_nba_scoreboard limit="10"]
     *
     * @param array $atts Atributos do shortcode
     * @return string HTML do scoreboard
     */
    public function nba_scoreboard_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => 10,
            'date' => null,
            'title' => 'NBA - Resultados'
        ), $atts);

        // Força o esporte para NBA
        $atts['sport'] = 'nba';

        return $this->scoreboard_shortcode($atts);
    }

    /**
     * Shortcode específico para standings NBA
     *
     * Uso: [espn_nba_standings group_by="conference"]
     *
     * @param array $atts Atributos do shortcode
     * @return string HTML da tabela
     */
    public function nba_standings_shortcode($atts) {
        $atts = shortcode_atts(array(
            'group_by' => 'conference', // NBA geralmente mostra por conferência
            'season' => date('Y'),
            'title' => 'NBA - Classificação'
        ), $atts);

        // Força o esporte para NBA
        $atts['sport'] = 'nba';

        return $this->standings_shortcode($atts);
    }

    /**
     * Shortcode específico para próximos jogos NBA
     *
     * Uso: [espn_nba_upcoming limit="10"]
     *
     * @param array $atts Atributos do shortcode
     * @return string HTML dos próximos jogos
     */
    public function nba_upcoming_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => 10,
            'title' => 'NBA - Próximos Jogos'
        ), $atts);

        // Força o esporte para NBA
        $atts['sport'] = 'nba';

        return $this->upcoming_games_shortcode($atts);
    }

    // ========================================
    // Shortcodes Específicos para Soccer (Futebol)
    // ========================================

    /**
     * Shortcode específico para scoreboard de futebol
     *
     * Uso: [espn_soccer_scoreboard league="bra.1" limit="10"]
     *
     * @param array $atts Atributos do shortcode
     * @return string HTML do scoreboard
     */
    public function soccer_scoreboard_shortcode($atts) {
        $atts = shortcode_atts(array(
            'league' => 'bra.1', // Brasileirão por padrão
            'limit' => 10,
            'date' => null,
            'title' => null // Será definido baseado na liga
        ), $atts);

        // Define título baseado na liga se não foi especificado
        if (!$atts['title']) {
            $atts['title'] = $this->get_soccer_league_name($atts['league']) . ' - Resultados';
        }

        // Força o esporte para soccer
        $atts['sport'] = 'soccer';

        return $this->scoreboard_shortcode($atts);
    }

    /**
     * Shortcode específico para standings de futebol
     *
     * Uso: [espn_soccer_standings league="bra.1"]
     *
     * @param array $atts Atributos do shortcode
     * @return string HTML da tabela
     */
    public function soccer_standings_shortcode($atts) {
        $atts = shortcode_atts(array(
            'league' => 'bra.1', // Brasileirão por padrão
            'season' => date('Y'),
            'title' => null // Será definido baseado na liga
        ), $atts);

        // Define título baseado na liga se não foi especificado
        if (!$atts['title']) {
            $atts['title'] = $this->get_soccer_league_name($atts['league']) . ' - Classificação';
        }

        // Força o esporte para soccer
        $atts['sport'] = 'soccer';

        // Para soccer, standings geralmente não tem group_by (é por liga)
        return $this->standings_shortcode($atts);
    }

    /**
     * Shortcode específico para próximos jogos de futebol
     *
     * Uso: [espn_soccer_upcoming league="bra.1" limit="10"]
     *
     * @param array $atts Atributos do shortcode
     * @return string HTML dos próximos jogos
     */
    public function soccer_upcoming_shortcode($atts) {
        $atts = shortcode_atts(array(
            'league' => 'bra.1', // Brasileirão por padrão
            'limit' => 10,
            'title' => null // Será definido baseado na liga
        ), $atts);

        // Define título baseado na liga se não foi especificado
        if (!$atts['title']) {
            $atts['title'] = $this->get_soccer_league_name($atts['league']) . ' - Próximos Jogos';
        }

        // Força o esporte para soccer
        $atts['sport'] = 'soccer';

        return $this->upcoming_games_shortcode($atts);
    }

    /**
     * Retorna o nome amigável de uma liga de futebol
     *
     * @param string $league_code Código da liga (ex: bra.1, eng.1)
     * @return string Nome da liga
     */
    private function get_soccer_league_name($league_code) {
        $league_names = array(
            // Ligas Europeias
            'eng.1' => 'Premier League',
            'esp.1' => 'La Liga',
            'ita.1' => 'Serie A',
            'ger.1' => 'Bundesliga',
            'fra.1' => 'Ligue 1',
            'ned.1' => 'Eredivisie',
            'por.1' => 'Primeira Liga',

            // Competições Internacionais
            'uefa.champions' => 'Champions League',
            'uefa.europa' => 'Europa League',
            'uefa.europa.conf' => 'Conference League',
            'fifa.world' => 'Copa do Mundo',

            // América do Sul
            'bra.1' => 'Brasileirão',
            'arg.1' => 'Liga Argentina',
            'conmebol.libertadores' => 'Libertadores',
            'conmebol.sudamericana' => 'Sul-Americana',

            // América do Norte
            'usa.1' => 'MLS',
            'mex.1' => 'Liga MX',
            'concacaf.champions' => 'Champions CONCACAF',
        );

        return isset($league_names[$league_code]) ? $league_names[$league_code] : 'Futebol';
    }
}
