<?php

namespace jeffpacks\cody;

use Exception;
use jeffpacks\cody\traits\ValueEncoder;
use jeffpacks\cody\interfaces\Importable;

/**
 * Represents a PHP method parameter.
 */
class PhpParameter implements Importable {

	use ValueEncoder;

	private string $name;
	private ?string $type;
	private $defaultValue;
	private ?string $description = null;
	private bool $hasNullDefaultValue;

	/**
	 * PhpParameter constructor.
	 *
	 * @param string $name The name of the parameter.
	 * @param string|null $type The value type of the parameter, e.g. "string", "?string", null for "mixed".
	 * @param mixed $defaultValue The default value of the parameter, null for "null", omit for none.
	 */
	public function __construct(string $name, ?string $type = null, $defaultValue = null) {

		$this->name = $name;
		$this->type = $type;
		$this->defaultValue = $defaultValue;
		$this->hasNullDefaultValue = func_num_args() === 3 && $defaultValue === null;

	}

	/**
	 * Provides the name of this parameter.
	 *
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * Provides the default value of this parameter, if any.
	 *
	 * @return mixed
	 */
	public function getDefaultValue() {
		return $this->defaultValue;
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
	 * Provides the type of this parameter, if any.
	 *
	 * @return string|null
	 */
	public function getType(): ?string {
		return $this->type;
	}

	/**
	 * Indicates whether this parameter specifies a default value.
	 *
	 * @return bool
	 */
	public function hasDefaultValue(): bool {
		return $this->hasNullDefaultValue() || $this->defaultValue !== null;
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
	 * Indicates whether the default value of this parameter, if any, is null.
	 *
	 * @return bool
	 */
	public function hasNullDefaultValue(): bool {
		return $this->hasNullDefaultValue;
	}

	/**
	 * Indicates whether this parameter specifies a value type.
	 *
	 * @return bool
	 */
	public function hasType(): bool {
		return $this->type !== null;
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

	/**
	 * Provides the full PHP code representation of this PHP class.
	 *
	 * @return string
	 * @throws Exception
	 */
	public function __toString(): string {

		$string = '';
		if ($this->hasType()) {
			$string .= "{$this->getType()} ";
		}

		$string .= "\${$this->getName()}";

		if ($this->hasDefaultValue()) {
			$string .= " = {$this->encode($this->getDefaultValue())}";
		}

		return $string;

	}

}