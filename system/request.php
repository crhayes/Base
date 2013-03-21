<?php
/**
* The Request class handles storing the request parameters (controller, action, params)
* and routing the request.
* 
* The appropriate Controller->action() method is called from here.
* 
* @package     Base PHP Framework
* @author      Chris Hayes <chris@chrishayes.ca>, <chayes@okd.com>
* @copyright   (c) 2012-2013 Chris Hayes, OKD
* @license     http://opensource.org/licenses/MIT
*/
class Request
{
    /**
     * The URI segments from the request.
     * 
     * @var array
     */
    private $uriSegments;

    /**
     * The path to the controller directory.
     * 
     * @var string
     */
    private $controllerPath;

    /**
     * The controller directory for the request.
     * 
     * @var string
     */
    private $controllerDirectory = '';

    /**
     * The requested controller.
     * 
     * @var string
     */
    private $controller;

    /**
     * The requested action.
     * 
     * @var string
     */
    private $action;

    /**
     * The request parameters.
     * 
     * @var array
     */
    private $params = array();

    /**
     * Setup the request and determine the controller directory.
     *
     * @return void
     */
    public function __construct()
    {
    	$this->uriSegments = explode('/', trim(str_replace(BASE_PATH, '', $_SERVER['REQUEST_URI']), '/'));
        $this->controllerPath = APP_PATH.'controllers'.DS;
    	$this->getControllerDirectory();
    }

    /**
     * Get the controller directory from the request parameters.
     *
     * This method loops through the request parameters (starting from the first) and
     * determines if each is a directory under the 'controllers' directory. If so, it is 
     * removed from the request parameters and appended to the controller directory string.
     *
     * This allows us to have nested directories for our controllers.
     * 
     * @return void
     */
    public function getControllerDirectory()
    {
    	foreach ($this->uriSegments as $segment) {
    		$this->controllerDirectory .= ($segment && is_dir($this->controllerPath.$this->controllerDirectory.$segment)) ? array_shift($this->uriSegments).DS : '';
    	}
    }

    /**
     * Load a controller, call the appropriate action and render a response.
     * 
     * @return void
     */
    function route()
    {
        $segments = $this->uriSegments;

        // If the first segment is a valid controller we shift it off the segments array
        // and set it as the current controller. Otherwise default to the index controller.
        $this->controller = ($this->controllerExists(Arr::get(0, $segments)))
            ? array_shift($segments)
            : 'index';

        // The first segment is now the action. If it's set we use it, otherwise default
        // to the index action.
        $this->action = (Arr::get(0, $segments))
            ? array_shift($segments)
            : 'index';

        // The remaining segments are request parameters
        $this->params = $segments;

        $controller = $this->loadController($this->controller);
        $action = $this->formatAction($this->action, $controller->restful);
        
        $response = $controller->before();
        $response = (is_null($response)) ? call_user_func_array(array($controller, $action), $this->params) : $response;
        $controller->after();

        return $response;
    }

    /**
     * Check if a controller exists that matches the request.
     * 
     * @param   string   $controller    Name of the controller to look for
     * @return  bool                    True if controller exists 
     */
    private function controllerExists($controller)
    {
        return file_exists($this->controllerPath.$this->controllerDirectory.$controller.EXT);
    }

    /**
     * Load a controller.
     * 
     * @param   string  $controller
     * @return  object  Controller
     */
    private function loadController($controller)
    {
        require_once $this->controllerPath.$this->controllerDirectory.$controller.EXT;        
        $controller = $this->formatController($controller);

        return new $controller($this);
    }

    /**
     * Format a controller so we can create a new instance.
     * 
     * @param   string  $controller
     * @return  string 
     */
    private function formatController($controller)
    {
        $directory = str_replace('/', '', $this->controllerDirectory);
        return ucfirst($directory).ucfirst($controller).'Controller';
    }

    /**
     * Format an action so we can call it. If the controller calling the
     * request is using restful routing we prepend the request type.
     * 
     * @param   string  $action
     * @param   boolean	$restful
     * @return  string 
     */
    private function formatAction($action, $restful = false)
    {
        if ($restful == true) {
            $prefix = strtolower($_SERVER['REQUEST_METHOD']);
        } else {
            $prefix = 'action';
        }
        
        return $prefix.ucfirst($action);
    }

    /**
     * Return the requested controller.
     * 
     * @return string
     */
    public function controller()
    {
    	return $this->controller;
    }

    /**
     * Return the requested action.
     * 
     * @return string
     */
    public function action()
    {
    	return $this->action;
    }

    /**
     * Return a request parameter corresponding to the curreny index.
     *
     * Starts at index 1: i.e. the first parameter has an index of 1.
     * 
     * @param  int 		$index
     * @return string
     */
    public function param($index)
    {
    	return Arr::get(($index-1), $this->params);
    }

    /**
     * Get the referring page.
     * 
     * @return  mixed 
     */
    public static function referrer()
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            return $_SERVER['HTTP_REFERER'];
        }
        
        return false;
    }
}