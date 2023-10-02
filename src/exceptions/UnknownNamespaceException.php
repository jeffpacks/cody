<?php

namespace jeffpacks\cody\exceptions;

use Exception;

/**
 * Thrown when an unknown namespace has been requested.
 */
class UnknownNamespaceException extends Exception {

	public function __construct(string $name) {
		parent::__construct("Unknown namespace $name");
	}

}