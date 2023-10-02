<?php

namespace jeffpacks\cody;

use jeffpacks\cody\interfaces\HasInterfaces;
use jeffpacks\cody\interfaces\Implementable;
use jeffpacks\cody\exceptions\UnknownMethodException;

class PhpInterface extends PhpCitizen implements HasInterfaces, Implementable {

	use traits\HasInterfaces;

	protected array $methods = [];

	public function __clone() {
		$this->methods = array_map(fn(PhpMethod $method) => clone $method, $this->methods);
	}

	/**
	 * Creates a new PHP method.
	 *
	 * @param string $name The name of the method.
	 * @return PhpMethodSignature
	 */
	public function createMethod(string $name): PhpMethodSignature {
		return $this->methods[$name] = new PhpMethodSignature($name);
	}

	/**
	 * Provides a specific method from this interface.
	 *
	 * @param string $name The name of the method.
	 * @return PhpMethodSignature
	 * @throws UnknownMethodException
	 */
	public function getMethod(string $name): PhpMethodSignature {

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
	 * Provides the full PHP code representation of this PHP interface.
	 *
	 * @return string
	 */
	public function __toString(): string {

		$string = parent::__toString();

		$string .= "interface {$this->getName()} ";

		if ($interfaces = $this->getInterfaces()) {
			$string .= 'extends ' . implode(', ', $interfaces) . ' ';
		}

		$string .= "{\n\n";

		foreach ($this->getMethods() as $method) {
			$string .= "$method;\n\n";
		}

		$string .= "\n}";

		return $string;

	}

}