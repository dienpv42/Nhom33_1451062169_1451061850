<?php
require_once(dirname(__FILE__).DS. "../base/AppModel.php");
require_once(dirname(__FILE__).DS. "../base/Helper.php");
require_once(dirname(__FILE__).DS. "../base/Session.php");
require_once(dirname(__FILE__).DS. "../model/MovieCategory.php");

class Movie extends AppModel {
	protected $table = 'movie';
	protected $alias = 'Movie';
	
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
		),
		"duration" => array(
			"form" => array(
				"type" => "text"
			),
			"notEmpty" => array(
				"rule" => "notEmpty",
				"message" => MSG_ERR_NOTEMPTY
			),
			"isNumber" => array(
				"rule" => "isNumber",
				"message" => MSG_ERR_NUMER
			)
		),
		"director" => array(
			"form" => array(
				"type" => "text"
			),
			"notEmpty" => array(
				"rule" => "notEmpty",
				"message" => MSG_ERR_NOTEMPTY
			)
		),
		"actor" => array(
			"form" => array(
				"type" => "text"
			),
			"notEmpty" => array(
				"rule" => "notEmpty",
				"message" => MSG_ERR_NOTEMPTY
			)
		),
		"language" => array(
			"form" => array(
				"type" => "text"
			),
			"notEmpty" => array(
				"rule" => "notEmpty",
				"message" => MSG_ERR_NOTEMPTY
			)
		),
		"country" => array(
			"form" => array(
				"type" => "select"
			),
			"notEmpty" => array(
				"rule" => "notEmpty",
				"message" => MSG_ERR_NOTEMPTY
			)
		),
		"description" => array(
			"form" => array(
				"type" => "textarea"
			),
			"notEmpty" => array(
				"rule" => "notEmpty",
				"message" => MSG_ERR_NOTEMPTY
			)
		),
		"open_date" => array(
			"form" => array(
				"type" => "datepicker"
			),
			"notEmpty" => array(
				"rule" => "notEmpty",
				"message" => MSG_ERR_NOTEMPTY
			)
		),
		"trial_url" => array(
			"form" => array(
				"type" => "text",
				"style" => "width: 150px"
			),
			"notEmpty" => array(
				"rule" => "notEmpty",
				"message" => MSG_ERR_NOTEMPTY
			)
		),
		"category_id" => array(
			"form" => array(
				"type" => "select",
				"options" => array()
			),
			"notEmpty" => array(
				"rule" => "notEmpty",
				"message" => MSG_ERR_NOTEMPTY
			)
		)
	);
	
	public function __construct() {
		$category = new MovieCategory();
		$catList = $category->findAll();
		
		$cats = array();
		if (!empty($catList)) {
			foreach ($catList as $cat) {
				$cats[$cat['MovieCategory']['id']] = $cat['MovieCategory']['title'];
			}
		}
		
		$this->rules['country']['form']['options'] = unserialize(M_COUNTRY);
		$this->rules['category_id']['form']['options'] = $cats;
		
		parent::__construct();
		
		$this->session = new Session();
	}
	
}