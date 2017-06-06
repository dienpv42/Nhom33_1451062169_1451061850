<?php
require_once(dirname(__FILE__).DS. "../base/AppModel.php");
require_once(dirname(__FILE__).DS. "../base/Helper.php");
require_once(dirname(__FILE__).DS. "../base/Session.php");

class User extends AppModel {
	protected $table = 'user';
	protected $alias = 'User';
	
	private $session = null;
	
	protected $rules = array(
		"email" => array(
			"form" => array(
				"type" => "text"
			),
			"notEmpty" => array(
				"rule" => "notEmpty",
				"message" => MSG_ERR_NOTEMPTY
			),
			"isEmail" => array(
				"rule" => "email",
				"message" => MSG_ERR_EMAIL
			)
		),
		"password" => array(
			"form" => array(
				"type" => "password"
			),
			"notEmpty" => array(
				"rule" => "notEmpty",
				"message" => MSG_ERR_NOTEMPTY
			)
		),
		"fullname" => array(
			"form" => array(
				"type" => "text"
			),
			"notEmpty" => array(
				"rule" => "notEmpty",
				"message" => MSG_ERR_NOTEMPTY
			)
		),
		"address" => array(
			"form" => array(
				"type" => "textarea"
			),
			"notEmpty" => array(
				"rule" => "notEmpty",
				"message" => MSG_ERR_NOTEMPTY
			)
		)
	);
	
	public function __construct() {
		parent::__construct();
		
		$this->session = new Session();
	}
	
	public function saveLogin($data) {
		$data[$this->alias]['password'] = Helper::hash($data[$this->alias]['password']);
		
		return parent::save($data);
	}
	
	public function login($data) {
		$exists = $this->find(array(
			'conditions' => array(
				'email' => $data[$this->alias]['email'],
				'password' => Helper::hash($data[$this->alias]['password'])
			)
		), 'first');
		if (!empty($exists)) {
			// Login user
			$this->session->write(USER_INFO, $exists);
			$this->session->write(LOGGED_IN, true);
			
			return true;
		}
		
		return false;
	}
	
	public function isLoggedIn() {
		return $this->session->read(LOGGED_IN);
	}
	
	public function logout() {
		$this->session->destroy();
	}
	
	public function isAdmin() {
		$data = $this->session->read(USER_INFO);
		
		return $data[$this->alias]['is_admin'];
	}
}