<?php

namespace Oxyrealm\Aether;

use Oxyrealm\Aether\Utils\Oxygen;

class Admin
{
    public static $slug = 'aether';

    public function __construct() {
        if (Oxygen::can()) {
            add_action('admin_menu', [$this, 'admin_menu']);
        }
    }

    public function admin_menu() {
        $capability = 'manage_options';

        if (current_user_can($capability)) {

            $hook = add_menu_page(
                __('Aether', 'aether'),
                __('Aether', 'aether'),
                $capability,
                self::$slug,
                [
                    $this,
                    'plugin_page'
                ],
                'data:image/svg+xml;base64,' . base64_encode(@file_get_contents(AETHER_PATH . '/public/img/icon.svg')),
                2
            );

            add_action('load-' . $hook, [$this, 'init_hooks']);
        }
    }

    public function init_hooks(): void {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    public function enqueue_scripts(): void {
        wp_enqueue_style( "aether-admin-main" );
        wp_enqueue_script( "aether-admin-main" );
    }

    public function plugin_page(): void {
        $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'main';
?>
        <h2>Aether Dashboard</h2>
        <h2 class="nav-tab-wrapper">
            <a href="<?php echo add_query_arg( [
				'page' => self::$slug,
				'tab' => 'main',
			], admin_url( 'admin.php' ) ); ?>" class="nav-tab <?php echo $active_tab == 'main' ? 'nav-tab-active' : ''; ?>">Main</a>
            <a href="<?php echo add_query_arg( [
				'page' => self::$slug,
				'tab' => 'faq',
			], admin_url( 'admin.php' ) ); ?>" class="nav-tab <?php echo $active_tab == 'faq' ? 'nav-tab-active' : ''; ?>">FAQ</a>
        </h2>
<?php
        switch ($active_tab) {
            case 'faq':
                $this->faq_tab();
                break;
            case 'main':
            default:
                echo '<div id="aether-main"></div>';
                break;
        }
    }

	public function faq_tab(): void {
		?>

		<h3>What is the Aether plugin?</h3>
		<p>The backbone and framework for all <a href="https://oxyrealm.com">OxyRealm</a>'s plugins. Aether plugin contains all dependencies used by OxyRealm's plugins, mean the plugins will be lean and more manageable to maintenance for us..</p>

		<h3>Why the Aether plugin installed when I was using OxyRealm's plugin?</h3>
        <p>OxyRealm's plugin will automatically download and activate the plugin from <a href="https://wordpress.org/plugins/aether">https://wordpress.org/plugins/aether</a>. Once the Aether plugin is activated, OxyRealm's plugin will be able to run.</p>

        <h3>Can I deactivated and deleted the Aether plugin?</h3>
        <p>Please don't deactivate and delete the Aether plugin. All active OxyRealm's plugin will fall and crash.</p>

        <h3>I enjoy and want to support OxyRealm's free plugins!</h3>
        <p>Thank you, we appreciate your support. You can use this link to support and buy the coffee for the developer <a href="https://go.oxyrealm.com/donate">https://go.oxyrealm.com/donate</a>.</p>

		<?php
	}
}
