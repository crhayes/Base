<?php
/**
 * Export Utility helper.
 *
 * Provides a couple of useful functions for exporting database results
 * into CSV format.
 * 
 * @package     Base PHP Framework
 * @author      Chris Hayes <chris@chrishayes.ca>, <chayes@okd.com>
 * @copyright   (c) 2012-2013 Chris Hayes, OKD
 * @license     http://opensource.org/licenses/MIT
 */
class Export
{    
    /**
     * Create a CSV export from a MySQL result array.
     * 
     * @param   array   $array   MySQL result array
     * @param   string  $fileName  Name used to save file
     */
    public static function csvFromMysqlArray($array, $fileName)
    {
        $output .= Export::csvHeaders($array[0]);

        foreach ($array as $row) {
            // remove newlines from all the fields and surround them with quotes
            foreach ($row as &$value) {
                $value = '"'.str_replace("\r\n", "", $value).'"';
            }

            $output .= join(',', $row) . "\n";
        }
        
        Export::sendOutput($output, $fileName);
    }

    /**
     * Create a CSV export from a MySQL resource.
     * 
     * @param   resource    $resource   MySQL Resource
     * @param   string      $fileName  Name used to save file
     */
    public static function csvFromMysqlResource($resource, $fileName)
    {
        $output = "";
        $headersPrinted = false;
        
        // Loop through each result row
        while ($row = mysql_fetch_array($resource, MYSQL_ASSOC)) {
            // Print out column names as the first row
            if ( ! $headersPrinted) {
                $output .= Export::csvHeaders($row);
                $headersPrinted = true;
            }

            // Remove newlines from all the fields and surround them with quotes
            foreach ($row as &$value) {
                $value = '"'.str_replace("\r\n", "", $value).'"';
            }

            $output .= join(',', $row) . "\n";
        }
        
        Export::sendOutput($output, $fileName);
    }
    
    /**
     * Create the CSV headers from the first resource row.
     * 
     * @param   array   $row
     * @return  string 
     */
    private static function csvHeaders($row)
    {        
        return join(',', str_replace('_', ' ', array_keys($row))) . "\n";
    }
    
    /**
     * Send output to the user so they can download the file.
     * 
     * @param   string  $output     Contents of the CSV file sent
     * @param   string  $fileName  Name used to save file
     */
    private static function sendOutput($output, $fileName)
    {
        // Get the filesize
        $sizeInBytes = strlen($output);
        
        // Set the headers
        header("Content-type: application/csv");
        header("Content-disposition: attachment; filename=$fileName; size=$sizeInBytes");
				
		// Send output
        echo $output;
				
		// Stop execution...
		exit();
    }
}