<?php defined('SYS_PATH') or die('No direct script access.');
/**
 * Redriect class.
 * 
 * @package     ssMVC - Super Simple MVC
 * @author      Chris Hayes <chris at chrishayes.ca>
 * @copyright   (c) 2012 Chris Hayes
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