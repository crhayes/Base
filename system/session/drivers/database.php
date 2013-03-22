<?php
/**
 * Database session driver. Allows us to save sessions in a database.
 * 
 * @package     Base PHP Framework
 * @author      Chris Hayes <chris@chrishayes.ca>, <chayes@okd.com>
 * @copyright   (c) 2012-2013 Chris Hayes, OKD
 * @license     http://opensource.org/licenses/MIT
 */
class SessionDatabase extends Session implements SessionDriver
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
		Database::query('
			INSERT INTO session (session_id, key_val, data)
				VALUES(:session_id, :key_val, :data)
				ON DUPLICATE KEY UPDATE session_id = :session_id, key_val = :key_val, data = :data', 
			array(
				':session_id' => session_id(),
				':key_val' => $key,
				':data' => Hash::make(serialize($value))
			)
		);
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
		$session = Database::row('SELECT * FROM session WHERE session_id = ? AND key_val = ?', array(session_id(), $key));

		return ($session->count()) ? unserialize(Hash::undo($session->data)) : false;
	}
	
	/**
	 * Delete a database session.
	 * 
	 * @param  string 	$key
	 * @return void
	 */
	public function forget($key)
	{
		Database::query('DELETE FROM session WHERE session_id = ? AND key_val = ?', array(session_id(), $key));
	}

	/**
	 * Return all database sessions.
	 * 
	 * @return DatabaseResult
	 */
	public function sessions()
	{
		return Database::query('SELECT * FROM session');
	}
}