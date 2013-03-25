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
class Redirect extends Response
{
    /**
     * Store a singleton instance of the redirect class.
     * 
     * @var Redirect
     */
    private static $instance;

    /**
     * The location we are redirecting to.
     * 
     * @var string
     */
    private $location;

    /**
     * Class constructor.
     */
    private function __construct() {}

    /**
     * Return a singleton instance of the class.
     * 
     * @return Redirect
     */
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
        // Redirect internally using a route.
        } else {
            $this->location = URL::toRoute($location);
        }
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

    /**
     * Attach some data to redirect with. The data will be
     * 'flashed' by the session class.
     * 
     * @param  string   $key
     * @param  mixed    $value
     * @return Redirect
     */
    public function with($key, $value)
    {
        Session::flash($key, $value);

        return $this;
    }

    /**
     * Send the response to the browser, which is a redirect header.
     * 
     * @return void
     */
    public function send()
    {
        header("Location: $this->location", true, $this->status);
        exit();
    }
    
    /**
     * Allow us to initiate the redirect statically and return the instance of
     * the Redirect class for method chaining.
     * 
     * @param  string   $name
     * @param  array    $arguments
     * @return Redirect
     */
    public static function __callStatic($name, $arguments)
    {
        call_user_func_array(array(self::getInstance(), "_$name"), $arguments);

        return self::getInstance();
    }
}