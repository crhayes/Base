<?php
/**
 * Session Utility. Provides a wrapper for PHP session handling that
 * provides session encryption as well as several different session
 * storage drivers (native, cookie, database).
 *
 * This class handles executes a query and returns a DatabaseQuery result.
 * 
 * @package     Base PHP Framework
 * @author      Chris Hayes <chris@chrishayes.ca>, <chayes@okd.com>
 * @copyright   (c) 2012-2013 Chris Hayes, OKD
 * @license     http://opensource.org/licenses/MIT
 */
class Session
{	
	/**
	 * Store an instance of the Session class.
	 * 
	 * @var Session
	 */
	private static $instance;
	
	/**
	 * Stored instance of the phpSec AES encruption library.
	 * 	
	 * @var Crypt_AES
	 */
	public $aes;
	
	/**
	 * Create a new instance of the Session class.
	 *
	 * The phpSec AES cryptography library is instantiated in order
	 * to allow us to encrypt the session data for extra security.
	 *
	 * @return void
	 */
	private function __construct()
	{
		session_start();
	}
	
	/**
	 * Factory method to create a new session instance.
	 * 
	 * @param  string 	$driver
	 * @return object
	 */
	public static function start($driver)
	{
		if ( ! isset(self::$instance)) {
			self::$instance = self::load($driver);
		}

		return self::$instance;
	}

	/**
	 * Load a session driver.
	 * 
	 * @param  string 	$driver
	 * @return object
	 */
	public static function load($driver)
	{
		require_once('session/driver.php');
		
		switch ($driver) {
			case 'cookie':
				require_once('session/drivers/cookie.php');
				return new SessionCookie();
			case 'database':
				require_once('session/drivers/database.php');
				return new SessionDatabase();
			default:
				require_once('session/drivers/native.php');
				return new SessionNative();
		}
	}

	/**
	 * Allow static interface for setting, getting, and
	 * deleting sessions.
	 * 
	 * @param  string 	$name
	 * @param  array 	$arguments
	 * @return mixed
	 */
	public static function __callStatic($name, $arguments)
	{
		return call_user_func_array(array(self::$instance, $name), $arguments);
	}
}