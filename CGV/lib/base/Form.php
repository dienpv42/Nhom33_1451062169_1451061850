<?php
if (!class_exists("Validator")) require_once(dirname(__FILE__).DS. "Validator.php");

class Form {
	
	/**
	 * Rules to validate
	 * "username" => array(
	 *		"notEmpty" => array(
	 *			"rule" => "notEmpty",
	 *			"message" => MSG_ERR_NOTEMPTY
	 *		)
	 *	)
	 */
	protected $rules = null;
	
	/**
	 * Validator object to validate input value
	 */
	protected $validator = null;
	
	/**
	 * Default model name
	 */
	private $model = 'Model';
	
	/**
	 * Error of inputs
	 */
	private $errors = null;
	
	/**
	 * Type of the input
	 */
	public $inputs = array();
	
	/**
	 * Data of this form
	 */
	public $data = array();
	
	public function __construct() {
		// Validator object
		$this->validator = new Validator();
	}
	
	public function input($name) {
		$type = 'text';
		
		foreach ($this->inputs as $field => $input) {
			if ($field == $name) {
				$type = $input['type'];
				$style = isset($input['style']) ? ' style="' . $input['style'] . '"' : '';
				
				switch ($type) {
					case 'text':
						$inputField = '<input type="text"' .$style .' name="data[' . $this->model . '][' . $name . ']"' . ' value="' . (isset($this->data[$this->model][$name]) ? $this->data[$this->model][$name] : '') . '" />';
						break;
					case 'textarea':
						$inputField = '<textarea name="data[' . $this->model . '][' . $name . ']"' .$style .'>' . (isset($this->data[$this->model][$name]) ? $this->data[$this->model][$name] : '') . '</textarea>';
						break;
					case 'password':
						$inputField = '<input type="password"' .$style .' name="data[' . $this->model . '][' . $name . ']"' . ' value="' . (isset($this->data[$this->model][$name]) ? $this->data[$this->model][$name] : '') . '" />';
						break;
					case 'datepicker':
						$inputField = '<input type="text"' .$style .' class="datepicker" name="data[' . $this->model . '][' . $name . ']"' . ' value="' . (isset($this->data[$this->model][$name]) ? $this->data[$this->model][$name] : '') . '" />';
						$inputField .= '
<script type="text/javascript">
$(function() {
	$(".datepicker").datepicker({ dateFormat: \'yy-mm-dd\' });
});
</script>
';
						break;
					case 'select':
						$options = $input['options'];
						$inputField = '<select' .$style .' name="data[' . $this->model . '][' . $name . ']">';
						foreach ($options as $optId => $opt) {
							$inputField .= '<option value="' . $optId . '" ' . (isset($this->data[$this->model][$name]) && $this->data[$this->model][$name] == $optId ? 'selected' : '') . '>' . $opt . '</option>';
						}
						$inputField .= '</select>';
						break;
					case 'hidden':
						$inputField = '<input type="hidden" name="data[' . $this->model . '][' . $name . ']"' . ' value="' . (isset($this->data[$this->model][$name]) ? $this->data[$this->model][$name] : '') . '" />';
						break;
/*					case 'file':
						$inputField = '<input type="file"' .$style .' name="data[' . $this->model . '][' . $name . ']"' . ' value="' . (isset($this->data[$this->model][$name]) ? $this->data[$this->model][$name] : '') . '" />';
						break;*/
				}
				break;
			}
		}
		
		echo $inputField;
	}
	
	public function setModel($model) {
		$this->model = $model;
	}
	
	public function setRules($rules) {
		if (!empty($rules)) {
			$this->rules = $rules;
			
			foreach ($rules as $field => $rule) {
				if (isset($rule['form'])) {
					$this->inputs[$field] = $rule['form'];
				}
			}
		}
	}
	
	/**
	 * Validate all data with errors
	 */
	public function validate($data) {
		$validate = $this->validator->validate($data, $this->rules);
		$this->errors = $this->validator->getErrors();
		
		return $validate;
	}
	
	public function error($field) {
		if (!empty($this->errors[$field])) {
			echo '<p class="err">'.$this->errors[$field].'</p>';
		}
	}
	
	public function getErrors() {
		return $this->errors;
	}
}




