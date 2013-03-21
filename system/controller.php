<?php
/**
 * Base Controller.
 * 
 * @package     Base PHP Framework
 * @author      Chris Hayes <chris@chrishayes.ca>, <chayes@okd.com>
 * @copyright   (c) 2012-2013 Chris Hayes, OKD
 * @license     http://opensource.org/licenses/MIT
 */
class Controller
{
	/**
	 * Instance of the request object.
	 * 
	 * @var Request
	 */
	protected $request;

	/**
	 * Indicates whether the controller uses RESTful routing.
	 * 
	 * @var boolean
	 */
	public $restful = false;

	/**
	 * Setup the controller and store an instance of the request.
	 * 
	 * @param  Request 	$request
	 */
	public function __construct($request)
	{
		$this->request = $request;
	}

	/**
	 * Called before the action is executed.
	 * 
	 * @return void
	 */
	public function before() {}

	/**
	 * Called after the action is executed.
	 * 
	 * @return void
	 */
	public function after() {}

	/**
	 * Magic method fired when calling undefined controller methods.
	 *
	 * Returns the 404 error view.
	 * 
	 * @param  string 	$name
	 * @param  array 	$arguments
	 * @return View
	 */
	public function __call($name, $arguments)
	{
		return View::make('error/404')->status(404);
	}
}