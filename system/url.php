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
     * Create a URL to a route.
     * 
     * @param   string  $route
     * @return  string 
     */
    public static function toRoute($route)
    {
        return BASE_PATH.$route;
    }
}