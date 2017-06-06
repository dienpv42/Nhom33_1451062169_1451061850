<?php
require_once(dirname(__FILE__).DS. "../base/AppModel.php");
require_once(dirname(__FILE__).DS. "../base/Helper.php");
require_once(dirname(__FILE__).DS. "../base/Session.php");

class MovieCategory extends AppModel {
	protected $table = 'movie_category';
	protected $alias = 'MovieCategory';
	
	private $session = null;
	
	protected $rules = array(
		"id" => array(
			"form" => array(
				"type" => "hidden"
			)
		),
		"title" => array(
			"form" => array(
				"type" => "text"
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
	
}