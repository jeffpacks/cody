<?php

namespace jeffpacks\cody;

use Exception;
use jeffpacks\cody\traits\ValueEncoder;
use jeffpacks\cody\exceptions\IncompatibleVariableValue;

/**
 * Represent a PHP variable.
 */
class PhpVariable {

	use ValueEncoder;

	private array $types;
	private string $name;
	private $value = null;
	private bool $hasNullValue = false;

	/**
	 * PhpVariable constructor.
	 *
	 * @param string $name The name of the variable, without the leading $ character.
	 * @param string|string[]|PhpInterface|PhpInterface[]|PhpClass|PhpClass[]|null $types Zero or more primitive PHP value types, PhpInterface or PhpClass instances or their equivalent FQNs, or a comma seperated string of such.
	 */
	public function __construct(string $name, $types = null) {

		$this->name = $name;

		$this->types = self::normalizeDataTypes($types);

	}

	/**
	 * Provides the name of this instance variable, without the leading $ character.
	 *
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * Provides the value types this instance variable can hold.
	 *
	 * @return array Zero or more primitive PHP value type identifiers or fully qualified PHP class or interface names.
	 */
	public function getTypes(): array {
		return $this->types;
	}

	/**
	 * Provides the initial value of this instance variable.
	 *
	 * @return mixed
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * Indicates whether this variable explicitly specifies NULL has its initial/default value.
	 *
	 * @return bool
	 */
	public function hasNullValue(): bool {
		return $this->hasNullValue;
	}

	/**
	 * Indicates whether this variable specifies any value type(s).
	 *
	 * @return bool
	 */
	public function hasTypes(): bool {
		return (bool) $this->types;
	}

	/**
	 * Indicates whether this variable has an initial/default value.
	 *
	 * If the value of this variable is null, this method will return true.
	 *
	 * @return bool
	 */
	public function hasValue(): bool {
		return $this->hasNullValue() || $this->value !== null;
	}

	/**
	 * Indicates whether this instance variable can be null.
	 *
	 * @return bool
	 */
	public function isNullable(): bool {
		return in_array('null', $this->types);
	}

	/**
	 * Provides a set of normalized data type identifiers based on the given data type identifiers.
	 *
	 * @param string|string[]|PhpInterface|PhpInterface[]|PhpClass|PhpClass[]|null $types Zero or more primitive PHP value types, PhpInterface or PhpClass instances or their equivalent FQNs, or a comma seperated string of such.
	 * @return string[]
	 */
	public static function normalizeDataTypes($types): array {

		$dataTypes = [];

		if ($types) {
			if (is_string($types)) {
				if (strstr($types, '|')) {
					$dataTypes = array_filter(
						array_map(fn(string $type) => strtolower($type), explode('|', $types)),
						fn($value) => $value !== 'mixed'
					);
				} elseif (strstr($types, '?')) {
					$dataTypes[] = 'null';
					$dataTypes[] = strtolower(str_replace('?', '', $types));
				} else {
					$dataTypes[] = $types;
				}
			} elseif (is_array($types)) {
				foreach ($types as $type) {
					if (is_string($type) && $type !== 'mixed') {
						$dataTypes[] = strtolower($type);
					} elseif ($type instanceof PhpInterface) {
						$dataTypes[] = strtolower($type->getFqn());
					} elseif ($type instanceof PhpClass) {
						$dataTypes[] = strtolower($type->getFqn());
					}
				}
			} elseif ($types instanceof PhpInterface) {
				$dataTypes[] = strtolower($types->getFqn());
			} elseif ($types instanceof PhpClass) {
				$dataTypes[] = strtolower($types->getFqn());
			}
		}

		$normalizedPrimitives = [
			'boolean' => 'bool',
			'integer' => 'int'
		];

		foreach ($dataTypes as $index => $dataType) {
			if (array_key_exists($dataType, $normalizedPrimitives)) {
				$dataTypes[$index] = $normalizedPrimitives[$dataType];
			}
		}

		return $dataTypes;

	}

	/**
	 * Sets whether this instance variable can be null.
	 *
	 * @param bool $nullable True to allow null, false to prevent null.
	 * @return PhpVariable This instance
	 */
	public function setNullable(bool $nullable = true): PhpVariable {

		if ($nullable) {
			if (!in_array('null', $this->types)) {
				$this->types[] = 'null';
			}
		} else {
			$this->types = array_filter($this->types, fn($value) => $value !== 'null');
		}

		return $this;

	}

	/**
	 * Sets the initial/default value of this instance variable.
	 *
	 * @param mixed $value The initial/default value, omit to remove the initial/default value.
	 * @return PhpVariable This instance
	 * @throws IncompatibleVariableValue
	 */
	public function setValue($value = null): PhpVariable {

		$this->validateValue($value);

		$this->hasNullValue = func_num_args() === 1 && $value === null;

		$this->value = $value;

		return $this;

	}

	/**
	 * Validates that a given value is compatible with the current data types of this variable.
	 *
	 * @param mixed $value
	 * @return void
	 * @throws IncompatibleVariableValue
	 */
	private function validateValue($value): void {

		$dataType = strtolower(gettype($value));

		$normalizedPrimitives = [
			'boolean' => 'bool',
			'integer' => 'int'
		];

		$dataType = array_key_exists($dataType, $normalizedPrimitives) ? $normalizedPrimitives[$dataType] : $dataType;

		if ($this->types && !in_array($dataType, $this->types)) {
			throw new IncompatibleVariableValue($this, $value);
		}

	}

	/**
	 * Provides the full PHP code representation of this instance variable.
	 *
	 * @return string
	 * @throws Exception
	 */
	public function __toString(): string {

		$string = '';

		switch (count($this->types)) {
			case 1:
				if (!$this->isNullable()) {
					$string .= reset($this->types) . ' ';
				}
				break;
			case 2:
				if ($this->isNullable()) {
					$string .= '?';
				}
				$type = array_filter($this->types, fn(string $type) => $type !== 'null');
				$string .= reset($type) . ' ';
				break;

		}

		$string .= "\$$this->name";

		if ($this->isNullable() || $this->value) {
			$string .= ' = ' . $this->encode($this->value);
		}

		return $string;

	}

}