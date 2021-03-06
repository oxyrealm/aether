<?php

namespace Oxyrealm\Modules\Sandbox;

use Oxyrealm\Aether\Admin;
use Oxyrealm\Aether\Assets;
use Oxyrealm\Aether\Utils\Blade;
use Oxyrealm\Aether\Utils\DB;
use Oxyrealm\Modules\ModuleAbstract;
use WP_Admin_Bar;

class Sandbox extends ModuleAbstract {

    public string $module_id = 'aether_m_sandbox';

    protected bool $active = false;

    protected array $post_metas = [
        'ct_builder_shortcodes',
        'ct_other_template',
        'ct_page_settings',
        'ct_parent_template',
        'ct_render_post_using',
        'ct_use_inner_content',
    ];

    protected array $options = [
        'ct_components_classes',
        'ct_custom_selectors',
        'ct_global_settings',
        'ct_style_folders',
        'ct_style_sets',
        'ct_style_sheets',
        'oxygen_vsb_comments_list_templates',
        'oxygen_vsb_easy_posts_templates',
        'oxygen_vsb_element_presets',
        'oxygen_vsb_global_colors',
        'oxygen_vsb_google_fonts_cache',
        'oxygen_vsb_latest_typekit_fonts',
        'oxygen_vsb_universal_css_cache',
    ];

    protected $secret;

    public function __construct() {
        $this->admin_settings();

        parent::__construct();

        $this->secret = get_option( "{$this->module_id}_secret" );
        $this->active = $this->is_active();
    }

    public function register() {
        Assets::register_style("{$this->module_id}-admin", AETHER_URL . '/modules/sandbox/assets/css/admin.css');

        add_action( 'init', [ $this, 'init' ] );
    }

    public function boot() {
        if ( ! $this->active ) {
            return;
        }
        wp_enqueue_style( "{$this->module_id}-admin" );


        foreach ( $this->options as $option ) {
            add_filter( "pre_option_{$option}", [ $this, 'pre_get_option' ], 0, 3 );
            add_filter( "pre_update_option_{$option}", [ $this, 'pre_update_option' ], 0, 3 );
        }
        
        add_filter( 'get_post_metadata', [ $this, 'get_post_metadata' ], 0, 4 );
        add_filter( 'update_post_metadata', [ $this, 'update_post_metadata' ], 0, 5 );
        add_filter( 'delete_post_metadata', [ $this, 'delete_post_metadata' ], 0, 5 );

        add_action( 'admin_bar_menu', [ $this, 'admin_bar_node' ], 100 );
    }

    public function init(): void {
        Assets::do_register();
    }

    private function is_active(): bool {
        if ( current_user_can( 'manage_options' ) || $this->validate_cookie() ) {
            return true;
        }

        $secret = $_GET[$this->module_id] ?? false;
        if ( $secret && $secret === $this->secret ) {
            $this->set_cookie();
        }

        return false;
    }

    private function validate_cookie(): bool {
        $cookie = $_COOKIE[$this->module_id] ?? false;
        return $cookie && $cookie === $this->secret;
    }

    private function set_cookie(): void {
        setcookie($this->module_id, $this->secret, time() + DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);
        
        if ( isset( $_SERVER['REQUEST_URI'] ) && wp_redirect( $_SERVER['REQUEST_URI'] ) ) {
            exit;
        }
    }

    private function unset_cookie(): void {
        setcookie($this->module_id, null, -1, COOKIEPATH, COOKIE_DOMAIN);
        
        if ( isset( $_SERVER['REQUEST_URI'] ) && wp_redirect( $_SERVER['REQUEST_URI'] ) ) {
            exit;
        }
    }

    public function set_secret(): void {
        update_option( "{$this->module_id}_secret", wp_generate_uuid4() );
    }

    public function unset_secret(): void {
        delete_option( "{$this->module_id}_secret" );
    }

    /**
     * 
     * @param mixed $pre_option 
     * @param string $option 
     * @param mixed $default 
     * @return mixed 
     */
    public function pre_get_option( $pre_option, string $option, $default ) {
        if ( $option === 'oxygen_vsb_universal_css_cache' ) {
            return 'false';
        }
        
        if ( DB::has( 'options', [ 'option_name' => "{$this->module_id}_{$option}", ] ) ) {
            $pre_option = get_option( "{$this->module_id}_{$option}", $default );
        }

        return $pre_option;
    }

    /**
     * 
     * @param mixed $value 
     * @param mixed $old_value 
     * @param string $option 
     * @return mixed 
     */
    public function pre_update_option( $value, $old_value, string $option ) {
        if ( $option === 'oxygen_vsb_universal_css_cache' ) {
            return $old_value;
        }

        update_option( "{$this->module_id}_{$option}", $value );
        return $old_value;
    }

    /**
     * 
     * @param null|bool $check 
     * @param int $object_id 
     * @param string $meta_key 
     * @param mixed $meta_value 
     * @param mixed $prev_value 
     * @return mixed 
     */
    public function update_post_metadata( $check, $object_id, $meta_key, $meta_value, $prev_value ) {
        return in_array( $meta_key, $this->post_metas ) 
            ? update_metadata( 'post', $object_id, "{$this->module_id}_{$meta_key}", $meta_value, $prev_value ) 
            : $check;
    }

    /**
     * 
     * @param null|bool $delete 
     * @param int $object_id 
     * @param string $meta_key 
     * @param mixed $meta_value 
     * @param bool $delete_all 
     * @return null|bool 
     */
    public function delete_post_metadata( $delete, $object_id, $meta_key, $meta_value, $delete_all ) {
        return in_array( $meta_key, $this->post_metas )
            ? delete_metadata( 'post', $object_id, "{$this->module_id}_{$meta_key}", $meta_value, $delete_all )
            : $delete;
    }

    /**
     * 
     * @param mixed $value 
     * @param int $object_id 
     * @param string $meta_key 
     * @param bool $single 
     * @return mixed 
     */
    public function get_post_metadata( $value, $object_id, $meta_key, $single ) {
        if ( in_array( $meta_key, $this->post_metas ) && metadata_exists( 'post', $object_id, "{$this->module_id}_{$meta_key}" ) ) {
            $value = get_metadata( 'post', $object_id, "{$this->module_id}_{$meta_key}", $single );
            if ( $single && is_array( $value ) ) {
                $value = [$value];
            }
        }
        return $value;
    }

    public function admin_bar_node( WP_Admin_Bar $wp_admin_bar )
    {
        $wp_admin_bar->add_node([
            'id'    => 'sandbox',
            'title' => 'Sandbox <span class="text-green-400">●</span>',
            'meta' => [
                'title' => 'Sandbox Mode - Aether'
            ]
        ]);
    }

    public function admin_settings() {
        Admin::$setting_tabs[] = [
            'id' => 'sandbox',
            'label' => 'Sandbox',
            'contents' => [
                [
                    'callback' => [ $this, 'admin_page' ],
                ]

            ]
        ];
    }

    public function admin_page() {
        $blade = Blade::blade();

        echo $blade->run('layouts.sample');
    }
}