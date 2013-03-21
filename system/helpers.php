<?php
/**
 * Useful helper functions.
 * 
 * @package     Base PHP Framework
 * @author      Chris Hayes <chris@chrishayes.ca>, <chayes@okd.com>
 * @copyright   (c) 2012-2013 Chris Hayes, OKD
 * @license     http://opensource.org/licenses/MIT
 */

function dd($value)
{
	echo '<pre>';
	var_dump($value);
	echo '</pre>';
	die();
}
 
function extend($name)
{
	View::getInstance()->extend($name);
}

function section($name)
{
	View::getInstance()->section($name);
}

function close()
{
	echo View::getInstance()->close();
}

function show($name)
{
	echo View::getInstance()->show($name);
}

function partial($name)
{
	echo View::getInstance()->load($name);
}

function asset($path)
{
	return BASE_PATH.'assets'.DS.$path;
}

function route($path)
{
	return URL::toRoute($path);
}