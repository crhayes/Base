<?php
/**
* Defines the signature of a response.
* 
* @package     Base PHP Framework
* @author      Chris Hayes <chris@chrishayes.ca>, <chayes@okd.com>
* @copyright   (c) 2012-2013 Chris Hayes, OKD
* @license     http://opensource.org/licenses/MIT
*/
abstract class Response
{
	protected $status = 200;

	protected $data = array();
	
	abstract public function send();

	abstract public function withData($key, $value);

	abstract public function withErrors($errors);
}