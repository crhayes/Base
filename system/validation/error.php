<?php
/**
 * The validation library provides a utility that makes it dead simple to 
 * validate forms. It allows rules to be applied to form attributes and then 
 * automagically validates the rules against each attribute.
 *
 * This class stores any errors that occur during validation and provides
 * a simple interface to display those errors.
 * 
 * @package     Base PHP Framework
 * @author      Chris Hayes <chris@chrishayes.ca>, <chayes@okd.com>
 * @copyright   (c) 2012-2013 Chris Hayes, OKD
 * @license     http://opensource.org/licenses/MIT
 */
class ValidationError
{
    /**
     * Store the validation errors.
     * 
     * @var array
     */
    private $errors = array();

    /**
     * Default error messages.
     * 
     * @var array
     */
    private $messages = array(
        'accepted'      => 'You must accept the terms',
        'required'      => 'This attribute is required',
        'date'          => 'Must be a valid date',
        'email'         => 'Must be a valid email address',
        'ip'            => 'Must be a valid IP address',
        'url'           => 'Must be a valid URL',
        'between'       => array(
            'numeric'       => 'Must be between %s and %s',
            'string'        => 'Must be between %s and %s characters long'
        ),
        'min'           => array(
            'numeric'       => 'Must be at least %s',
            'string'        => 'Must be at least %s characters long'
        ),
        'max'           => array(
            'numeric'       => 'Must not be greater than %s',
            'string'        => 'Must not be greater than %s characters long'
        ),
        'match'         => 'Must match the %s attribute',
        'mismatch'      => 'Must not match the %s attribute',
        'same'          => 'Must equal %s',
        'different'     => 'Must not equal %s',
        'age'           => 'You must be of age %s',
        'numeric'       => 'Must be numeric',
        'integer'       => 'Must be an integer',
        'alpha'         => 'Must contain only alphabetic characters',
        'alpha'         => 'Must contain only alphanumeric characters',
        'alphadash'     => 'Must contain only alphanumeric characters or dashes',
        'postalcode'    => 'Must be a valid postal code',
        'zipcode'       => 'Must be a valid zip code',
        'in'            => 'Must be one of: %s',
        'notin'         => 'Must not be one of: %s',
        'filetype'      => 'Must be of file type: %s',
        'filesize'      => 'Maximum file size is %s'
    );
    
    /**
     * Class constructor. Accepts a messages parameter that allows 
     * overridding of default messages.
     * 
     * @param  mixed    $messages
     * @return void
     */
    public function __construct($messages)
    {
        if ($messages) {
            $this->messages = array_merge($this->messages, $messages);
        }
    }
    
    /**
     * Check if an error for a attribute exists.
     * 
     * @param  string   $attribute
     * @return boolean
     */
    public function exist($attribute = null)
    {
        if ($attribute and ! empty($this->errors[$attribute])) {
            return true;
        } elseif ( ! $attribute and ! empty($this->errors)) {
            return true;
        }

        return false;
    }

    /**
     * Get the first error for a attribute.
     * 
     * @param  string   $attribute
     * @param  string   $format
     * @return string
     */
    public function first($attribute, $format = null)
    {
        if ($this->exist($attribute)) {
            // Format the message
            $message = $this->formatMessages($this->errors[$attribute], $format);

            // Get the first error message for the attribute
            return array_shift($message);
        }
    }

    /**
     * Get all the errors for a attribute.
     * 
     * @param  string   $attribute
     * @param  string   $format
     * @param  boolean  $returnString
     * @return array
     */
    public function get($attribute, $format = null, $returnString = false)
    {
        if ($this->exist($attribute)) {
            // Format the message
            $messages = $this->formatMessages($this->errors[$attribute], $format);

            return ($returnString) ? $this->compressMessages($messages) : $messages;
        }
    }

    /**
     * Get all the errors.
     * 
     * @param  string   $format
     * @param  boolean  $returnString
     * @return array
     */
    public function all($format = null, $returnString = false)
    {
        // Format the message
        $messages = $this->formatMessages($this->errors, $format);

        return ($returnString) ? $this->compressMessages($messages) : $messages;
    }

    /**
     * Store an error for a attribute.
     * 
     * @param  string   $attribute
     * @param  string   $rule
     * @param  array    $parameters
     * @return void
     */
    public function addError($attribute, $rule, $parameters = null)
    {
        $message = $this->getFromString($rule, $this->messages);

        if ($parameters) {
            $message = vsprintf($message, $parameters);
        }

        $this->errors[$attribute][$rule] = $message;
    }

    /**
     * Remove an error for a attribute.
     * 
     * @param  string   $attribute
     * @param  string   $rule
     * @param  boolean  $all
     * @return void
     */
    public function removeError($attribute, $rule, $all = false)
    {
        if ($all) {
            unset($this->errors[$attribute]);
        } else {
            unset($this->errors[$attribute][$rule]);
        }
    }

    /**
     * Format error messages.
     * 
     * @param  mixed    $messages
     * @param  string   $format
     * @return mixed
     */
    private function formatMessages($messages, $format)
    {
        // If formatting is specified apply it
        if ($format) {
            // Recursively loop through errors
            if (is_array($messages)) {
                foreach ($messages as &$message) {
                    $message = $this->formatMessages(&$message, &$format);
                }
            // Once we have reached an error message we format it            
            } else {
                return str_replace(':message', $messages, $format);
            }
        }

        return $messages;
    }

    /**
     * Convert an array of messages into a string. This is useful for easily printing
     * all error messages to the screen.
     * 
     * @param  array    $messages
     * @return string
     */
    private function compressMessages($messages)
    {
        if (is_array($messages)) {
            foreach ($messages as &$message) {
                $message = $this->compressMessages($message);
            }
            
            $messages = join('', $messages);
        }

        return $messages;
    }

    /**
     * Given an array key in "dot notation" get an array value if it 
     * exists, otherwise return a default value.
     * 
     * @param   string  $keys   Array key as a dot notated string.
     * @param   array   $array  Array to search through.
     * @return  string
     */
    public static function getFromString($keys, $array, $default = null)
    {
        foreach (explode('.', $keys) as $key)
        {
            if ( ! is_array($array) or ! array_key_exists($key, $array))
            {
                return $default;
            }

            $array = $array[$key];
        }
        
        return $array;
    }
}