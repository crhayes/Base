<?php
/**
 * Cookie session driver. Allows sessions to be stored in cookies.
 * 
 * @package     Base PHP Framework
 * @author      Chris Hayes <chris@chrishayes.ca>, <chayes@okd.com>
 * @copyright   (c) 2012-2013 Chris Hayes, OKD
 * @license     http://opensource.org/licenses/MIT
 */
class SessionCookie extends Session implements SessionDriver
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
		setcookie($key, Hash::make(serialize($data)), null, '/');
	}
	
	/**
	 * Decrypt the data from the session and return it.
	 *
	 * @param  string 	$key
	 * @param  string 	$key
	 * @param  boolean	$checkIfFlashed
	 * @return mixed
	 */
	public function get($key)
	{
		return (isset($_COOKIE[$key])) ? unserialize(Hash::undo($_COOKIE[$key])) : false;
	}
	
	/**
	 * Delete a cookie session.
	 * 
	 * @param  string 	$key
	 * @return void
	 */
	public function forget($key)
	{
		setcookie($key, false, time()-3600, '/');		
	}

	/**
	 * Return all cookie sessions.
	 * 
	 * @return array
	 */
	public function sessions()
	{
		return $_COOKIE;
	}
}