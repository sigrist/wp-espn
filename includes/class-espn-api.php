<?php
/**
 * Classe para integração com a API da ESPN
 */

if (!defined('ABSPATH')) {
    exit;
}

class WP_ESPN_API {

    /**
     * Base URLs da API
     */
    const BASE_URL = 'https://site.api.espn.com/apis/site/v2/sports';
    const CDN_URL = 'https://cdn.espn.com/core';

    /**
     * Tempo de cache em segundos (1 hora)
     */
    const CACHE_TIME = 3600;

    /**
     * Mapeia os esportes para suas URLs
     */
    private static $sports_map = array(
        'nfl' => 'football/nfl',
        'nba' => 'basketball/nba',
        'mlb' => 'baseball/mlb',
        'nhl' => 'hockey/nhl',
        'soccer' => 'soccer',
        'college-football' => 'football/college-football',
        'college-basketball' => 'basketball/mens-college-basketball'
    );

    /**
     * Faz uma requisição para a API da ESPN
     *
     * @param string $endpoint Endpoint da API
     * @param array $args Argumentos adicionais
     * @return array|WP_Error
     */
    private static function make_request($endpoint, $args = array()) {
        $cache_key = 'wp_espn_' . md5($endpoint . serialize($args));
        $cached_data = get_transient($cache_key);

        if ($cached_data !== false) {
            return $cached_data;
        }

        $url = $endpoint;
        if (!empty($args)) {
            $url = add_query_arg($args, $url);
        }

        $response = wp_remote_get($url, array(
            'timeout' => 15,
            'headers' => array(
                'Accept' => 'application/json'
            )
        ));

        if (is_wp_error($response)) {
            return $response;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return new WP_Error('json_error', 'Erro ao processar resposta da API');
        }

        set_transient($cache_key, $data, self::CACHE_TIME);

        return $data;
    }

    /**
     * Obtém o scoreboard (resultados) de um esporte
     *
     * @param string $sport Código do esporte (nfl, nba, etc)
     * @param string $date Data no formato YYYYMMDD (opcional)
     * @param int $limit Limite de jogos
     * @return array|WP_Error
     */
    public static function get_scoreboard($sport, $date = null, $limit = 10) {
        if (!isset(self::$sports_map[$sport])) {
            return new WP_Error('invalid_sport', 'Esporte inválido');
        }

        $sport_path = self::$sports_map[$sport];
        $endpoint = self::BASE_URL . '/' . $sport_path . '/scoreboard';

        $args = array('limit' => $limit);
        if ($date) {
            $args['dates'] = $date;
        }

        return self::make_request($endpoint, $args);
    }

    /**
     * Obtém a tabela de classificação
     *
     * @param string $sport Código do esporte
     * @param int $season Temporada (ano)
     * @return array|WP_Error
     */
    public static function get_standings($sport, $season = null) {
        if (!isset(self::$sports_map[$sport])) {
            return new WP_Error('invalid_sport', 'Esporte inválido');
        }

        $sport_path = self::$sports_map[$sport];
        $endpoint = self::BASE_URL . '/' . $sport_path . '/standings';

        $args = array();
        if ($season) {
            $args['season'] = $season;
        } else {
            $args['season'] = date('Y');
        }

        return self::make_request($endpoint, $args);
    }

    /**
     * Obtém informações dos times
     *
     * @param string $sport Código do esporte
     * @param string $team_id ID do time (opcional)
     * @return array|WP_Error
     */
    public static function get_teams($sport, $team_id = null) {
        if (!isset(self::$sports_map[$sport])) {
            return new WP_Error('invalid_sport', 'Esporte inválido');
        }

        $sport_path = self::$sports_map[$sport];
        $endpoint = self::BASE_URL . '/' . $sport_path . '/teams';

        if ($team_id) {
            $endpoint .= '/' . $team_id;
        }

        return self::make_request($endpoint);
    }

    /**
     * Obtém o calendário de jogos de um time
     *
     * @param string $sport Código do esporte
     * @param string $team_id ID do time
     * @param int $season Temporada (opcional)
     * @return array|WP_Error
     */
    public static function get_team_schedule($sport, $team_id, $season = null) {
        if (!isset(self::$sports_map[$sport])) {
            return new WP_Error('invalid_sport', 'Esporte inválido');
        }

        $sport_path = self::$sports_map[$sport];
        $endpoint = self::BASE_URL . '/' . $sport_path . '/teams/' . $team_id . '/schedule';

        $args = array();
        if ($season) {
            $args['season'] = $season;
        }

        return self::make_request($endpoint, $args);
    }

    /**
     * Obtém detalhes de um jogo específico
     *
     * @param string $sport Código do esporte
     * @param string $event_id ID do evento
     * @return array|WP_Error
     */
    public static function get_game_summary($sport, $event_id) {
        if (!isset(self::$sports_map[$sport])) {
            return new WP_Error('invalid_sport', 'Esporte inválido');
        }

        $sport_path = self::$sports_map[$sport];
        $endpoint = self::BASE_URL . '/' . $sport_path . '/summary';

        $args = array('event' => $event_id);

        return self::make_request($endpoint, $args);
    }

    /**
     * Limpa o cache do plugin
     */
    public static function clear_cache() {
        global $wpdb;
        $wpdb->query(
            "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_wp_espn_%' OR option_name LIKE '_transient_timeout_wp_espn_%'"
        );
    }

    /**
     * Formata a data/hora do jogo
     *
     * @param string $date Data no formato ISO
     * @return string
     */
    public static function format_game_date($date) {
        $timestamp = strtotime($date);
        return date_i18n('d/m/Y H:i', $timestamp);
    }

    /**
     * Obtém a URL do logo do time
     *
     * @param array $team Array com informações do time
     * @return string
     */
    public static function get_team_logo($team) {
        if (isset($team['logo'])) {
            return $team['logo'];
        }
        if (isset($team['logos']) && !empty($team['logos'])) {
            return $team['logos'][0]['href'];
        }
        return '';
    }
}
