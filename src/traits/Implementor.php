<?php

namespace jeffpacks\cody\traits;

use Closure;
use jeffpacks\cody\PhpClass;
use jeffpacks\cody\PhpMethod;
use jeffpacks\cody\PhpInterface;
use jeffpacks\cody\PhpMethodSignature;
use jeffpacks\cody\PhpInstanceVariable;
use jeffpacks\cody\interfaces\Implementable;
use jeffpacks\cody\interfaces\Implementor as ImplementorInterface;
use jeffpacks\cody\exceptions\UnknownMethodException;
use jeffpacks\cody\exceptions\UnknownVariableException;

trait Implementor {

	protected array $methods = [];
	protected array $variables = [];

	public function __clone() {
		$this->methods = array_map(fn(PhpMethod $method) => clone $method, $this->methods);
		$this->variables = array_map(fn(PhpInstanceVariable $variable) => clone $variable, $this->variables);
	}

	/**
	 * Creates a new PHP method.
	 *
	 * @param string $name The name of the method.
	 * @return PhpMethod
	 */
	public function createMethod(string $name): PhpMethod {
		return $this->methods[$name] = new PhpMethod($name);
	}

	/**
	 * Creates a new instance variable in this class/trait.
	 *
	 * @param string $name The name of the variable.
	 * @param string|string[]|PhpInterface|PhpInterface[]|PhpClass|PhpClass[]|null $types Zero or more primitive PHP value types, PhpInterface or PhpClass instances or their equivalent FQNs, or a comma seperated string of such.
	 * @return PhpInstanceVariable The new variable
	 */
	public function createVariable(string $name, $types = null): PhpInstanceVariable {
		return $this->variables[$name] = new PhpInstanceVariable($name, $types);
	}

	/**
	 * Provides a specific method from this implementor.
	 *
	 * @param string $name The name of the method.
	 * @param Closure|null $fallback A closure fn(string $requestedName, Implementor $owner) that will return a PhpMethod object if the requested method does not exist.
	 * @return PhpMethod
	 * @throws UnknownMethodException
	 */
	public function getMethod(string $name, ?Closure $fallback = null): PhpMethod {

		if (!isset($this->methods[$name])) {
			if ($fallback) {
				return $fallback($name, $this);
			}
			throw new UnknownMethodException($name);
		}

		return $this->methods[$name];

	}

	/**
	 * Provides the methods of this implementor.
	 *
	 * @return PhpMethod[]
	 */
	public function getMethods(): array {
		return $this->methods;
	}

	/**
	 * Provides a specific instance variable.
	 *
	 * @param string $name The name of the variable.
	 * @param Closure|null $fallback A closure fn(string $requestedName, HasVariables $owner) that will return a PhpVariable object if the requested variable does not exist.
	 * @return PhpInstanceVariable
	 * @throws UnknownVariableException
	 */
	public function getVariable(string $name, ?Closure $fallback = null): PhpInstanceVariable {

		if (!isset($this->variables[$name])) {
			if ($fallback) {
				return $fallback($name, $this);
			}
			throw new UnknownVariableException($name);
		}

		return $this->variables[$name];

	}

	/**
	 * Provides the instance variables of this implementor.
	 *
	 * @return PhpInstanceVariable[] Zero or more instance variables
	 */
	public function getVariables(): array {
		return $this->variables;
	}

	/**
	 * Indicates whether this implementor has a given instance variable.
	 *
	 * @param string $name The name of the variable.
	 * @return bool
	 */
	public function hasMethod(string $name): bool {
		return isset($this->methods[$name]);
	}

	/**
	 * Indicates whether this implementor has a given instance variable.
	 *
	 * @param string $name The name of the variable.
	 * @return bool
	 */
	public function hasVariable(string $name): bool {
		return isset($this->variables[$name]);
	}

	/**
	 * Creates method implementations of a given method or the methods defined by a given interface.
	 *
	 * @param Implementable $implementable The interface or method to implement.
	 * @param string $methodBody The method's body, if a method is being implemented.
	 * @return ImplementorInterface This instance
	 */
	public function implement(Implementable $implementable, string $methodBody = ''): ImplementorInterface {

		if ($implementable instanceof PhpMethodSignature) {
			$method = $this->methods[$implementable->getName()] = new PhpMethod($implementable->getName());
			$method->import($implementable);
			$method->setBody($methodBody);
		} elseif ($implementable instanceof PhpInterface) {
			foreach ($implementable->getMethods() as $method) {
				$this->implement($method);
			}
		}

		return $this;

	}

}