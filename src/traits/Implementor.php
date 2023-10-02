<?php

namespace jeffpacks\cody\traits;

use jeffpacks\cody\exceptions\UnknownMethodException;
use jeffpacks\cody\PhpMethod;
use jeffpacks\cody\PhpInterface;
use jeffpacks\cody\interfaces\Implementable;
use jeffpacks\cody\interfaces\Implementor as ImplementorInterface;
use jeffpacks\cody\PhpMethodSignature;

trait Implementor {

	protected array $methods = [];

	public function __clone() {
		$this->methods = array_map(fn(PhpMethod $method) => clone $method, $this->methods);
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
	 * Provides a specific method from this implementor.
	 *
	 * @param string $name The name of the method.
	 * @return PhpMethod
	 * @throws UnknownMethodException
	 */
	public function getMethod(string $name): PhpMethod {

		if (!isset($this->methods[$name])) {
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