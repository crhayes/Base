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
	public function set($key, $value, $flashed = false)
	{
		$data = array(
			'value' => $value,
			'flashed' => $flashed,
			'lastActivity' => strtotime('now')
		);

		$_SESSION[$key] = Hash::make(serialize($data));
	}

	/**
	 * Create an encrypted session that expires after one page request.
	 * 
	 * @param  string 	$key
	 * @param  mixed 	$value
	 * @return void
	 */
	public function flash($key, $value, $flashed = true)
	{
		$this->set($key, $value, $flashed);
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
		if (isset($_SESSION[$key])) {
			extract(unserialize(Hash::undo($_SESSION[$key])));
			
			// Just want to know if the session is a flashed one
			if ($checkIfFlashed) {
				return $flashed;
			// Otherwise we want the session data
			} else {
				$lifetime = Config::get('session.lifetime');

				// The session is valid
				if (($lastActivity + $lifetime) > strtotime('now')) {
					return $value;
				// Session has expired
				} else {
					$this->forget($key);
				}
			}
		}

		return false;
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
	 * Delete any sessions that have been flashed, as they are only valid
	 * for one request.
	 * 
	 * @return void
	 */
	public function sweep()
	{
		foreach ($_SESSION as $key => $data) {
			if ($this->get($key, true) == true) {
				$this->forget($key);
			}
		}
	}
}