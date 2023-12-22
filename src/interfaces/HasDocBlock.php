<?php

namespace jeffpacks\cody\interfaces;

use jeffpacks\cody\PhpClass;
use jeffpacks\cody\PhpInterface;
use jeffpacks\cody\PhpParameter;

interface HasDocBlock {

	/**
	 * Adds an annotation to this docblock.
	 *
	 * @param string $name The name of the annotation (not including any '@' character)
	 * @param string $content The content or description of the annotation
	 * @return HasDocBlock This instance
	 */
	public function addAnnotation(string $name, string $content): HasDocBlock;

	/**
	 * Adds a throws annotation to this docblock.
	 *
	 * @param string $name The name of the throwable.
	 * @param string|null $description A description of the throw, if any.
	 * @return HasDocBlock This instance
	 */
	public function addThrows(string $name, ?string $description): HasDocBlock;

	/**
	 * Provides the annotations of this docblock.
	 *
	 * @return string[]
	 */
	public function getAnnotationGroups(): array;

	/**
	 * Provides the description of this docblock.
	 *
	 * @return string[]
	 */
	public function getDescription(): array;

	/**
	 * Provides the parameters added to this docblock.
	 *
	 * @return PhpParameter[]
	 */
	public function getParameters(): array;

	/**
	 * Provides the return types of this docblock.
	 *
	 * @return string[]
	 */
	public function getReturnTypes(): array;

	/**
	 * Provides the throws annotations of this docblock, if any.
	 *
	 * @return string[] Zero or more name => description entries
	 */
	public function getThrows(): array;

	/**
	 * Sets in which order annotations should be sorted.
	 *
	 * @param string[] $order Zero or more annotation names, in the order they should be sorted.
	 * @return HasDocBlock This instance
	 */
	public function setAnnotationOrder(array $order): HasDocBlock;

	/**
	 * Sets the description of this docblock.
	 *
	 * @param string|string[]|null $description Zero or more lines of description.
	 * @return HasDocBlock This instance
	 */
	public function setDescription($description = null): HasDocBlock;

	/**
	 * Sets the return types of this docblock.
	 *
	 * @param string|string[]|PhpInterface|PhpInterface[]|PhpClass|PhpClass[]|null $types Zero or more primitive PHP value types, PhpInterface or PhpClass instances or their equivalent FQNs, or a comma seperated string of such.
	 * @return HasDocBlock This instance
	 */
	public function setReturnTypes($types = null): HasDocBlock;

	/**
	 * Provides a string representation of this docblock.
	 *
	 * @return string
	 */
	public function __toString(): string;

}