<?php

namespace jeffpacks\cody\interfaces;

use jeffpacks\cody\PhpMethod;

interface Implementor {

	/**
	 * Creates a new PHP method.
	 *
	 * @param string $name The name of the method.
	 * @return PhpMethod
	 */
	public function createMethod(string $name): PhpMethod;

	/**
	 * Provides the methods of this implementor.
	 *
	 * @return PhpMethod[]
	 */
	public function getMethods(): array;

	/**
	 * Imports a given method or interface.
	 *
	 * @param Implementable $implementable The method or interface to implement.
	 * @return Implementor This instance
	 */
	public function implement(Implementable $implementable): Implementor;

}