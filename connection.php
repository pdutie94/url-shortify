<?php
define( 'DB_SERVER', 'localhost' );
define( 'DB_USERNAME', 'root' );
define( 'DB_PASSWORD', 'password' );
define( 'DB_NAME', 'url-shortify' );

class DB {
	private static $instance = null;
	public static function getInstance() {
		if ( ! isset( self::$instance ) ) {
			try {
				self::$instance = new PDO( 'mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME, DB_USERNAME, DB_PASSWORD );
				self::$instance->exec( "SET NAMES 'utf8'" );
			} catch ( PDOException $ex ) {
				die( $ex->getMessage() );
			}
		}
		return self::$instance;
	}
	public static function query( $query, $params = array() ) {
		$stmt = self::getInstance()->prepare( $query );
		$data = $stmt->execute( $params );
		return $data;
	}

	public static function fetch( $query, $params = array() ) {
		$stmt = self::getInstance()->prepare( $query );
		$data = $stmt->execute( $params );
		$data = $stmt->fetch();
		return $data;
	}

	public static function fetchAll( $query, $params = array() ) {
		$stmt = self::getInstance()->prepare( $query );
		$data = $stmt->execute( $params );
		$data = $stmt->fetchAll();
		return $data;
	}
}
