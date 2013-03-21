<?php

class Hash
{
	private static $instance;

	private $crpyt;

	private function __construct()
	{		
		// Load the Cryptography class
		require_once('hash/crypt/AES.php');
		
		// Create a new AES cryptography object
		$this->crypt = new Crypt_AES(); 
		
		// Set the encryption key
		$this->crypt->setKey(Config::get('application.key'));
	}

	private static function getInstance()
	{
		if ( ! isset(self::$instance)) {
			self::$instance = new Hash();
		}

		return self::$instance;
	}

	public static function make($data)
	{
		return self::getInstance()->crypt->encrypt($data);
	}

	public static function undo($data)
	{
		return self::getInstance()->crypt->decrypt($data);
	}
}