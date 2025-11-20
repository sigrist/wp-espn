<?php
/**
 * Plugin Name: ESPN Sports Scores
 * Plugin URI: https://github.com/sigrist/wp-espn
 * Description: Exibe resultados de jogos, tabelas de classificação e próximos jogos usando a API da ESPN
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://github.com/sigrist
 * License: GPL2
 * Text Domain: wp-espn-sports
 */

// Previne acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Define constantes do plugin
define('WP_ESPN_VERSION', '1.0.0');
define('WP_ESPN_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WP_ESPN_PLUGIN_URL', plugin_dir_url(__FILE__));

// Carrega as classes necessárias
require_once WP_ESPN_PLUGIN_DIR . 'includes/class-espn-api.php';
require_once WP_ESPN_PLUGIN_DIR . 'includes/class-espn-shortcodes.php';

/**
 * Classe principal do plugin
 */
class WP_ESPN_Sports_Scores {

    private static $instance = null;

    /**
     * Singleton instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Construtor
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Inicializa os hooks do WordPress
     */
    private function init_hooks() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('init', array($this, 'init_shortcodes'));
    }

    /**
     * Registra os estilos
     */
    public function enqueue_styles() {
        wp_enqueue_style(
            'wp-espn-sports',
            WP_ESPN_PLUGIN_URL . 'assets/css/espn-sports.css',
            array(),
            WP_ESPN_VERSION
        );
    }

    /**
     * Registra os scripts
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            'wp-espn-sports',
            WP_ESPN_PLUGIN_URL . 'assets/js/espn-sports.js',
            array('jquery'),
            WP_ESPN_VERSION,
            true
        );

        // Passa dados para o JavaScript
        wp_localize_script('wp-espn-sports', 'wpEspnAjax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wp_espn_nonce')
        ));
    }

    /**
     * Inicializa os shortcodes
     */
    public function init_shortcodes() {
        $shortcodes = new WP_ESPN_Shortcodes();
        $shortcodes->register();
    }
}

/**
 * Inicializa o plugin
 */
function wp_espn_sports_init() {
    return WP_ESPN_Sports_Scores::get_instance();
}

// Inicia o plugin
wp_espn_sports_init();
