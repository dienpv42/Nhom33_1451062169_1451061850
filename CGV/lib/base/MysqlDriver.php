<?php
/**
* PHP Simple Model Driver
* 
* @author DienPham<dienpv42@gmail.com>
* @version 1.0
*/

class MysqlDriver {
	/**
	 * Enter description here...
	 *
	 * @var unknown_type
	 */
	var $startQuote = "`";
	
	/**
	 * Enter description here...
	 *
	 * @var unknown_type
	 */
	var $endQuote = "`";
	
	/**
	 * The DataSource configuration
	 *
	 * @var array
	 * @access public
	 */
	var $config = array ();
	
	/**
	 * Log file
	 */
	var $logFile = null;
	
	var $logFileName = '';
	
	/**
	* List of table engine specific parameters used on table creating
	*
	* @var array
	* @access public
	*/
	var $tableParameters = array(
		'charset' => array('value' => 'DEFAULT CHARSET', 'quote' => false, 'join' => '=', 'column' => 'charset'),
		'collate' => array('value' => 'COLLATE', 'quote' => false, 'join' => '=', 'column' => 'Collation'),
		'engine' => array('value' => 'ENGINE', 'quote' => false, 'join' => '=', 'column' => 'Engine')
	);
	
	var $keywords = array('>=', '<=', '>', '<', 'IN', 'NOT', 'IS', 'LIKE', '!=', '<>');
	
	/**
	 * Mysqli column definition
	 *
	 * @var array
	 */
	var $columns = array('primary_key' => array('name' => 'int(11) DEFAULT NULL auto_increment'),
						'string' => array('name' => 'varchar', 'limit' => '255'),
						'text' => array('name' => 'text'),
						'integer' => array('name' => 'int', 'limit' => '11', 'formatter' => 'intval'),
						'float' => array('name' => 'float', 'formatter' => 'floatval'),
						'datetime' => array('name' => 'datetime', 'format' => 'Y-m-d H:i:s', 'formatter' => 'date'),
						'timestamp' => array('name' => 'timestamp', 'format' => 'Y-m-d H:i:s', 'formatter' => 'date'),
						'time' => array('name' => 'time', 'format' => 'H:i:s', 'formatter' => 'date'),
						'date' => array('name' => 'date', 'format' => 'Y-m-d', 'formatter' => 'date'),
						'binary' => array('name' => 'blob'),
						'boolean' => array('name' => 'tinyint', 'limit' => '1'));
	
	static $instance = null;
	
	public function __construct($config = array()) {
		$this->config = $config;
		
		return $this->connect();
	}
	
	public static function getInstance()
	{
		$config = array('host'=>DATABASE_HOST, 'login' => DATABASE_USERNAME, 'password' => DATABASE_PASSWORD, 'port' => DATABASE_PORT, 'database' => DATABASE_NAME, 'encoding'=>'utf8');
		if (null === MysqlDriver::$instance) {
			MysqlDriver::$instance = new MysqlDriver($config);
		}
	
		return MysqlDriver::$instance;
	}
	
	/**
	 * Connects to the database using options in the given configuration array.
	 *
	 * @return boolean True if the database could be connected, else false
	 */
	function connect() {
		$config = $this->config;
		$this->connected = false;
		$this->connection = mysqli_connect($config['host'], $config['login'], $config['password'], $config['database']);

		if ($this->connection !== false) {
			$this->connected = true;
		}
		
		// Important row to return utf8 results
		if (!empty($config['encoding'])) {
			$this->setEncoding($config['encoding']);
		}
		
		return $this->connected;
	}
	
	/**
	 * Disconnects from database.
	 *
	 * @return boolean True if the database could be disconnected, else false
	 */
	function disconnect() {
		@mysqli_free_result($this->results);
		$this->connected = !@mysqli_close($this->connection);
		return !$this->connected;
	}
	
	function setLogFile($name) {
		$this->logFileName = $name;
		
		// Init log file
		$this->logFile = fopen($this->logFileName, 'a+');
	}
	
	/**
	 * Executes given SQL statement.
	 *
	 * @param string $sql SQL statement
	 * @return resource Result resource identifier
	 * @access protected
	 */
	function _execute($sql) {
		if (preg_match('/^\s*call/i', $sql)) {
			return $this->_executeProcedure($sql);
		} else {
			return mysqli_query($this->connection, $sql);
		}
	}
	
	function execute($sql) {
		if (! function_exists ( 'getMicrotime' )) {
			/**
			 * Returns microtime for execution time checking
			 *
			 * @return float Microtime
			 */
			function getMicrotime() {
				list ( $usec, $sec ) = explode ( " ", microtime () );
				return (( float ) $usec + ( float ) $sec);
			}
		}
		
		$t = getMicrotime ();
		$this->_result = $this->_execute ( $sql );
		$this->affected = $this->lastAffected ();
		$this->took = round ( (getMicrotime () - $t) * 1000, 0 );
		$this->error = $this->lastError ();
		$this->numRows = $this->lastNumRows ( $this->_result );
		
		// Log query to files to debug
		$this->logQuery($sql);
		if (!empty($this->error)) {
			$this->logQuery($this->error);
		}
		
		return $this->_result;
	}
	
	/**
	 * Log SQL to output files to debug
	 */
	function logQuery($sql) {
		fwrite($this->logFile, date('Y-m-d H:i:s') . " Query: " . $sql."\n");
	}
	
	/**
	 * Executes given SQL statement (procedure call).
	 *
	 * @param string $sql SQL statement (procedure call)
	 * @return resource Result resource identifier for first recordset
	 * @access protected
	 */
	function _executeProcedure($sql) {
	    $answer = mysqli_multi_query($this->connection, $sql);

	    $firstResult = mysqli_store_result($this->connection);

        if (mysqli_more_results($this->connection)) {
            while($lastResult = mysqli_next_result($this->connection));
        }

        return $firstResult;
	}
	
	/**
	 * Returns a quoted and escaped string of $data for use in an SQL statement.
	 */
	function value($data) {
		return "'" . mysqli_real_escape_string($this->connection, $data) . "'";
	}
	
	/**
	 * Returns a formatted error message from previous database operation.
	 *
	 * @return string Error message with error number
	 */
	function lastError() {
		if (mysqli_errno($this->connection)) {
			return mysqli_errno($this->connection).': '.mysqli_error($this->connection);
		}
		
		return null;
	}
	
	/**
	 * Returns number of affected rows in previous database operation. If no previous operation exists,
	 * this returns false.
	 *
	 * @return integer Number of affected rows
	 */
	function lastAffected($source = null) {
		if ($this->_result) {
			return mysqli_affected_rows($this->connection);
		}
		
		return null;
	}
	
	/**
	 * Returns number of rows in previous resultset. If no previous resultset exists,
	 * this returns false.
	 *
	 * @return integer Number of rows in resultset
	 */
	function lastNumRows($source = null) {
		if ($this->_result and is_object($this->_result)) {
			return @mysqli_num_rows($this->_result);
		}
		return null;
	}
	
	/**
	 * Returns the ID generated from the previous INSERT operation.
	 *
	 * @param unknown_type $source
	 * @return in
	 */
	function lastInsertId($source = null) {
		$id = $this->fetchRow('SELECT LAST_INSERT_ID() AS insertID', false);
		if ($id !== false && !empty($id) && !empty($id[0]) && isset($id[0]['insertID'])) {
			return $id[0]['insertID'];
		}

		return null;
	}

	/**
	 * Gets the length of a database-native column description, or null if no length
	 *
	 * @param string $real Real database-layer column type (i.e. "varchar(255)")
	 * @return integer An integer representing the length of the column
	 */
	function length($real) {
		$col = str_replace(array(')', 'unsigned'), '', $real);
		$limit = null;

		if (strpos($col, '(') !== false) {
			list($col, $limit) = explode('(', $col);
		}

		if ($limit != null) {
			return intval($limit);
		}
		return null;
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $results
	 */
	function resultSet(&$results) {
		$this->results =& $results;
		$this->map = array();
		$num_fields = mysqli_num_fields($results);
		$index = 0;
		$j = 0;
		while ($j < $num_fields) {
			$column = mysqli_fetch_field_direct($results, $j);
			if (!empty($column->table)) {
				$this->map[$index++] = array($column->table, $column->name);
			} else {
				$this->map[$index++] = array(0, $column->name);
			}
			$j++;
		}
	}
	
	/**
	 * Returns an array of all result rows for a given SQL query.
	 * Returns false if no rows matched.
	 *
	 * @param string $sql
	 *        	SQL statement
	 * @param boolean $cache
	 *        	Enables returning/storing cached query results
	 * @return array Array of resultset rows, or false if no rows matched
	 */
	function fetchAll($sql) {
		if ($this->execute ( $sql )) {
			$out = array ();
			
			while ( $item = $this->fetchRow () ) {
				$out [] = $item;
			}
			return $out;
		} else {
			return false;
		}
	}
	
	/**
	 * Returns a row from current resultset as an array .
	 *
	 *
	 * @return array The fetched row as an array
	 */
	function fetchRow($sql = null) {
		if (! empty ( $sql ) && is_string ( $sql ) && strlen ( $sql ) > 5) {
			if (! $this->execute ( $sql )) {
				return null;
			}
		}
		
		if (is_resource ( $this->_result ) || is_object ( $this->_result )) {
			$this->resultSet ( $this->_result );
			$resultRow = $this->fetchResult ();
			return $resultRow;
		} else {
			return null;
		}
	}
	
	/**
	 * Fetches the next row from the current result set
	 *
	 * @return unknown
	 */
	function fetchResult() {
		if ($row = mysqli_fetch_row($this->results)) {
			$resultRow = array();
			$i = 0;
			foreach ($row as $index => $field) {
				@list($table, $column) = $this->map[$index];
				$resultRow[$table][$column] = $row[$index];
				$i++;
			}
			return $resultRow;
		} else {
			return false;
		}
	}
	
	/**
	 * Sets the database encoding
	 *
	 * @param string $enc Database encoding
	 */
	function setEncoding($enc) {
		return $this->_execute('SET NAMES ' . $enc) != false;
	}
	
	/**
	 * Gets the database encoding
	 *
	 * @return string The database encoding
	 */
	function getEncoding() {
		return mysqli_client_encoding($this->connection);
	}

	/*
	 * build condition
	 *
	 * @param <array> $condition
	 * @example
	 * $condition = array(
	 * array('member_id' => '50'),
	 * array('member_dept_id' => 'IN (1,2,3)')
	 *
	 * @return String
	 * @example
	 * "WHERE member_id >= 50 AND member_dept_id IN (1,2,3)
	 */
	protected function buildConditions($condition) {
		if (empty($condition)) return;
		
		$sql = '';
		$index = 0;
		$conditionArray = array();
		
		foreach ( $condition as $field => $sub ) {
			$math = '';
			$subVal = explode(' ', $field);
			if (count($subVal) == 1) {
				$math = '=';
			} else {
				if (isset($subVal[1]) && !in_array($subVal[1], $this->keywords)) {
					$math = '=';
				}
			}
			
			$conditionArray[] = " " . $field . " " . $math . " '" . $sub . "'";
		}
		
		return " WHERE " . implode(' AND ', $conditionArray);
	}
	
	/**
	 *
	 * @param
	 *	Array or String orders like $orders => array('my_name'=>'ASC', 'colum_id'=>'DESC') or $orders => "my_name ASC, column DESC"
	 */
	protected function buildOrders($orders) {
		$sql = '';
		if (isset ( $orders )) {
			if (! is_array ( ! $orders ) && $orders != "") {
				$ordersCache = preg_replace ( '/,\s+/', ',', $orders );
				if (!is_array($ordersCache)) $ordersCache = explode ( ",", $ordersCache );
				$orders = array ();
				foreach ( $ordersCache as $item ) {
					$itemArr = explode ( " ", $item );
					if (isset ( $itemArr [1] )) {
						$orders [$itemArr [0]] = $itemArr [1];
					} else {
						$orders [$itemArr [0]] = 'ASC';
					}
				}
			}
			
			if (count ( $orders )) {
				$str = "";
				foreach ( $orders as $k => $v ) {
					$str .= ($k . " " . $v . ", ");
				}
				if ($str != "")
					$str = substr ( $str, 0, (strlen ( $str ) - strlen ( $str ) - 2) );
				$sql = "ORDER BY " . $str;
			}
		}
		return $sql;
	}
	
	/**
	 *
	 * @param <String> $mainTable
	 *        	manin table name
	 * @param <type> $joins        	
	 * @return string
	 */
	protected function buildJoins($mainTable, $joins) {
		$sql = '';
		if ($mainTable != "" && !empty( $joins )) {
			foreach ( $joins as $k => $v ) {
				$sql .= " " . (isset ( $v ['type'] ) ? $v ['type'] : "") . " JOIN " . $k . " ON " . $mainTable . "." . $v ['main_key'] . " = " . $k . "." . $v ['join_key'] . " ";
			}
		}
		return $sql;
	}
	
	/**
	 *
	 * @param <type> $groups
	 *        	is an array of name all fields for group
	 * @return string
	 */
	protected function buildGroups($groups) {
		$sql = '';
		if (isset ( $groups )) {
			if (! is_array ( ! $groups ) && $groups != "") {
				$groups = preg_replace ( '/,\s+/', ',', $groups );
				$groups = explode ( ",", $groups );
			}
			
			if (count ( $groups )) {
				$str = "";
				foreach ( $groups as $v ) {
					$str .= ($v . ", ");
				}
				if ($str != "")
					$str = substr ( $str, 0, (strlen ( $str ) - strlen ( $str ) - 2) );
				$sql = "GROUP BY " . $str;
			}
		}
		return $sql;
	}
	
	// default for mysql
	public function setLimit($sql, $limit = false, $offset = false) {
		if ($limit) {
			$rt = '';
			if (! strpos ( strtolower ( $limit ), 'limit' ) || strpos ( strtolower ( $limit ), 'limit' ) === 0) {
				$rt = ' LIMIT';
			}
			
			if ($offset) {
				$rt .= ' ' . $offset . ',';
			}
			
			$rt .= ' ' . $limit;
			$sql = $sql . $rt;
		}
		return $sql;
	}
	
	/**
	 * Set LIMIT for a SQL
	 * 
	 * @param unknown $limit
	 * @param string $offset
	 * @return string|NULL
	 */
	function limit($limit, $offset = null) {
		if ($limit) {
			$rt = '';
			if (! strpos ( strtolower ( $limit ), 'limit' ) || strpos ( strtolower ( $limit ), 'limit' ) === 0) {
				$rt = ' LIMIT';
			}
			
			if ($offset) {
				$rt .= ' ' . $offset . ',';
			}
			
			$rt .= ' ' . $limit;
			return $rt;
		}
		return null;
	}
	
	/**
	 * Render a SQL statement
	 * 
	 * @param unknown $type
	 * @param unknown $data
	 * @return string
	 */
	public function renderStatement($type, $data) {
		extract ( $data );
		$aliases = null;
		
		switch (strtolower ( $type )) {
			case 'select' :
				return "SELECT {$fields} FROM {$table} {$alias} {$joins} {$conditions} {$group} {$order} {$limit}";
				break;
			case 'create' :
				return "INSERT INTO {$table} ({$fields}) VALUES ({$values})";
				break;
			case 'update' :
				if (! empty ( $alias )) {
					$aliases = "{$this->alias}{$alias} {$joins} ";
				}
				return "UPDATE {$table} {$aliases}SET {$fields} {$conditions}";
				break;
			case 'delete' :
				if (! empty ( $alias )) {
					$aliases = "{$this->alias}{$alias} {$joins} ";
				}
				return "DELETE {$alias} FROM {$table} {$aliases}{$conditions}";
				break;
		}
	}
	
	/**
	 * Perform insert query
	 */
	function insert($myTable, $data) {
		$query = array ();
		$query ['table'] = $myTable;
		
		$fields = array ();
		$values = array ();
		
		foreach ( $data as $key => $val ) {
			$fields [] = $key;
			$values [] = $this->value($val);
		}
		$query ['fields'] = implode ( ', ', $fields );
		$query ['values'] = implode ( ', ', $values );
		
		if ($this->execute ( $this->renderStatement ( 'create', $query ) )) {
			return true;
		} else {
			return false;
		}
	}
	
	public function update($myTable, $myBean, $conditions = null) {
		if (isset($myBean['id']))
			unset($myBean['id']);
		$query = array ();
		$table = $myTable;
		
		$myUpdateContent = "";
		
		foreach ( $myBean as $key => $val ) {
			$field = $key;
			$value = $this->value($val);
			$myUpdateContent .= $field . " = " . $value . ", ";
		}
		$fields = substr ( $myUpdateContent, 0, (strlen ( $myUpdateContent ) - 2) );
		$conditions = $this->buildConditions ( $conditions );
		$alias = $joins = null;
		$query = compact ( 'table', 'alias', 'joins', 'fields', 'conditions' );
		
		if (! $this->execute ( $this->renderStatement ( 'update', $query ) )) {
			return false;
		}
		return true;
	}
	
	public function delete($myTable, $conditions = null) {
		$alias = $joins = null;
		$table = $myTable;
		$conditions = $this->buildConditions ( $conditions );
		
		if ($conditions === false) {
			return false;
		}
		
		if ($this->execute ( $this->renderStatement ( 'delete', compact ( 'alias', 'table', 'joins', 'conditions' ) ) ) === false) {
			return false;
		}
		return true;
	}
	
	/*
	 * Ham truu tuong hoa: lay du lieu, tuong duong cau truy van select dang don gian
	 * Tham so:
	 * $$tTableName: ten doi tuong du lieu, co the hieu la ten bang
	 * $tNameProperties: mang ten thuoc tinh doi tuong, co the hieu la ten cua cac truong can truy van
	 * $$tWhereClause: chuoi dieu kien cua ham, hay cua cau truy van
	 */
	function select($myTable, $options = array(), $isCount = false) {
		// Get options by parameters
		$myFields = isset($options['fields']) ? $options['fields'] : '*';
		$conditions = isset($options['conditions']) ? $options['conditions'] : null;
		$orders = isset($options['orders']) ? $options['orders'] : null;
		$groups = isset($options['groups']) ? $options['groups'] : null;
		$mJoins = isset($options['joins']) ? $options['joins'] : null;
		$mlimit = isset($options['limit']) ? $options['limit'] : false;
		$moffset = isset($options['offset']) ? $options['offset'] : false;
		
		try {
			$returnArr = array ();
			$table = $myTable;
			$alias = $joins = $order = $group = $limit = "";
			$fields = "";
			if (is_array ( $myFields )) {
				$fields = implode ( ', ', $myFields );
			} else {
				$fields = $myFields;
			}
			
			if (isset ( $mJoins ) && is_array ( $mJoins )) {
				foreach ( $mJoins as $jTable => $join ) {
					if (empty ( $jTable ) || empty ( $join ['join_key'] ) || empty ( $join ['main_key'] ) || ! isset ( $join ['join_fields'] ) || !isset ( $join ['join_fields'] [1] ))
						continue;
					$fields .= ", " . $jTable . "." . $join ['join_fields'] [0] . ", " . $jTable . "." . $join ['join_fields'] [1];
				}
			}
			
			$conditions = $this->buildConditions ( $conditions );
			$order = $this->buildOrders ( $orders );
			$group = $this->buildGroups ( $groups );
			$joins = $this->buildJoins ($myTable, $mJoins);
			
			$tmpTable = explode('_', $table);
			$alias = array();
			foreach ($tmpTable as $tmp) {
				$alias[] = ucfirst($tmp);
			}
			
			$alias = implode($alias);
			
			$query = compact ( 'table', 'alias', 'joins', 'fields', 'conditions', 'joins', 'group', 'order', 'limit' );
			$sql = $this->renderStatement ( 'select', $query );
			$sql = $this->setLimit ( $sql, $mlimit, $moffset );
			
			if ($isCount) 
				$returnArr = $this->fetchRow ( $sql );
			else
				$returnArr = $this->fetchAll ( $sql );
		} catch (Exception $ex) {
			var_dump($ex);
		}
		
		return $returnArr;
	}
}



