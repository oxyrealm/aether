<?php

namespace Oxyrealm\Aether\Utils;

use Exception;
use Medoo\Medoo;
use Medoo\Raw;
use PDOStatement;
use wpdb;

/**
 * @method static bool|array select( mixed $table, mixed $join, mixed $columns = null, mixed $where = null )
 * @method static PDOStatement|bool insert( mixed $table, mixed $datas )
 * @method static PDOStatement|bool update( mixed $table, mixed $data, mixed $where = null )
 * @method static PDOStatement|bool delete( mixed $table, mixed $where )
 * @method static PDOStatement|bool replace( mixed $table, mixed $columns, mixed $where = null )
 * @method static mixed get( mixed $table, mixed $join = null, mixed $columns = null, mixed $where = null )
 * @method static bool has( mixed $table, mixed $join, mixed $where = null )
 * @method static bool|array rand( mixed $table, mixed $join = null, mixed $columns = null, mixed $where = null )
 * @method static mixed count( mixed $table, mixed $join = null, mixed $column = null, mixed $where = null )
 * @method static mixed max( mixed $table, mixed $join, mixed $column = null, mixed $where = null )
 * @method static mixed min( mixed $table, mixed $join, mixed $column = null, mixed $where = null )
 * @method static mixed avg( mixed $table, mixed $join, mixed $column = null, mixed $where = null )
 * @method static mixed sum( mixed $table, mixed $join, mixed $column = null, mixed $where = null )
 * @method static mixed id()
 * @method static mixed action( mixed $actions )
 * @method static PDOStatement|bool create( mixed $table, mixed $columns, mixed $options = null )
 * @method static PDOStatement|bool drop( mixed $table )
 * @method static PDOStatement|bool query( mixed $query, array $map = [] )
 * @method static string|bool quote( string $string )
 * @method static Raw raw( mixed $string, array $map = [] )
 * @method static Medoo debug()
 * @method static mixed error()
 * @method static array log()
 * @method static array info()
 * @method static string|string[] last()
 *
 * @see \Medoo\Medoo
 */
class DB {
	private static $instances = [];

	private static $medoo;

	private static $wpdb;

	protected function __construct() {
		self::$wpdb = self::wpdb();

		self::$medoo = new Medoo( [
			'type'     => 'mysql',
			'host'     => self::$wpdb->dbhost,
			'database' => self::$wpdb->dbname,
			'username' => self::$wpdb->dbuser,
			'password' => self::$wpdb->dbpassword,
			'prefix'   => self::$wpdb->prefix,
		] );
	}

	public static function getInstance(): DB {
		$cls = static::class;
		if ( ! isset( self::$instances[ $cls ] ) ) {
			self::$instances[ $cls ] = new static();
		}

		return self::$instances[ $cls ];
	}

	public static function __callStatic( string $method, array $args ) {
		return self::getInstance()::$medoo->{$method}( ...$args );
	}

	public function __get( string $name ) {
		return self::getInstance()::$medoo->{$name};
	}

	public function __wakeup() {
		throw new Exception( "Cannot unserialize a singleton." );
	}

	protected function __clone() {
	}

	public static function wpdb(): wpdb {
		/** @var wpdb $wpdb */
		global $wpdb;

		return $wpdb;
	}
}
