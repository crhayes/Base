<?php

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
		$_SESSION[$key] = Hash::make(serialize(compact('value', 'flashed')));
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
			
			return ($checkIfFlashed) ? $flashed : $value;			
		}
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