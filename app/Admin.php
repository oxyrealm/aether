<?php

namespace Oxyrealm\Aether;

use Oxyrealm\Aether\Utils\Oxygen;

class Admin {
	public static $slug = 'aether';

	public function __construct() {
		if ( Oxygen::can() ) {
			add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		}
	}

	public function admin_menu() {
		$capability = 'manage_options';

		if ( current_user_can( $capability ) ) {

			$hook = add_menu_page(
				__( 'Aether', 'aether' ),
				__( 'Aether', 'aether' ),
				$capability,
				self::$slug,
				[
					$this,
					'plugin_page'
				],
				'data:image/svg+xml;base64,' . base64_encode( @file_get_contents( AETHER_PATH . '/public/img/icon.svg' ) ),
			//2
			);

			add_action( 'load-' . $hook, [ $this, 'init_hooks' ] );
		}
	}

	public function init_hooks(): void {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	public function enqueue_scripts(): void {
		wp_enqueue_style( "aether-admin-main" );
		wp_enqueue_script( "aether-admin-main" );
	}

	public function plugin_page(): void {
		$active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'faq';
		?>
        <h2>Aether Dashboard</h2>
        <h2 class="nav-tab-wrapper">
            <a href="<?php echo add_query_arg( [
				'page' => self::$slug,
				'tab'  => 'main',
			], admin_url( 'admin.php' ) ); ?>"
               class="nav-tab <?php echo $active_tab == 'main' ? 'nav-tab-active' : ''; ?>">Main</a>
            <a href="<?php echo add_query_arg( [
				'page' => self::$slug,
				'tab'  => 'faq',
			], admin_url( 'admin.php' ) ); ?>"
               class="nav-tab <?php echo $active_tab == 'faq' ? 'nav-tab-active' : ''; ?>">FAQ</a>
        </h2>
		<?php
		switch ( $active_tab ) {
			case 'main':
				echo '<div id="aether-main"></div>';
				break;
			case 'faq':
			default:
				$this->faq_tab();
				break;
		}
	}

	public function faq_tab(): void {
		?>

        <h3>What is the Aether plugin?</h3>
        <p>The backbone and framework for all <a href="https://oxyrealm.com">dPlugins</a>'s plugins. Aether plugin
            contains all dependencies used by our other plugins, mean our other plugins will be lean and more manageable to
            maintenance.</p>

        <h3>Why the Aether plugin installed when I was using dPlugins's plugin?</h3>
        <p>dPlugins's plugin will automatically download and activate the plugin from <a
			href="https://wordpress.org/plugins/aether">https://wordpress.org/plugins/aether</a>. Once the
            Aether plugin is activated, dPlugins's plugin will be able to run.</p>

        <h3>Can I deactivated and deleted the Aether plugin?</h3>
        <p>We are advising to don't deactivate and delete the Aether plugin. 
			All our other active plugins heavily rely on the Aether plugin.
			Deactivating the Aether plugin can result in error and crash on all 
			our other active plugins.</p>

        <h3>I enjoy and want to support All our free plugins!</h3>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" class="donation">
			<table class="form-table">
				<tbody>
				<tr>
					<th> Plugin Support: </th>
					<td>
					<div>
						<label>
							<p>
								Thank you, we appreciate your support. 
								It has required a great deal of time and effort to develop and
								you can help support this development by <strong>buy the coffee for the developer</strong>.
							</p>
						</label>

						<input type="hidden" name="cmd" value="_xclick" />
						<input type="hidden" name="business" value="suabahasa@gmail.com" />
						<input type="hidden" name="item_name" value="Aether (WordPress Plugin)" />
						<input type="hidden" name="buyer_credit_promo_code" value="" />
						<input type="hidden" name="buyer_credit_product_category" value="" />
						<input type="hidden" name="buyer_credit_shipping_method" value="" />
						<input type="hidden" name="buyer_credit_user_address_change" value="" />
						<input type="hidden" name="no_shipping" value="1" />
						<input type="hidden" name="return" value="<?php echo add_query_arg( [ 'page' => self::$slug, 'tab'  => 'main', ], admin_url( 'admin.php' ) ); ?>" />
						<input type="hidden" name="no_note" value="1" />
						<input type="hidden" name="currency_code" value="USD" />
						<input type="hidden" name="tax" value="0" />
						<input type="hidden" name="lc" value="US" />
						<input type="hidden" name="bn" value="PP-DonationsBF" />

						<div class="donation-amount">
						$ <input type="number" name="amount" min="5" value="20"> <span> 😀 </span>
						<input type="submit" class="button-primary" value="Support 💰">
						</div>
					</div>
					</td>
				</tr>
				</tbody>
			</table>
		</form>
		<style>.donation{max-width:800px}.donation .donation-amount{float:left;margin-top:10px;max-width:500px}.donation .donation-amount span{font-size:28px;margin-top:4px;vertical-align:bottom}.donation .donation-amount img{width:24px!important;margin-bottom:-5px!important}.donation .donation-amount::after{content:"";display:block;clear:both}.donation input[type=number]{width:60px;margin-left:10px}.donation td,.donation th{padding:0;margin-bottom:0}.donation input[type=submit]{margin-left:10px}</style>

		<?php
	}
}
