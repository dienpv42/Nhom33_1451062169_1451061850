<?php
if (!class_exists("MysqlDriver")) require_once(dirname(__FILE__).DS. "MysqlDriver.php");
if (!class_exists("Form")) require_once(dirname(__FILE__).DS. "Form.php");

/**
 * Base class for all model
 * @author TrungBQ
 *
 */
class AppModel {
	/**
	 * Database connection
	 */
	protected $db = null;
	
	/**
	 * Alias to return the data
	 */
	protected $alias = null;
	
	/**
	 * Name of the table in database
	 */
	protected $table = '';
	
	/**
	 * Set the limit default when return results
	 */
	protected $limit = LIMIT;
	
	/**
	 * Current data this record is holding
	 */
	protected $data = null;
	
	/**
	 * Validation errors
	 */
	protected $errors = null;
	
	/**
	 * Form helper
	 */
	public $form = null;
	
	/**
	 * Rules to validate
	 */
	protected $rules = null;
	
	public function __construct() {
		// Create db instance
		$this->db = MysqlDriver::getInstance();
		
		// Set log query file path
		$this->db->setLogFile(dirname(__FILE__) . '/../logs/queries.log');
		
		// Form object
		$this->form = new Form();
		$this->form->setModel($this->alias);
		$this->form->setRules($this->rules);
	}
	
	public function save($data) {
		$this->data = $data;
		$this->form->data = $data;
		
		if (!$this->form->validate($this->data[$this->alias])) {
			return false;
		}
		
		if (isset($this->data[$this->alias]['id']) && !empty($this->data[$this->alias]['id'])) {
			$id = $this->data[$this->alias]['id'];
			return $this->db->update($this->table, $this->data[$this->alias], array('id' => $id));
		} else {
			unset($this->data[$this->alias]['id']);
			$saved = $this->db->insert($this->table, $this->data[$this->alias]);
			if ($saved) {
				$this->data[$this->alias]['id'] = $this->db->lastInsertId();
				return $saved;
			}
		}
	}

	public function findById($id) {
		$data = $this->find(array(
			'conditions' => array($this->alias.'.id' => $id)
		), 'first');
		
		$this->form->data = $data;
		
		return $data;
	}
	
	public function find($conditions, $first = 'all') {
		$results = $this->db->select($this->table, $conditions);
		
		if (!empty($results) && $first == 'first') {
			return $results[0];
		}
		
		return $results;
	}
	
	public function deleteById($id) {
		$this->db->delete($this->table, array(
			$this->table.'.id' => $id
		));
	}
	
	public function delete($conditions) {
		$this->db->delete($this->table, $conditions);
	}
	
	public function findAll() {
		return $this->db->select($this->table);
	}
}
