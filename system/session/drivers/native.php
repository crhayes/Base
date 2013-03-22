<?php
/**
 * Native session driver.
 * 
 * @package     Base PHP Framework
 * @author      Chris Hayes <chris@chrishayes.ca>, <chayes@okd.com>
 * @copyright   (c) 2012-2013 Chris Hayes, OKD
 * @license     http://opensource.org/licenses/MIT
 */
class SessionNative extends Session implements SessionDriver
{	
	/**
	 * Encrypt the data that is to be stored in the session and then create the session.
	 * 
	 * @param  string 	$key
	 * @param  mixed 	$value
	 * @param  boolean	$flashed
	 * @return void
	 */
	public function set($key, $data)
	{
		$_SESSION[$key] = Hash::make(serialize($data));
	}
	
	/**
	 * Decrypt the data from the session and return it.
	 *
	 * @param  string 	$key
	 * @param  string 	$key
	 * @param  boolean	$checkIfFlashed
	 * @return mixed
	 */
	public function get($key, $checkIfFlashed = false)
	{
		return (isset($_SESSION[$key])) ? unserialize(Hash::undo($_SESSION[$key])) : false;
	}
	
	/**
	 * Delete a session variable.
	 * 
	 * @param  string 	$key
	 * @return void
	 */
	public function forget($key)
	{
		unset($_SESSION[$key]);	
	}

	/**
	 * Return all native sessions.
	 * 
	 * @return array
	 */
	public function sessions()
	{
		return $_SESSION;
	}
}