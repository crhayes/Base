<?php
/**
 * Database Utility. Provides a wrapper around PHP's PDO extension to 
 * simplify database querying.
 *
 * This class stores the results of a database query and provides
 * an easy way to iterate over and use the results.
 * 
 * @package     Base PHP Framework
 * @author      Chris Hayes <chris@chrishayes.ca>, <chayes@okd.com>
 * @copyright   (c) 2012-2013 Chris Hayes, OKD
 * @license     http://opensource.org/licenses/MIT
 */
class DatabaseResult implements ArrayAccess, IteratorAggregate
{
	/**
	 * Store an instance of the query.
	 * 
	 * @var DatabaseQuery
	 */
	private $query;

	/**
	 * Store the result of the query.
	 * 
	 * @var array
	 */
	private $result;

	/**
	 * Class constructor.
	 * 
	 * @param DatabaseQuery $query
	 * @param array 		$result
	 */
	public function __construct($query, $result)
	{
		$this->query = $query;
		$this->result = $result;
	}

	/**
	 * Return the number of affected rows.
	 * 
	 * @return integer
	 */
	public function count()
	{
		return $this->query->resultCount();
	}

	/**
	 * Return the last inserted id.
	 * 
	 * @return integer
	 */
	public function lastInsertId()
	{
		return $this->connection->lastInsertId();
	}

 	/**
 	 * Create a new iterator.
 	 * 
 	 * @return ArrayIterator
 	 */
    public function getIterator()
    {
        return new ArrayIterator($this->result);
    }

    /**
     * Magic Method to allow us to read unaccessible properties.
     *
     * When using Database::row() a DatabaseQuery object is returned,
     * but consudering we are querying for one row it's likely we will want to use the returned
     * value as if it's a result set. This Magic Method allow us to capture undefined properties and
     * return the corresponding value from our stored query result. 
     * 
     * @param  string 	$name
     * @return string
     */
    public function __get($name)
    {
    	if ($this->result) {
    		return $this->result->{$name};
    	}
    }

    /**
     * Magic Method to allow us to use the returned object like a string.
     *
     * When using Database::field() a DatabaseQuery object is returned,
     * but considering we are querying one field it's likely we will want to use the returned
     * value as if it were a string. This Magic Method allows us to 'convert' the object to a string
     * by returning the query result value.
     * 
     * @return string
     */
    public function __toString()
    {
    	if ($this->result) {
    		return $this->result;
    	}
    }

    /**
     * Magic method to return the data that should be serialized when attempting
     * to serialize an object instance.
     * 
     * @return array
     */
    public function __sleep()
    {
    	return array('result');
    }

    /**
     * Magic method that is called when calling isset() on an object instance.
     * 
     * @param  string  $name
     * @return boolean
     */
    public function __isset($name)
    {
    	if (is_array($this->result)) {
    		return ! empty($this->result[$name]);
    	}

    	return ! empty($this->result);
    }

 	public function offsetSet($offset, $value)
 	{
        if (is_null($offset)) {
            $this->result[] = $value;
        } else {
            $this->result[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->result[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->result[$offset]);
    }
    
    public function offsetGet($offset)
    {
        return isset($this->result[$offset]) ? $this->result[$offset] : null;
    }
}