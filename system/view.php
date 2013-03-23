<?php
/**
 * The view class provides template inheritance. 
 *
 * Views can easily extend a base 'layout' and define sections
 * of content that can be easily extended or overwritten.
 * 
 * @package     Base PHP Framework
 * @author      Chris Hayes <chris@chrishayes.ca>, <chayes@okd.com>
 * @copyright   (c) 2012-2013 Chris Hayes, OKD
 * @license     http://opensource.org/licenses/MIT
 */
class View extends Response
{
	/**
	 * Store an instance of the View class.
	 * 
	 * @var View
	 */
	private static $instance;

	/**
	 * Store the path to the views folder.
	 * 
	 * @var string
	 */
	private $viewPath;

	/**
	 * The name of the view we are loading.
	 * 
	 * @var string
	 */
	private $viewName;

	/**
	 * The view we are extending.
	 * 
	 * @var string
	 */
	private $extendedView;

	/**
	 * Store the contents of sections.
	 * 
	 * @var array
	 */
	private $sections;

	/**
	 * The currently opened (started) section.
	 * 
	 * @var string
	 */
	private $openSection;

	/**
	 * Store data to be used in the view.
	 * 
	 * @var array
	 */
	private $data = array();

	/**
	 * Returns a new template object
	 *
	 * @param string 	$view
	 */
	private function __construct($view, $data)
	{
		$this->viewPath = APP_PATH.'views/';
		$this->viewName = $view;
		$this->data = $data;
	}

	/**
	 * Return an instance of the view class.
	 * 
	 * @param  string 	$view
	 * @return View
	 */
	public static function make($view, $data = array()) {
		return self::getInstance($view, $data);
	}

	/**
	 * Static interface to return an instance of the View class.
	 *
	 * This is used to return an instance of the view class to the helper
	 * methods that would otherwise have no access to the View data.
	 *
	 * @param  mixed 	$view
	 * @return View
	 */
	public static function getInstance($view = null, $data = array())
	{
		if ( ! isset(self::$instance)) {
			self::$instance = new View($view, $data);
		}

		return self::$instance;
	}

	/**
	 * Set the view path.
	 * 
	 * @param string 	$path
	 */
	public function setViewPath($path)
	{
		$this->viewPath = $path;
	}

	/**
	 * Add data to the view.
	 * 
	 * @param  string 	$key
	 * @param  mixed 	$value
	 * @return View
	 */
	public function with($key, $value)
	{
		$this->data[$key] = $value;

		return $this;
	}

	/**
	 * Sets the HTTP status code for the view.
	 * 
	 * This is useful, for example, when we want to show a 404/error page 
	 * with a 404 response but don't want to redirect the user.
	 * 
	 * @param  int 		$code
	 * @return View
	 */
	public function status($code)
	{
		$this->status = $code;

		return $this;
	}

	/**
	 * Render View HTML.
	 *
	 * @return mixed
	 */
	public function render()
	{
		if ($this->status) {
			header(' ', true, $this->status);
		}

		try {
			$view = $this->load($this->viewName);

			if ($this->extendedView) {
				$view = $this->load($this->extendedView);
			}

			echo $view;
		}
		catch(\Exception $e)
		{
			return (string) $e;
		}
	}

	/**
	 * Load the given template and return the contents.
	 *
	 * @param  string 	$view
	 * @return string
	 */
	public function load($view)
	{
		ob_start();

		extract($this->data);
		require $this->viewPath.$view.EXT;

		return ob_get_clean();
	}

	/**
	 * Extend a parent View.
	 *
	 * @param  string 	$view
	 * @return void
	 */
	public function extend($view)
	{
		$this->extendedView = $view;
		ob_end_clean(); // Ignore this child class and load the parent!
		ob_start();
	}

	/**
	 * Start a new section.
	 * 
	 * @param  string 	$name
	 * @return void
	 */
	public function section($name)
	{
		$this->openSection = $name;
		ob_start();
	}

	/**
	 * Close a section and return the buffered contents.
	 *
	 * @return string
	 */
	public function close()
	{
		$name = $this->openSection;

		$buffer = ob_get_clean();

		if ( ! isset($this->sections[$name])) {
			$this->sections[$name] = $buffer;
		} elseif (isset($this->sections[$name])) {
			$this->sections[$name] = str_replace('@parent', $buffer, $this->sections[$name]);
		}

		return $this->sections[$name];
	}

	/**
	 * Show the contents of a section.
	 *
	 * @param  string 	$name
	 * @return string
	 */
	public function show($name)
	{
		if(isset($this->sections[$name]))
		{
			return $this->sections[$name];
		}
	}

	/**
	 * Render and output the view.
	 * 
	 * @return void
	 */
	public function send()
	{
		$this->render();
	}

	/**
	 * Allows setting template values while still returning the object instance
	 * $template->title($title)->text($text);
	 *
	 * @return this
	 */
	public function __call($key, $args)
	{
		$this->$key = $args[0];
		return $this;
	}
}