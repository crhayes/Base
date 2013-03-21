<?php
/**
 * Session driver interface. All drivers must implement this interface.
 * 
 * @package     Base PHP Framework
 * @author      Chris Hayes <chris@chrishayes.ca>, <chayes@okd.com>
 * @copyright   (c) 2012-2013 Chris Hayes, OKD
 * @license     http://opensource.org/licenses/MIT
 */
interface SessionDriver
{
	public function set($key, $value, $flashed = false);

	public function flash($key, $value, $flashed = true);
	
	public function get($key, $checkIfFlashed = false);
	
	public function forget($key);

	public function sweep();	
}