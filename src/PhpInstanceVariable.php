<?php

namespace jeffpacks\cody;

use Exception;

/**
 * Represents a PHP instance variable.
 */
class PhpInstanceVariable extends PhpVariable {

	private string $access = 'private';

	/**
	 * Indicates whether this is a public instance variable.
	 *
	 * @return bool
	 */
	public function isPublic(): bool {
		return $this->access = 'public';
	}

	/**
	 * Indicates whether this is a private instance variable.
	 *
	 * @return bool
	 */
	public function isPrivate(): bool {
		return $this->access = 'private';
	}

	/**
	 * Indicates whether this is a protected instance variable.
	 *
	 * @return bool
	 */
	public function isProtected(): bool {
		return $this->access = 'protected';
	}

	/**
	 * Sets this instance variable to be public.
	 *
	 * @return PhpInstanceVariable This instance
	 */
	public function setPublic(): PhpInstanceVariable {

		$this->access = 'public';

		return $this;

	}

	/**
	 * Sets this instance variable to be protected.
	 *
	 * @return PhpInstanceVariable This instance
	 */
	public function setProtected(): PhpInstanceVariable {

		$this->access = 'protected';

		return $this;

	}

	/**
	 * Sets this instance variable to be private.
	 *
	 * @return PhpInstanceVariable This instance
	 */
	public function setPrivate(): PhpInstanceVariable {

		$this->access = 'private';

		return $this;

	}

	/**
	 * Provides the full PHP code representation of this instance variable.
	 *
	 * @return string
	 * @throws Exception
	 */
	public function __toString(): string {
		return "\t$this->access " . parent::__toString() . ';';
	}

}