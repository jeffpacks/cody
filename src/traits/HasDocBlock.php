<?php

namespace jeffpacks\cody\traits;

use jeffpacks\cody\interfaces\HasDocBlock as HasDocBlockInterface;
use jeffpacks\cody\PhpClass;
use jeffpacks\cody\PhpInterface;
use jeffpacks\cody\PhpParameter;
use jeffpacks\cody\PhpVariable;

/**
 * @implements HasDocBlockInterface
 */
trait HasDocBlock {

	private array $description = [];
	private array $annotationGroups = [];
	private string $indentation;
	private array $annotationOrder = [];
	private array $parameters = [];
	private array $throws = [];
	private array $returnTypes = [];

	/**
	 * Adds an annotation to this docblock.
	 *
	 * @param string $name The name of the annotation (not including any '@' character)
	 * @param string $content The content or description of the annotation
	 * @return HasDocBlockInterface This instance
	 */
	public function addAnnotation(string $name, string $content): HasDocBlockInterface {

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
	 * @return HasDocBlockInterface This instance
	 */
	public function addParameter(PhpParameter $parameter): HasDocBlockInterface {

		$this->parameters[$parameter->getName()] = $parameter;

		return $this;

	}

	/**
	 * Adds a throws annotation to this docblock.
	 *
	 * @param string $name The name of the throwable.
	 * @param string|null $description A description of the throw, if any.
	 * @return HasDocBlockInterface This instance
	 */
	public function addThrows(string $name, ?string $description): HasDocBlockInterface {

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
	 * @return string[]
	 */
	public function getDescription(): array {
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
	 * @return HasDocBlockInterface This instance
	 */
	public function setAnnotationOrder(array $order): HasDocBlockInterface {

		$this->annotationOrder = $order;

		return $this;

	}

	/**
	 * Sets the description of this docblock.
	 *
	 * @param string|string[]|null $description Zero or more lines of description.
	 * @return HasDocBlockInterface This instance
	 */
	public function setDescription($description = null): HasDocBlockInterface {

		$this->description = (array) $description;

		return $this;

	}

	/**
	 * Sets the return types of this docblock.
	 *
	 * @param string|string[]|PhpInterface|PhpInterface[]|PhpClass|PhpClass[]|null $types Zero or more primitive PHP value types, PhpInterface or PhpClass instances or their equivalent FQNs, or a comma seperated string of such.
	 * @return HasDocBlockInterface This instance
	 */
	public function setReturnTypes($types = null): HasDocBlockInterface {

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
		foreach ($this->description as $index => $line) {
			$line = str_replace("\n", '', $line);
			if ($index === 0) {
				if (!in_array(substr($line, -1), ['.', '!', ','])) {
					$line .= '.';
				}
				$line .= "\n$this->indentation *";
			}
			$line .= "\n";
			$string .= "$this->indentation * $line";
		}

		foreach ($this->getParameters() as $parameter) {
			$string .= "$this->indentation * {$parameter->asAnnotation()}\n";
		}

		if ($this->returnTypes) {
			$string .= "$this->indentation * @return " . implode('|', $this->returnTypes) . "\n";
		} else {
			$string .= "$this->indentation * @return void\n";
		}

		foreach ($this->getThrows() as $name => $description) {
			$string .= "$this->indentation * @throws $name" . ($description ? " $description" : '') . "\n";
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