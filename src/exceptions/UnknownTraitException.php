<?php

namespace jeffpacks\cody\exceptions;

use Exception;

/**
 * Thrown when an unknown trait has been requested.
 */
class UnknownTraitException extends Exception {

	public function __construct(string $name) {
		parent::__construct("Unknown trait $name");
	}

}