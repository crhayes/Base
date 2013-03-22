<?php
/**
 * URL helper class to make it easy to create URLs.
 * 
 * @package     Base PHP Framework
 * @author      Chris Hayes <chris@chrishayes.ca>, <chayes@okd.com>
 * @copyright   (c) 2012-2013 Chris Hayes, OKD
 * @license     http://opensource.org/licenses/MIT
 */
class Url
{
    /**
     * Determine the base URL of the application.
     * 
     * @return   string 
     */
    public static function base()
    {
        // Determine whether to use HTTP or HTTPS
        $protocol = (Arr::get('HTTPS', $_SERVER) && $_SERVER['HTTPS'] != "off") ? "https://" : "http://";
        
        return $protocol.$_SERVER['HTTP_HOST'].BASE_PATH;
    }

    /**
     * Create a URL to a route.
     * 
     * @param   string  $route
     * @return  string 
     */
    public static function toRoute($route)
    {
        return self::base().$route;
    }
}