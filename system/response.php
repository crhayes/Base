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
	/**
	 * HTTP status code.
	 * 
	 * @var int
	 */
	protected $status = 200;
	
	abstract public function send();
}