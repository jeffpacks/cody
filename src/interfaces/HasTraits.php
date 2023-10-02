<?php

namespace jeffpacks\cody\interfaces;

interface HasTraits {

	/**
	 * Adds a trait to this PHP citizen.
	 *
	 * @param string $name The name of the trait.
	 * @return HasTraits This instance
	 */
	public function addTrait(string $name): HasTraits;

	/**
	 * Provides the PHP trait names of this PHP citizen.
	 *
	 * @return string[] Zero or more PHP trait names
	 */
	public function getTraits(): array;

}