<?php

namespace jeffpacks\cody\exceptions;

use Exception;

/**
 * Thrown when an unknown method has been requested.
 */
class UnknownMethodException extends Exception {

	public function __construct(string $name) {
		parent::__construct("Unknown method $name");
	}

}