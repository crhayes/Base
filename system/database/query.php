<?php

class DatabaseQuery
{
	/**
	 * Store a PDO instance.
	 * 
	 * @var PDO
	 */
	private $connection;

	/**
	 * Store the PDO statement.
	 * 
	 * @var PDOStatement
	 */
	private $query;

	/**
	 * Store the parameters we'll use for the prepared statements.
	 * 
	 * @var array
	 */
	private $params = array();

	/**
	 * The type of fetch we are doing; i.e. fetching an array result set, a row, or a field
	 * 
	 * @var string
	 */
	private $fetchMethod;

	/**
	 * Set the PDO fetch mode. Allows us to return data as objects or associative arrays.
	 * 
	 * @var int 
	 */
	private $fetchMode = PDO::FETCH_OBJ;

	/**
	 * Store the result of the query.
	 * 
	 * @var array
	 */
	private $result = array();

	/**
	 * Prepare the query, apply bindings and execute.
	 * 
	 * @param  PDO 		$connection
	 * @param  string 	$query
	 * @param  array 	$bindings
	 * @param  int 		$fetchMethod
	 * @param  int 		$fetchMode
	 * @return void
	 */
	public function __construct($connection, $query, $bindings, $fetchMethod, $fetchMode)
	{
		$this->connection = $connection;

		$this->query = $this->connection->prepare($query);

		// If there are any bindings for the prepared statement we store them
		if ($bindings) {
			$this->params = $this->params + $bindings;
		}

		$this->fetchMethod = $fetchMethod;

		// If a fetch more was specified we store it
		if ($fetchMode) {
			$this->fetchMode = $fetchMode;
		}
	}

	/**
	 * Execute the query and store the result.
	 * 
	 * @return void
	 */
	public function execute()
	{
		$this->query->setFetchMode($this->fetchMode);

		$this->query->execute($this->params);	

		// Fetch the data in the appropriate format
		switch ($this->fetchMethod) {
			case 'row':
				$result = $this->query->fetch();
				break;
			case 'field':
				$result = $this->query->fetchColumn();
				break;
			default:
				$result = $this->query->fetchAll();
				break;
		}

		return new DatabaseResult($this, $result);
	}
}