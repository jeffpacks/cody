<?php

namespace jeffpacks\cody;

use jeffpacks\cody\interfaces\Importable;

class PhpDocBlock implements Importable {

	private ?string $description = null;
	private array $annotationGroups = [];
	private string $indentation;
	private array $annotationOrder = [];
	private array $parameters = [];
	private array $throws = [];
	private array $returnTypes = [];

	/**
	 * PhpDocBlock constructor.
	 *
	 * @param string $indentation The character or string to indent each line of this docblock with.
	 */
	public function __construct(string $indentation = '') {
		$this->indentation = $indentation;
	}

	/**
	 * Adds an annotation to this docblock.
	 *
	 * @param string $name The name of the annotation (not including any '@' character)
	 * @param string $content The content or description of the annotation
	 * @return PhpDocBlock This instance
	 */
	public function addAnnotation(string $name, string $content): PhpDocBlock {

		if (!isset($this->annotationGroups[$name])) {
			$this->annotationGroups[$name] = [];
		}

		$this->annotationGroups[$name][] = $content;

		return $this;

	}

	/**
	 * Adds a method parameter to this docblock.
	 *
	 * @param PhpParameter $parameter
	 * @return PhpDocBlock This instance
	 */
	public function addParameter(PhpParameter $parameter): PhpDocBlock {

		$this->parameters[$parameter->getName()] = $parameter;

		return $this;

	}

	/**
	 * Adds a throws annotation to this docblock.
	 *
	 * @param string $name The name of the throwable.
	 * @param string|null $description A description of the throw, if any.
	 * @return PhpDocBlock This instance
	 */
	public function addThrows(string $name, ?string $description): PhpDocBlock {

		$this->throws[$name] = $description;

		return $this;

	}

	/**
	 * Provides the annotations of this docblock.
	 *
	 * @return string[]
	 */
	public function getAnnotationGroups(): array {
		return $this->annotationGroups;
	}

	/**
	 * Provides the description of this docblock.
	 *
	 * @return string|null
	 */
	public function getDescription(): ?string {
		return $this->description;
	}

	/**
	 * Provides the parameters added to this docblock.
	 *
	 * @return PhpParameter[]
	 */
	public function getParameters(): array {
		return $this->parameters;
	}

	/**
	 * Provides the return types of this docblock.
	 *
	 * @return string[]
	 */
	public function getReturnTypes(): array {
		return $this->returnTypes;
	}

	/**
	 * Provides the throws annotations of this docblock, if any.
	 *
	 * @return string[] Zero or more name => description entries
	 */
	public function getThrows(): array {
		return $this->throws;
	}

	/**
	 * Sets in which order annotations should be sorted.
	 *
	 * @param string[] $order Zero or more annotation names, in the order they should be sorted.
	 * @return PhpDocBlock This instance
	 */
	public function setAnnotationOrder(array $order): PhpDocBlock {

		$this->annotationOrder = $order;

		return $this;

	}

	/**
	 * Sets the description of this docblock.
	 *
	 * @param string|null $description
	 * @return PhpDocBlock This instance
	 */
	public function setDescription(?string $description = null): PhpDocBlock {

		$this->description = $description;

		return $this;

	}

	/**
	 * Sets the return types of this docblock.
	 *
	 * @param string|string[]|PhpInterface|PhpInterface[]|PhpClass|PhpClass[]|null $types Zero or more primitive PHP value types, PhpInterface or PhpClass instances or their equivalent FQNs, or a comma seperated string of such.
	 * @return PhpDocBlock This instance
	 */
	public function setReturnTypes($types = null): PhpDocBlock {

		$this->returnTypes = PhpVariable::normalizeDataTypes($types);

		return $this;

	}

	/**
	 * Provides a string representation of this docblock.
	 *
	 * @return string
	 */
	public function __toString(): string {

		$string = "$this->indentation/**\n";
		if ($this->description) {
			$string .= "$this->indentation * $this->description.\n";
		}
		$string .= "$this->indentation * \n";

		foreach ($this->getParameters() as $parameter) {
			$string .= "$this->indentation * {$parameter->asAnnotation()}\n";
		}

		if ($this->returnTypes) {
			$string .= "$this->indentation * @return " . implode('|', $this->returnTypes) . "\n";
		} else {
			$string .= "$this->indentation * @return void\n";
		}

		foreach ($this->getThrows() as $name => $description) {
			$string .= "$this->indentation * @throws {$name}" . ($description ? " $description" : '') . "\n";
		}

		$annotationGroups = $this->annotationGroups;
		uksort($annotationGroups, fn($a, $b) => array_search($a, $this->annotationOrder) <=> array_search($b, $this->annotationOrder));

		foreach ($annotationGroups as $name => $annotations) {
			foreach ($annotations as $annotation) {
				$string .= "$this->indentation * @$name $annotation\n";
			}
		}

		$string .= "$this->indentation */\n";

		return $string;

	}

}