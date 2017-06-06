<?php

class Validator {
	private $errors = array();
	private $data = array();
	private $rules = array();
	
	public function __construct() {}

	public function validate($data, $rules) {
		$this->data = $data;
		$this->rules = $rules;
		$isOK = true;
		

		foreach ($this->data as $key => $value) {
			if (isset($this->rules[$key])) {
				foreach ($this->rules[$key] as $valKey => $valData) {
					if ($valKey == 'form') continue;
					
					$rule = '';
					$message = '';
					$param = '';
					
					if (is_array($valData['rule'])) {
						$rule = $valData['rule'][0];
						$param = isset($valData['rule'][1]) ? $valData['rule'][1] : '';
					} else {
						$rule = $valData['rule'];
					}
					if (is_array($valData['message'])) {
						$message = sprintf($valData['message'][0], $valData['message'][1]);
					} else {
						$message = $valData['message'];
					}
					
					if (method_exists($this, $rule)) {
						if (!empty($param)) {
							if (!$this->{$rule}($value, $param)) {
								$this->errors[$key] = $message;
								$isOK = false;
							}
						} else {
							if (!$this->{$rule}($value)) {
								$this->errors[$key] = $message;
								$isOK = false;
							}
						}
					}
				}
			}
		}
		
		return $isOK;
	}
	

	

	private function maxLength($value, $length) {
		return (mb_strlen($value) < $length);
	}
	

	private function notEmpty($value) {
		return !empty($value);
	}
	

	private function isNumber($value) {
		if (empty($value)) return true;
		
		return is_numeric($value);
	}
	
	private function isOfficeTel($value) {
		return preg_match("/^\d{3}(-*)\d{4}$/", $value);
	}
	

	private function is_tel($value) {
		if (empty($value)) return true;
		if(preg_match("/^\d+(-*)\d+(-*)\d+$/", $value)) {
			return true;
		} else {
			return false;
		}
	}
	

	private function email($value) {
		if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $value)) {
			return true;
		}
		else {
			return false;
		}
	}
	

	public function getErrors() {
		return $this->errors;
	}
}