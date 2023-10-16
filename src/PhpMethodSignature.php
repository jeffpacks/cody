<?php

namespace jeffpacks\cody;

use Error;
use jeffpacks\cody\interfaces\Importable;
use jeffpacks\cody\interfaces\Implementable;
use jeffpacks\cody\exceptions\IncompatibleVariableValue;

class PhpMethodSignature implements Importable, Implementable {

	private string $name;
	private array $parameters = [];
	protected bool $isAbstract = false;
	private bool $isStatic = false;
	private array $returnTypes = [];
	private PhpDocBlock $docBlock;
	private string $accessModifier;

	/**
	 * PhpMethodSignature constructor.
	 *
	 * @param string $name The name of the method, not including parenthesis.
	 * @param string|null $accessModifier "public", "protected" or "private", defaults to "public"
	 */
	public function __construct(string $name, ?string $accessModifier = 'public') {

		$this->name = $name;
		$this->accessModifier = $accessModifier;

		$this->docBlock = new PhpDocBlock("\t");

	}

	public function __clone() {
		$this->docBlock = clone $this->docBlock;
		$this->parameters = array_map(fn(PhpParameter $parameter) => clone $parameter, $this->parameters);
	}

	/**
	 * Adds a parameter to this method.
	 *
	 * @param string $name The name of the parameter.
	 * @param string|null $type The value type of the parameter, e.g. "string", "?string", null for "mixed".
	 * @param mixed $defaultValue The default value of the parameter, null for "null", omit for none.
	 * @return PhpParameter The new parameter
	 * @throws IncompatibleVariableValue
	 */
	public function createParameter(string $name, ?string $type = null, $defaultValue = null): PhpParameter {

		switch(func_num_args()) {
			case 3:
				$parameter = $this->parameters[$name] = new PhpParameter($name, $type, $defaultValue);
				break;
			case 2:
				$parameter = $this->parameters[$name] = new PhpParameter($name, $type);
				break;
			default:
				$parameter = $this->parameters[$name] = new PhpParameter($name);
				break;
		}

		$this->docBlock->addParameter($parameter);

		return $parameter;

	}

	/**
	 * Adds a throws annotation this method's docblock.
	 *
	 * @param string $name Name of the throwable class.
	 * @param string|null $description A description of when or why the throwable is thrown.
	 * @return PhpMethod This instance
	 */
	public function addThrows(string $name, ?string $description = null): PhpMethodSignature {

		$this->docBlock->addThrows($name,$description);

		return $this;

	}

	/**
	 * Provides the description of this method, if any.
	 *
	 * @return string|null
	 */
	public function getDescription(): ?string {
		return $this->docBlock->getDescription();
	}

	/**
	 * Provides the docblock of this method.
	 *
	 * @return PhpDocBlock
	 */
	public function getDocBlock(): PhpDocBlock {
		return $this->docBlock;
	}

	/**
	 * Provides the name of this PHP citizen.
	 *
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * Provides the parameter names and value types of this PHP method, if any.
	 *
	 * @return string[]
	 */
	public function getParameters(): array {
		return $this->parameters;
	}

	/**
	 * Provides the return types of this method.
	 *
	 * @return string[]
	 */
	public function getReturnTypes(): array {
		return $this->returnTypes;
	}

	/**
	 * Imports an importable object into this method.
	 *
	 * @param Importable $importable
	 * @return Importable
	 * @throws IncompatibleVariableValue
	 */
	public function import(Importable $importable): Importable {

		if ($importable instanceof PhpDocBlock) {
			$this->docBlock = clone $importable;
			return $this->docBlock;
		} elseif ($importable instanceof PhpParameter) {
			return $this->createParameter(clone $importable);
		} elseif ($importable instanceof PhpMethodSignature) {
			$this->name = $importable->name;
			$this->docBlock = clone $importable->docBlock;
			foreach ($importable->parameters as $name => $parameter) {
				$this->parameters[$name] = clone $parameter;
			}
			$this->isStatic = $importable->isStatic;
			$this->returnTypes = $importable->returnTypes;
			$this->accessModifier = $importable->accessModifier;
			return $this;
		}

		throw new Error('Unable to import ' . get_class($importable));

	}

	/**
	 * Indicates whether this is an abstract method.
	 *
	 * @return bool
	 */
	public function isAbstract(): bool {
		return $this->isAbstract;
	}

	/**
	 * Indicates whether this is a static method.
	 *
	 * @return bool
	 */
	public function isStatic(): bool {
		return $this->isStatic;
	}

	/**
	 * Sets the description of this PHP method.
	 *
	 * @param string|null $description
	 * @return PhpMethod This instance
	 */
	public function setDescription(?string $description = null): PhpMethodSignature {

		$this->docBlock->setDescription($description);

		return $this;

	}

	/**
	 * Sets the return types of this method.
	 *
	 * @param string|string[]|PhpInterface|PhpInterface[]|PhpClass|PhpClass[]|null $types Zero or more primitive PHP value types, PhpInterface or PhpClass instances or their equivalent FQNs, or a comma seperated string of such.
	 * @return PhpMethodSignature This instance
	 */
	public function setReturnTypes($types): PhpMethodSignature {

		$this->returnTypes = PhpVariable::normalizeDataTypes($types);
		$this->docBlock->setReturnTypes($types);

		return $this;

	}

	/**
	 * Provides the full PHP code representation of this PHP citizen.
	 *
	 * @return string
	 */
	public function __toString(): string {

		$string = "$this->docBlock";

		$string .= "\t$this->accessModifier ";

		if ($this->isAbstract()) {
			$string .= 'abstract ';
		}

		if ($this->isStatic()) {
			$string .= 'static ';
		}

		$string .= "function {$this->getName()}(" . implode(', ', $this->getParameters()) . ')';

		# Return type
		if (in_array('null', $this->returnTypes)) {
			if (count($this->returnTypes) === 2) {
				$returnType = array_filter($this->returnTypes, fn(string $type) => $type !== 'null');
				$string .= ': ?' . reset($returnType);
			}
		} else {
			if (count($this->returnTypes) === 1) {
				$string .= ': ' . reset($this->returnTypes);
			}
		}

		return $string;

	}

}