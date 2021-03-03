<?php

namespace Oxyrealm\Aether\Utils;

class Migration {
	public static function migrate( $currentDatabaseVersion, $newDatabaseVersion ): void {
		$regExFileName = '/(\d{4})_(\d{2})_(\d{2})_(\d{6})_(.*?)_(.*?)\.php/';

		foreach ( glob( AETHER_MIGRATION_PATH . '*.php' ) as $fileName ) {
			if ( preg_match( $regExFileName, basename( $fileName ), $match ) ) {
				$fileBasename  = $match[0];
				$fileDateYear  = $match[1];
				$fileDateMonth = $match[2];
				$fileDateDay   = $match[3];
				$fileTimestamp = $match[4];
				$fileVersion   = $match[5];
				$fileName      = $match[6];

				if ( intval( $fileVersion ) <= intval( $newDatabaseVersion ) && intval( $fileVersion ) > intval( $currentDatabaseVersion ) ) {
					$className = self::camelize( $fileName );
					call_user_func( [ "\\Oxyrealm\\Aether\\Database\\Migrations\\{$className}", 'up' ] );
				}
			}
		}
	}

	public static function camelize( $input, $separator = '_' ) {
		return str_replace( $separator, '', ucwords( $input, $separator ) );
	}

	public static function rollback(): void {
	}
}
