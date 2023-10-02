<?php

namespace jeffpacks\cody\exceptions;

use Exception;

/**
 * Thrown when an unknown class has been requested.
 */
class UnknownClassException extends Exception {

	public function __construct(string $name) {
		parent::__construct("Unknown class $name");
	}

}