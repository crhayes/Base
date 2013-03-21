<?php

/**
 * Session Utility. Provides a wrapper for PHP session handling that
 * provides session encryption as well as several different session
 * storage drivers (native, cookie, database);
 *
 * @author      Chris Hayes <chayes@okd.com, chris@chrishayes.ca>
 * @link        http://okd.com, http://chrishayes.ca
 * @copyright   (c) 2012 OKD, Chris Hayes
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
	 * Key to be used by the encryption library.
	 * 
	 * @var string
	 */
	private $cryptKey = 'okdsessionencryptionmanagement';
	
	/**
	 * Stored instance of the phpSec AES encruption library.
	 * 	
	 * @var Crypt_AES
	 */
	public $aes;

	protected static $flashedSessions = array();
	
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
	public static function start($driver = 'native')
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
				require_once('session/drivers/databse.php');
				return new SessionDatabase();
			default:
				require_once('session/drivers/native.php');
				return new SessionNative();
		}
	}
	
	/**
	 * Set the encryption key for the phpSec library.
	 * 
	 * @param string $key
	 */
	public function setKey($key)
	{	
		$this->aes->setKey($key);
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
		switch($name) {
			case 'set':
				list($key, $value) = $arguments;
				self::$instance->set($key, $value);
				break;
			case 'flash':
				list($key, $value) = $arguments;
				self::$instance->flash($key, $value);
				break;
			case 'get':
				list($key) = $arguments;
				return self::$instance->get($key);
			case 'forget':
				list($key) = $arguments;
				self::$instance->unset($key);
				break;
			case 'sweep':
				self::$instance->sweep();
				break;
		}
	}
}