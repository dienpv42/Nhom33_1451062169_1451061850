<?php
require_once(dirname(__FILE__).DS. "../base/AppModel.php");
require_once(dirname(__FILE__).DS. "../base/Helper.php");

class Calendar extends AppModel {
	protected $table = 'calendar';
	protected $alias = 'Calendar';
	
	public function __construct() {
		parent::__construct();
	}
	
}