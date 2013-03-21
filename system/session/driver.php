<?php

interface SessionDriver
{
	public function set($key, $value, $flashed = false);

	public function flash($key, $value, $flashed = true);
	
	public function get($key, $checkIfFlashed = false);
	
	public function forget($key);

	public function sweep();	
}