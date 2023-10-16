<?php

namespace jeffpacks\cody;

use jeffpacks\cody\interfaces\Importable;
use jeffpacks\cody\exceptions\IncompatibleVariableValue;

/**
 * Represents a PHP method parameter.
 */
class PhpParameter extends PhpVariable implements Importable {

	private ?string $description = null;

	/**
	 * PhpParameter constructor.
	 *
	 * @param string $name The name of the variable, without the leading $ character.
	 * @param string|string[]|PhpInterface|PhpInterface[]|PhpClass|PhpClass[]|null $types Zero or more primitive PHP value types, PhpInterface or PhpClass instances or their equivalent FQNs, or a comma seperated string of such.
	 * @param mixed $defaultValue The default value of the parameter, omit for none.
	 * @throws IncompatibleVariableValue
	 */
	public function __construct(string $name, $types = null, $defaultValue = null) {

		parent::__construct($name, $types);

		if (func_num_args() > 2) {
			$this->setValue($defaultValue);
		}

	}

	/**
	 * Provides a doc-block annotation representation of this parameter.
	 *
	 * @return string
	 */
	public function asAnnotation(): string {

		$string = '@param ';

		if ($this->hasTypes()) {
			$string .= implode('|', $this->getTypes()) . ' ';
		} else {
			$string .= 'mixed ';
		}

		$string .= "\${$this->getName()}";

		if ($this->hasDescription()) {
			$string .= " {$this->getDescription()}";
		}

		return $string;

	}

	/**
	 * Provides the description of this parameter, if any.
	 *
	 * @return string|null
	 */
	public function getDescription(): ?string {
		return $this->description;
	}

	/**
	 * Indicates whether this parameter has a description.
	 *
	 * @return bool
	 */
	public function hasDescription(): bool {
		return $this->description !== null;
	}

	/**
	 * Sets the description of this parameter.
	 *
	 * @param string|null $description
	 * @return PhpParameter This instance
	 */
	public function setDescription(?string $description = null): PhpParameter {

		$this->description = $description;

		return $this;

	}

}