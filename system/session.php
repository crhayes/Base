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
	 * @return SessionDriver
	 */
	private static function load($driver)
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
	 * Set encrypted session data with the loaded session driver.
	 * 
	 * @param string  	$key
	 * @param mixed  	$value
	 * @param boolean 	$flashed
	 * @return void
	 */
	private function _set($key, $value, $flashed = false)
	{
		$data = array(
			'value' => $value,
			'flashed' => $flashed,
			'lastActivity' => strtotime('now')
		);

		$this->set($key, $data);
	}

	/**
	 * 'Flash' encrypted session data with the loaded session driver. The value
	 * will expire after a single request.
	 * 
	 * @param string  	$key
	 * @param mixed  	$value
	 * @param boolean 	$flashed
	 * @return void
	 */
	private function _flash($key, $value, $flashed = true)
	{
		$this->_set($key, $value, true);
	}
	
	/**
	 * Get decrypted session data with the loaded session driver.
	 * 
	 * @param  string  	$key
	 * @param  boolean 	$checkIfFlashed
	 * @return mixed
	 */
	private function _get($key, $checkIfFlashed = false)
	{
		// Get the session value if it is set
		$session = (Arr::get($key, $_SESSION)) ? $this->get($key) : null;

		if (is_array($session) && extract($session) && isset($value) && isset($flashed) && isset($lastActivity)) {
			// Return the 'flashed' status if that's what we're after
			if ($checkIfFlashed) {
				return $flashed;
			}

			// The session is valid
			if (($lastActivity + Config::get('session.lifetime')) > strtotime('now')) {
				return $value;
			// Session has expired
			} else {
				$this->forget($key);
			}
		}

		return false;
	}

	/**
	 * Delete session data with the loaded session driver.
	 * 
	 * @param  string $key
	 * @return void
	 */
	private function _forget($key)
	{
		$this->forget($key);
	}

	/**
	 * Clean up any expired sessions with loaded session driver.
	 * 
	 * @return void
	 */
	private function _sweep()
	{
		foreach ($this->sessions() as $key => $data) {
			if ($this->_get($key, true) == true) {
				$this->forget($key);
			}
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
		return call_user_func_array(array(self::$instance, '_'.$name), $arguments);
	}
}