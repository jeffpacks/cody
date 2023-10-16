<?php

namespace jeffpacks\cody\exceptions;

use Exception;
use jeffpacks\cody\PhpVariable;

/**
 * Thrown when a variable is attempted assigned a value that is incompatible with the variable's data type.
 */
class IncompatibleVariableValue extends Exception {

	private PhpVariable $variable;
	private $incompatibleValue;

	public function __construct(PhpVariable $variable, $value) {

		$this->variable = $variable;
		$this->incompatibleValue = $value;

		parent::__construct("An incompatible value was attempted set for the variable «{$variable->getName()}» which only accepts the following: " . implode(', ', $variable->getTypes()));

	}

	/**
	 * Provides the value that is considered incompatible with the variable.
	 *
	 * @return mixed
	 */
	public function getIncompatibleValue() {
		return $this->incompatibleValue;
	}

	/**
	 * Provides the variable.
	 *
	 * @return PhpVariable
	 */
	public function getVariable(): PhpVariable {
		return $this->variable;
	}

}