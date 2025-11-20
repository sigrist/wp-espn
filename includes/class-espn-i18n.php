<?php
/**
 * Classe para internacionalização e tradução de termos da ESPN
 */

if (!defined('ABSPATH')) {
    exit;
}

class WP_ESPN_i18n {

    /**
     * Status de jogos traduzidos
     */
    private static $game_status = array(
        'pre' => 'Agendado',
        'in' => 'Ao Vivo',
        'post' => 'Finalizado',
        'final' => 'Final',
        'scheduled' => 'Agendado',
        'postponed' => 'Adiado',
        'canceled' => 'Cancelado',
        'suspended' => 'Suspenso',
        'delayed' => 'Atrasado'
    );

    /**
     * Períodos/Quarters traduzidos
     */
    private static $periods = array(
        '1st Quarter' => '1º Quarto',
        '2nd Quarter' => '2º Quarto',
        '3rd Quarter' => '3º Quarto',
        '4th Quarter' => '4º Quarto',
        'Halftime' => 'Intervalo',
        'Overtime' => 'Prorrogação',
        '1st Half' => '1º Tempo',
        '2nd Half' => '2º Tempo',
        'End of 1st' => 'Fim do 1º',
        'End of 2nd' => 'Fim do 2º',
        'End of 3rd' => 'Fim do 3º',
        'End of 4th' => 'Fim do 4º',
        '1st Period' => '1º Período',
        '2nd Period' => '2º Período',
        '3rd Period' => '3º Período',
        'Top 1st' => 'Topo 1ª',
        'Bottom 1st' => 'Base 1ª',
        'Top 2nd' => 'Topo 2ª',
        'Bottom 2nd' => 'Base 2ª',
        'Top 3rd' => 'Topo 3ª',
        'Bottom 3rd' => 'Base 3ª',
        'Top 4th' => 'Topo 4ª',
        'Bottom 4th' => 'Base 4ª',
        'Top 5th' => 'Topo 5ª',
        'Bottom 5th' => 'Base 5ª',
        'Top 6th' => 'Topo 6ª',
        'Bottom 6th' => 'Base 6ª',
        'Top 7th' => 'Topo 7ª',
        'Bottom 7th' => 'Base 7ª',
        'Top 8th' => 'Topo 8ª',
        'Bottom 8th' => 'Base 8ª',
        'Top 9th' => 'Topo 9ª',
        'Bottom 9th' => 'Base 9ª'
    );

    /**
     * Dias da semana
     */
    private static $days = array(
        'Monday' => 'Segunda-feira',
        'Tuesday' => 'Terça-feira',
        'Wednesday' => 'Quarta-feira',
        'Thursday' => 'Quinta-feira',
        'Friday' => 'Sexta-feira',
        'Saturday' => 'Sábado',
        'Sunday' => 'Domingo',
        'Mon' => 'Seg',
        'Tue' => 'Ter',
        'Wed' => 'Qua',
        'Thu' => 'Qui',
        'Fri' => 'Sex',
        'Sat' => 'Sáb',
        'Sun' => 'Dom'
    );

    /**
     * Meses
     */
    private static $months = array(
        'January' => 'Janeiro',
        'February' => 'Fevereiro',
        'March' => 'Março',
        'April' => 'Abril',
        'May' => 'Maio',
        'June' => 'Junho',
        'July' => 'Julho',
        'August' => 'Agosto',
        'September' => 'Setembro',
        'October' => 'Outubro',
        'November' => 'Novembro',
        'December' => 'Dezembro',
        'Jan' => 'Jan',
        'Feb' => 'Fev',
        'Mar' => 'Mar',
        'Apr' => 'Abr',
        'Jun' => 'Jun',
        'Jul' => 'Jul',
        'Aug' => 'Ago',
        'Sep' => 'Set',
        'Oct' => 'Out',
        'Nov' => 'Nov',
        'Dec' => 'Dez'
    );

    /**
     * Termos gerais
     */
    private static $general_terms = array(
        'vs' => 'vs',
        'at' => 'em',
        '@' => '@',
        'Week' => 'Semana',
        'Game' => 'Jogo',
        'Final Score' => 'Placar Final',
        'Live' => 'Ao Vivo',
        'Score' => 'Placar',
        'Standings' => 'Classificação',
        'Conference' => 'Conferência',
        'Division' => 'Divisão',
        'Record' => 'Recorde',
        'Wins' => 'Vitórias',
        'Losses' => 'Derrotas',
        'Ties' => 'Empates',
        'Win %' => '% Vitórias',
        'Points For' => 'Pontos Pró',
        'Points Against' => 'Pontos Contra',
        'Home' => 'Casa',
        'Away' => 'Visitante',
        'Neutral' => 'Neutro',
        'TBD' => 'A Definir',
        'Bye Week' => 'Semana de Folga'
    );

    /**
     * Nomes de conferências NFL
     */
    private static $nfl_conferences = array(
        'American Football Conference' => 'Conferência Americana',
        'National Football Conference' => 'Conferência Nacional',
        'AFC East' => 'AFC Leste',
        'AFC North' => 'AFC Norte',
        'AFC South' => 'AFC Sul',
        'AFC West' => 'AFC Oeste',
        'NFC East' => 'NFC Leste',
        'NFC North' => 'NFC Norte',
        'NFC South' => 'NFC Sul',
        'NFC West' => 'NFC Oeste'
    );

    /**
     * Nomes de conferências NBA
     */
    private static $nba_conferences = array(
        'Eastern Conference' => 'Conferência Leste',
        'Western Conference' => 'Conferência Oeste',
        'Atlantic' => 'Atlântico',
        'Central' => 'Central',
        'Southeast' => 'Sudeste',
        'Northwest' => 'Noroeste',
        'Pacific' => 'Pacífico',
        'Southwest' => 'Sudoeste'
    );

    /**
     * Traduz o status do jogo
     *
     * @param string $status Status em inglês
     * @return string Status traduzido
     */
    public static function translate_game_status($status) {
        $status_lower = strtolower($status);

        if (isset(self::$game_status[$status_lower])) {
            return apply_filters('wp_espn_translate_game_status', self::$game_status[$status_lower], $status);
        }

        return apply_filters('wp_espn_translate_game_status', $status, $status);
    }

    /**
     * Traduz o período/quarter do jogo
     *
     * @param string $period Período em inglês
     * @return string Período traduzido
     */
    public static function translate_period($period) {
        if (isset(self::$periods[$period])) {
            return apply_filters('wp_espn_translate_period', self::$periods[$period], $period);
        }

        // Tenta traduzir padrões comuns
        $period = str_replace('Quarter', 'Quarto', $period);
        $period = str_replace('Period', 'Período', $period);
        $period = str_replace('Half', 'Tempo', $period);
        $period = str_replace('Inning', 'Entrada', $period);

        return apply_filters('wp_espn_translate_period', $period, $period);
    }

    /**
     * Traduz termos gerais
     *
     * @param string $text Texto em inglês
     * @return string Texto traduzido
     */
    public static function translate_general($text) {
        if (isset(self::$general_terms[$text])) {
            return apply_filters('wp_espn_translate_general', self::$general_terms[$text], $text);
        }

        return apply_filters('wp_espn_translate_general', $text, $text);
    }

    /**
     * Traduz nome de conferência/divisão
     *
     * @param string $conference Nome da conferência
     * @param string $sport Esporte (nfl, nba, etc)
     * @return string Nome traduzido
     */
    public static function translate_conference($conference, $sport = '') {
        // Tenta NFL
        if (isset(self::$nfl_conferences[$conference])) {
            return apply_filters('wp_espn_translate_conference', self::$nfl_conferences[$conference], $conference, $sport);
        }

        // Tenta NBA
        if (isset(self::$nba_conferences[$conference])) {
            return apply_filters('wp_espn_translate_conference', self::$nba_conferences[$conference], $conference, $sport);
        }

        return apply_filters('wp_espn_translate_conference', $conference, $conference, $sport);
    }

    /**
     * Traduz uma string completa substituindo múltiplos termos
     *
     * @param string $text Texto completo
     * @return string Texto traduzido
     */
    public static function translate_text($text) {
        $original = $text;

        // Traduz dias da semana
        foreach (self::$days as $en => $pt) {
            $text = str_replace($en, $pt, $text);
        }

        // Traduz meses
        foreach (self::$months as $en => $pt) {
            $text = str_replace($en, $pt, $text);
        }

        // Traduz termos gerais
        foreach (self::$general_terms as $en => $pt) {
            $text = str_replace($en, $pt, $text);
        }

        return apply_filters('wp_espn_translate_text', $text, $original);
    }

    /**
     * Formata data/hora em português
     *
     * @param string $date Data no formato ISO
     * @param string $format Formato de saída (padrão: 'd/m/Y H:i')
     * @return string Data formatada
     */
    public static function format_date($date, $format = 'd/m/Y H:i') {
        $timestamp = strtotime($date);

        // Define locale para português
        $old_locale = setlocale(LC_TIME, 0);
        setlocale(LC_TIME, 'pt_BR.UTF-8', 'pt_BR', 'portuguese');

        $formatted = date_i18n($format, $timestamp);

        // Restaura locale
        setlocale(LC_TIME, $old_locale);

        return apply_filters('wp_espn_format_date', $formatted, $date, $format);
    }

    /**
     * Obtém tradução personalizada via filtro
     *
     * @param string $key Chave da tradução
     * @param string $default Valor padrão
     * @return string Tradução
     */
    public static function get_translation($key, $default = '') {
        return apply_filters('wp_espn_custom_translation', $default, $key);
    }

    /**
     * Adiciona uma tradução personalizada
     *
     * @param string $category Categoria (game_status, periods, etc)
     * @param string $key Chave em inglês
     * @param string $value Tradução
     */
    public static function add_translation($category, $key, $value) {
        if (property_exists(__CLASS__, $category)) {
            self::${$category}[$key] = $value;
        }
    }

    /**
     * Obtém todas as traduções de uma categoria
     *
     * @param string $category Categoria
     * @return array Traduções
     */
    public static function get_translations($category) {
        if (property_exists(__CLASS__, $category)) {
            return self::${$category};
        }
        return array();
    }
}
