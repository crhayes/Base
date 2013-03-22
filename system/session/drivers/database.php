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
			INSERT INTO session (session_id, data, flashed, last_activity)
				VALUES(:session_id, :data, :flashed, NOW())
				ON DUPLICATE KEY UPDATE data = :data, flashed = :flashed, last_activity = NOW()', 
			array(
				':session_id' => $key,
				':data' => Hash::make(serialize($value)),
				':flashed' => (int) $flashed));
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
		$session = Database::row('SELECT * FROM session WHERE session_id = ?', array($key));

		if ($session->count()) {
			// Just want to know if the session is a flashed one
			if ($checkIfFlashed) {
				return $session->flashed;
			// Otherwise we want the session data
			} else {
				$lifetime = Config::get('application.session.lifetime');

				// The session is valid
				if ((strtotime($session->last_activity) + $lifetime) > strtotime('now')) {
					return unserialize(Hash::undo($session->data));
				// Session has expired
				} else {
					$this->forget($session->session_id);
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
		Database::query('DELETE FROM session WHERE session_id = ?', array($key));
	}

	/**
	 * Delete any sessions that have been flashed, as they are only valid
	 * for one request.
	 * 
	 * @return void
	 */
	public function sweep()
	{
		if (($sessions = $this->all()) && $sessions->count()) {
			foreach ($sessions as $session) {
				if ($this->get($session->session_id, true) == true) {
					$this->forget($session->session_id);
				}
			}
		}
	}

	/**
	 * Return all database sessions.
	 * 
	 * @return DatabaseResult
	 */
	private function all()
	{
		return Database::query('SELECT * FROM session');
	}
}