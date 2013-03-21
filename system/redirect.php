<?php
/**
 * Redirect class. Provides a wrapper for redirecting that allows us to return
 * a redirect as a response.
 * 
 * We can set the status code of the redirect as well as redirect with 
 * flashed session data.
 * 
 * @package     Base PHP Framework
 * @author      Chris Hayes <chris@chrishayes.ca>, <chayes@okd.com>
 * @copyright   (c) 2012-2013 Chris Hayes, OKD
 * @license     http://opensource.org/licenses/MIT
 */
class Redirect
{
    private static $instance;

    private $location;

    private $status;

    private function __construct() {}

    public static function getInstance()
    {
        if ( ! isset(self::$instance)) {
            self::$instance = new Redirect();
        }

        return self::$instance;
    }

    /**
     * Redirect to another page given either an absolute URL or a route.
     * 
     * @param   string  $location    Either an absolute URL or a route.
     * @param   string  $status
     * @return  void
     */
    public function _to($location, $status = 302)
    {
        $this->status = $status;

        // Redirect to an absolute URL.
        if (stristr($location, 'http://') or stristr($location, 'https://')) {
            $this->location = $location;
        }
        
        // Redirect internally using a route.
        $this->location = URL::toRoute($location);
    }
    
    /**
     * Redirect to the previous URL. 
     * 
     * @return  void
     */
    public function _back($status = 302)
    {
        $this->status = $status;

        if ($back = Request::referrer()) {
            $this->location = $back;
        }
    }

    public function with($key, $value)
    {
        Session::flash($key, $value);

        return $this;
    }

    public function redirect()
    {
        header("Location: $this->location", true, $this->status);
        exit();
    }
    
    public static function __callStatic($name, $arguments)
    {
        call_user_func_array(array(self::getInstance(), "_$name"), $arguments);

        return self::getInstance();
    }
}