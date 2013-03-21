<?php
/**
 * Wrapper around the PHP Crypt AES library to easily allow the creation
 * of hashes (without depending on PHP extensions).
 * 
 * @package     Base PHP Framework
 * @author      Chris Hayes <chris@chrishayes.ca>, <chayes@okd.com>
 * @copyright   (c) 2012-2013 Chris Hayes, OKD
 * @license     http://opensource.org/licenses/MIT
 */
class Hash
{
	/**
	 * Store an instance of the Hash class.
	 * 
	 * @var Hash
	 */
	private static $instance;

	/**
	 * Store an instance of the crypt library.
	 * @var Crypt_AES
	 */
	private $crpyt;

	/**
	 * Load the encryption library and set the key.
	 */
	private function __construct()
	{		
		// Load the Cryptography class
		require_once('hash/crypt/AES.php');
		
		// Create a new AES cryptography object
		$this->crypt = new Crypt_AES(); 
		
		// Set the encryption key
		$this->crypt->setKey(Config::get('application.key'));
	}

	/**
	 * Get a singleton instance of the Hash class.
	 * 
	 * @return Hash
	 */	
	private static function getInstance()
	{
		if ( ! isset(self::$instance)) {
			self::$instance = new Hash();
		}

		return self::$instance;
	}

	/**
	 * Return some encrypted data.
	 * @param  mixed 	$data
	 * @return string
	 */
	public static function make($data)
	{
		return self::getInstance()->crypt->encrypt($data);
	}

	/**
	 * Return some decrypted data.
	 * 
	 * @param  string 	$data
	 * @return mixed
	 */
	public static function undo($data)
	{
		return self::getInstance()->crypt->decrypt($data);
	}
}