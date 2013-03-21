<?php

/**
 * Cookie Utility. Provides a wrapper for PHP cookie handling that
 * provides session encryption.
 *
 * @author      Chris Hayes <chayes@okd.com, chris@chrishayes.ca>
 * @link        http://okd.com, http://chrishayes.ca
 * @copyright   (c) 2012 OKD, Chris Hayes
 */
class Cookie
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
	private $cryptKey = 'okdcookieencryptionmanagement';
	
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
		// Load the Cryptography class
		require_once(__DIR__.'/../vendor/crypt/AES.php');
		
		// Create a new AES cryptography object
		$this->aes = new Crypt_AES(); 
		
		// Set the encryption key
		$this->setKey($this->cryptKey);
	}

	/**
	 * Return an instance of the Cookie class.
	 * 
	 * @return Cookie
	 */
	public function getInstance()
	{
		if ( ! isset(self::$instance)) {
			self::$instance = new Cookie();
		}

		return self::$instance;
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
	 * Encrypt the data that is to be stored in the cookie and then create the cookie.
	 * 
	 * @param  string 	$key
	 * @param  mixed 	$value
	 * @return void
	 */
	public function setCookie($key, $value)
	{		
		$_COOKIE[$key] = $this->aes->encrypt(serialize($value));
	}
	
	/**
	 * Decrypt the data from the cookie and return it.
	 * 
	 * @param  string 	$key
	 * @return mixed
	 */
	public function getCookie($key)
	{
		return unserialize($this->aes->decrypt($_COOKIE[$key]));
	}

	/**
	 * Delete a cookie variable.
	 * 
	 * @param  string 	$key
	 * @return void
	 */
	public function deleteCookie($key)
	{
		unset($_COOKIE[$key]);	
	}

	/**
	 * Allow static interface for setting, getting, and
	 * deleting cookies.
	 * 
	 * @param  string 	$name
	 * @param  array 	$arguments
	 * @return mixed
	 */
	public static function __callStatic($name, $arguments)
	{
		$cookie = self::getInstance();

		switch($name) {
			case 'set':
				list($key, $value) = $arguments;
				$cookie->setCookie($key, $value);
				break;
			case 'get':
				list($key) = $arguments;
				return $cookie->getCookie($key);
			case 'delete':
				list($key) = $arguments;
				$cookie->deleteCookie($key);
				break;
		}
	}
}