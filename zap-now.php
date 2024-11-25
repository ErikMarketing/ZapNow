<?php
/**
 * Plugin Name: ⚡ Zap Now
 * Plugin URI: https://erik.marketing
 * Description: Lightweight WordPress cache clearing solution. One-click clearing of WordPress core caches, 
 * including object cache, transients, theme cache, update caches, menu cache, and rewrite rules. 
 * Adds a convenient Zap Now button to your admin bar for instant cache clearing.
 * Version: 1.0.0
 * Requires at least: 5.0
 * Requires PHP: 7.2
 * Author: ErikMarketing
 * Author URI: https://erik.marketing
 * License: GPL v3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: zap-now
 *
 * @package ZapNow
 * @author ErikMarketing
 * @link https://erik.marketing
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

class Zap_Now {
    /**
     * Initialize the plugin
     */
    public function __construct() {
        add_action('admin_bar_menu', array($this, 'add_zap_button'), 999);
        add_action('admin_footer', array($this, 'add_zap_script'));
        add_action('wp_ajax_zap_cache', array($this, 'zap_cache_callback'));
    }

    /**
     * Add zap button to admin bar
     */
    public function add_zap_button($wp_admin_bar) {
        if (!current_user_can('manage_options')) {
            return;
        }

        $wp_admin_bar->add_node(array(
            'id'    => 'zap-now',
            'title' => __('Zap Now!', 'zap-now'),
            'href'  => '#',
            'meta'  => array('class' => 'zap-now')
        ));
    }

    /**
     * Add JavaScript for cache zapping
     */
    public function add_zap_script() {
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <script>
        jQuery(document).ready(function($) {
            $('#wp-admin-bar-zap-now').click(function(e) {
                e.preventDefault();
                
                if (!confirm('<?php echo esc_js(__('Ready to zap all WordPress caches?', 'zap-now')); ?>')) {
                    return;
                }

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'zap_cache',
                        nonce: '<?php echo wp_create_nonce('zap_cache_nonce'); ?>'
                    },
                    beforeSend: function() {
                        $('#wp-admin-bar-zap-now .ab-item').text('<?php echo esc_js(__('Zapping...', 'zap-now')); ?>');
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.data.message);
                        } else {
                            alert('Error: ' + response.data);
                        }
                    },
                    error: function() {
                        alert('<?php echo esc_js(__('Oops! Something went wrong while zapping.', 'zap-now')); ?>');
                    },
                    complete: function() {
                        $('#wp-admin-bar-zap-now .ab-item').text('<?php echo esc_js(__('Zap Now!', 'zap-now')); ?>');
                    }
                });
            });
        });
        </script>
        <?php
    }

    /**
     * Clear all WordPress transients
     */
    private function clear_all_transients() {
        global $wpdb;
        
        // Delete all transients from options table
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '%_transient_%'");
        
        // Clear memory cached transients
        wp_cache_flush();
    }

    /**
     * Clear theme customizer cache
     */
    private function clear_theme_cache() {
        // Clear theme mods
        remove_theme_mods();
        
        // Clear customizer cache
        wp_cache_delete('customizer', 'options');
        
        // Clear any cached CSS
        delete_option('theme_mods_' . get_stylesheet());
    }

    /**
     * Clear plugin update cache
     */
    private function clear_updates_cache() {
        delete_site_transient('update_plugins');
        delete_site_transient('update_themes');
        delete_site_transient('update_core');
        wp_clean_plugins_cache();
        wp_clean_themes_cache();
    }

    /**
     * Clear menu cache
     */
    private function clear_menu_cache() {
        wp_cache_delete('wp_get_nav_menu_items', 'nav_menu_items');
        delete_transient('wp_get_nav_menu_items');
    }

    /**
     * Handle cache zapping
     */
    public function zap_cache_callback() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'zap_cache_nonce')) {
            wp_send_json_error(__('Security check failed.', 'zap-now'));
        }

        // Verify user capabilities
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('You do not have permission to zap cache.', 'zap-now'));
        }

        $cleared = array();

        // Clear object cache
        wp_cache_flush();
        $cleared[] = 'object cache';

        // Clear all transients
        $this->clear_all_transients();
        $cleared[] = 'transients';

        // Clear theme cache
        $this->clear_theme_cache();
        $cleared[] = 'theme cache';

        // Clear update caches
        $this->clear_updates_cache();
        $cleared[] = 'updates cache';

        // Clear menu cache
        $this->clear_menu_cache();
        $cleared[] = 'menu cache';

        // Flush rewrite rules
        flush_rewrite_rules();
        $cleared[] = 'rewrite rules';

        $message = sprintf(
            __('Cache zapped successfully! ⚡ Cleared: %s', 'zap-now'),
            implode(', ', $cleared)
        );

        wp_send_json_success(array(
            'message' => $message,
            'cleared' => $cleared
        ));
    }
}

// Initialize the plugin
new Zap_Now();
