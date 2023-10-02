<?php

namespace jeffpacks\cody\exceptions;

use Exception;

/**
 * Thrown when an unknown interface has been requested.
 */
class UnknownInterfaceException extends Exception {

	public function __construct(string $name) {
		parent::__construct("Unknown interface $name");
	}

}