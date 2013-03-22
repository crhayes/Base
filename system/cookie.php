<?php
/**
 * Cookie Utility. Provides a wrapper for PHP cookie handling that
 * provides session encryption.
 * 
 * @package     Base PHP Framework
 * @author      Chris Hayes <chris@chrishayes.ca>, <chayes@okd.com>
 * @copyright   (c) 2012-2013 Chris Hayes, OKD
 * @license     http://opensource.org/licenses/MIT
 */
class Cookie
{	
	/**
	 * Encrypt the data that is to be stored in the cookie and then create the cookie.
	 * 
	 * @param  string 	$key
	 * @param  mixed 	$value
	 * @return void
	 */
	public function set($key, $value)
	{		
		setcookie($key, Hash::make(serialize($value)));
	}
	
	/**
	 * Decrypt the data from the cookie and return it.
	 * 
	 * @param  string 	$key
	 * @return mixed
	 */
	public function get($key)
	{
		return (isset($_COOKIE[$key])) ? unserialize(Hash::undo($_COOKIE[$key])) : false;
	}

	/**
	 * Delete a cookie variable.
	 * 
	 * @param  string 	$key
	 * @return void
	 */
	public function forget($key)
	{
		setcookie($key, null, time()-3600);
	}
}