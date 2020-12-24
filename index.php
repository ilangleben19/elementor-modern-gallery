<?php
/**
 * Plugin Name: Elementor Modern Gallery
 * Plugin URI: https://github.com/ilangleben19/elementor-modern-gallery
 * Description: A modern gallery Elementor widget.
 * Version: 1.0
 * Author: Ian Langleben
 * Author URI: https://github.com/ilangleben19
 **/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

final class Elementor_Modern_Gallery_Extension
{
    const VERSION = '1.0.0';
    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';
    const MINIMUM_PHP_VERSION = '7.0';

    private static $_instance = null;
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        add_action('plugins_loaded', [$this, 'on_plugins_loaded']);
    }

    public function i18n()
    {
        load_plugin_textdomain('elementor-modern-gallery-extension');
    }

    public function on_plugins_loaded()
    {
        if ($this->is_compatible()) {
            add_action('elementor/init', [$this, 'init']);
        }
    }

    public function is_compatible()
    {
        // Check if Elementor installed and activated
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
            return false;
        }

        // Check for required Elementor version
        if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
            return false;
        }

        // Check for required PHP version
        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
            return false;
        }

        return true;
    }

    public function admin_notice_missing_main_plugin()
    {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'elementor-modern-gallery-extension'),
            '<strong>' . esc_html__('Elementor Modern Gallery Extension', 'elementor-modern-gallery-extension') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'elementor-modern-gallery-extension') . '</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    public function admin_notice_minimum_elementor_version()
    {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-modern-gallery-extension'),
            '<strong>' . esc_html__('Elementor Modern Gallery Extension', 'elementor-modern-gallery-extension') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'elementor-modern-gallery-extension') . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    public function admin_notice_minimum_php_version()
    {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-modern-gallery-extension'),
            '<strong>' . esc_html__('Elementor Modern Gallery Extension', 'elementor-modern-gallery-extension') . '</strong>',
            '<strong>' . esc_html__('PHP', 'elementor-modern-gallery-extension') . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    public function init()
    {
        $this->i18n();

        // Add Plugin actions
        add_action('elementor/widgets/widgets_registered', [$this, 'init_widgets']);

        // Register Widget Styles
        add_action('elementor/frontend/after_enqueue_styles', [$this, 'widget_styles']);
    }

    public function init_widgets()
    {
        // Include Widget files
        require_once __DIR__ . '/modern-gallery-widget.php';

        // Register widget
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor_Modern_Gallery_Widget());
    }

    public function widget_styles()
    {
        wp_register_style('modern-gallery-widget', plugins_url('modern-gallery-widget.css', __FILE__));
    }

    public function includes()
    {}
}

Elementor_Modern_Gallery_Extension::instance();
