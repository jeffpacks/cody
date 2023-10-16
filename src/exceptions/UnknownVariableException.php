<?php

namespace jeffpacks\cody\exceptions;

use Exception;

/**
 * Thrown when an unknown instance variable has been requested.
 */
class UnknownVariableException extends Exception {

	public function __construct(string $name) {
		parent::__construct("Unknown instance variable $name");
	}

}