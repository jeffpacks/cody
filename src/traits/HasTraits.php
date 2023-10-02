<?php

namespace jeffpacks\cody\traits;

use jeffpacks\cody\interfaces\HasTraits as HasTraitsInterface;

trait HasTraits {

	private array $traits = [];

	/**
	 * Adds a trait to this PHP citizen.
	 *
	 * @param string $name The name of the trait.
	 * @return HasTraitsInterface This instance
	 */
	public function addTrait(string $name): HasTraitsInterface {

		$this->traits[] = $name;
		$this->traits = array_unique($this->traits);

		return $this;

	}

	/**
	 * Provides the PHP trait names of this PHP citizen.
	 *
	 * @return string[] Zero or more PHP trait names
	 */
	public function getTraits(): array {
		return $this->traits;
	}

}