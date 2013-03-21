<?php

/**
 * Database Utility. Provides a wrapper around PHP's PDO extension to 
 * simplify database querying.
 *
 * Classes
 * -------
 * Database
 * DatabaseQuery
 *
 * @author      Chris Hayes <chayes@okd.com, chris@chrishayes.ca>
 * @link        http://okd.com, http://chrishayes.ca
 * @copyright   (c) 2012 OKD, Chris Hayes
 */

// Alias the Database class for ease-of-use
class_alias('Database', 'DB');

/**
 * Initiates the PDO connection and creates a DatabaseQuery object.
 */
class Database
{
	/**
	 * Store a Database instance.
	 * 
	 * @var Database
	 */
	private static $instance;

	/**
	 * Store a PDO instance.
	 * 
	 * @var PDO
	 */
	private $connection;

	/**
	 * Methods allowed for fetching results.
	 * 
	 * @var array
	 */
	private $fetchMethods = array('query', 'row', 'field');

	/**
	 * Create a new PDO connection with the given credentials.
	 * 
	 * @param  array 	$credentials
	 * @return void
	 */
	private function __construct($credentials)
	{
		require_once('database/query.php');
		require_once('database/result.php');

		extract($credentials);

		if (($host AND $database AND $username) == '') {
			die('Database credentials required in config.php');
		}

		try {
			$this->connection = new PDO("mysql:host=$host;dbname=$database", $username, $password);
		} catch (PDOException $e) {
		    die("Database Error: " . $e->getMessage());
		}
	}

	/**
	 * Create a singleton instance of the Database class.
	 * @param  array 	$credentials
	 * @return Database
	 */
	public static function connect($credentials)
	{
		if ( ! self::$instance) {
			self::$instance = new Database($credentials);
		}

		return self::$instance;
	}

	/**
	 * Create a new Database_Result object.
	 *
	 * Called by an instantiated Database object.
	 * 
	 * @param  int 		$fetchMethod
	 * @param  string 	$query
	 * @param  array 	$bindings
	 * @param  int 		$fetchMode
	 * @return DatabaseQuery
	 */
	public function queryObject($fetchMethod, $query, $bindings = null, $fetchMode = null)
	{
		$databaseQuery = new DatabaseQuery($this->connection, $query, $bindings, $fetchMethod, $fetchMode);

		return $databaseQuery->execute();
	}

	/**
	 * Create a new Database_Result object.
	 *
	 * Called statically. If a PDO connection has not been previously
	 * made it can include the credentials, and a connection will be
	 * made on the fly.
	 * 
	 * @param  int 		$fetchMethod
	 * @param  string 	$query
	 * @param  array 	$bindings
	 * @param  int 		$fetchMode
	 * @param  array 	$credentials
	 * @return DatabaseQuery
	 */
	public static function queryStatic($fetchMethod, $query, $bindings = null, $fetchMode = null)
	{
		$databaseQuery = new DatabaseQuery(self::$instance->connection, $query, $bindings, $fetchMethod, $fetchMode);

		return $databaseQuery->execute();
	}

	/**
	 * Magic method to capture undefined instantiated object method calls.
	 * 
	 * @param  string 	$name
	 * @param  array 	$arguments
	 * @return DatabaseQuery
	 */
	public function __call($name, $arguments)
	{
		self::connect(Config::get('database'));

		// Add the name of the function called as the first argument (fetchMethod)
		array_unshift($arguments, $name);

		if (in_array($name, $this->fetchMethods)) {
			return call_user_func_array(array($this, 'queryObject'), $arguments);
		}
	}

	/**
	 * Magic method to capture undefined static method calls.
	 * 
	 * @param  string 	$name
	 * @param  array 	$arguments
	 * @return DatabaseQuery
	 */
	public static function __callStatic($name, $arguments)
	{
		self::connect(Config::get('database'));
		
		// Add the name of the function called as the first argument (fetchMethod)
		array_unshift($arguments, $name);

		if (in_array($name, self::$instance->fetchMethods)) {
			return call_user_func_array(array('Database', 'queryStatic'), $arguments);
		}
	}
}

