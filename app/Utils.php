<?php

namespace Oxyrealm\Aether;

class Utils {
	public static function is_request( string $type ): bool {
		switch ( $type ) {
			case 'admin':
				return is_admin();
			case 'ajax':
				return defined( 'DOING_AJAX' );
			case 'rest':
				return defined( 'REST_REQUEST' );
			case 'cron':
				return defined( 'DOING_CRON' );
			case 'frontend':
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
			default:
				return false;
				break;
		}
	}

	public static function is_oxygen_editor(): bool {
		return defined( 'SHOW_CT_BUILDER' ) && ! defined( 'OXYGEN_IFRAME' );
	}

	public static function is_oxygen_iframe(): bool {
		return defined( 'SHOW_CT_BUILDER' ) && defined( 'OXYGEN_IFRAME' );
	}

	public static function ltrim( string $string, string $prefix ): string {
		return strpos( $string, $prefix ) === 0
			? substr( $string, strlen( $prefix ) )
			: $string;
	}

	public static function localization( $domain, $plugin_file ): void {
		load_plugin_textdomain( $domain, false, dirname( plugin_basename( $plugin_file ) ) . '/languages/' );
	}

	public static function plugin_action_links( $links, $setting_page_slug ) {
		$plugin_shortcuts = [
			'<a href="' . add_query_arg( [ 'page' => $setting_page_slug ], admin_url( 'admin.php' ) ) . '">Settings</a>',
			'<a href="https://go.oxyrealm.com/donate" target="_blank" style="color:#3db634;">Buy me a coffee ☕</a>'
		];

		return array_merge( $links, $plugin_shortcuts );
	}
}