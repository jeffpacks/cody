<?php

namespace jeffpacks\cody\interfaces;

use Closure;
use jeffpacks\cody\PhpClass;
use jeffpacks\cody\PhpMethod;
use jeffpacks\cody\PhpInterface;
use jeffpacks\cody\PhpInstanceVariable;
use jeffpacks\cody\exceptions\UnknownMethodException;
use jeffpacks\cody\exceptions\UnknownVariableException;

interface Implementor {

	/**
	 * Creates a new PHP method.
	 *
	 * @param string $name The name of the method.
	 * @return PhpMethod
	 */
	public function createMethod(string $name): PhpMethod;

	/**
	 * Creates a new instance variable in this class/trait.
	 *
	 * @param string $name The name of the variable.
	 * @param string|string[]|PhpInterface|PhpInterface[]|PhpClass|PhpClass[]|null $types Zero or more primitive PHP value types, PhpInterface or PhpClass instances or their equivalent FQNs, or a comma seperated string of such.
	 * @return PhpInstanceVariable The new variable
	 */
	public function createVariable(string $name, $types = null): PhpInstanceVariable;

	/**
	 * Provides a specific method.
	 *
	 * @param string $name The name of the method.
	 * @param Closure|null $fallback A closure fn(string $requestedName, Implementor $owner) that will return a PhpMethod object if the requested method does not exist.
	 * @return PhpMethod
	 * @throws UnknownMethodException
	 */
	public function getMethod(string $name, ?Closure $fallback = null): PhpMethod;

	/**
	 * Provides the methods of this implementor.
	 *
	 * @return PhpMethod[]
	 */
	public function getMethods(): array;

	/**
	 * Provides a specific instance variable.
	 *
	 * @param string $name The name of the variable.
	 * @param Closure|null $fallback A closure fn(string $requestedName, HasVariables $owner) that will return a PhpVariable object if the requested variable does not exist.
	 * @return PhpInstanceVariable
	 * @throws UnknownVariableException
	 */
	public function getVariable(string $name, ?Closure $fallback = null): PhpInstanceVariable;

	/**
	 * Provides the instance variables of this class/trait.
	 *
	 * @return PhpInstanceVariable[] Zero or more instance variables
	 */
	public function getVariables(): array;

	/**
	 * Indicates whether this implementor has a given method.
	 *
	 * @param string $name The name of the method.
	 * @return bool
	 */
	public function hasMethod(string $name): bool;

	/**
	 * Indicates whether this class/trait has a given instance variable.
	 *
	 * @param string $name The name of the variable.
	 * @return bool
	 */
	public function hasVariable(string $name): bool;

	/**
	 * Imports a given method or interface.
	 *
	 * @param Implementable $implementable The method or interface to implement.
	 * @return Implementor This instance
	 */
	public function implement(Implementable $implementable): Implementor;

}