<?php

namespace jeffpacks\cody\interfaces;

interface HasInterfaces {

	/**
	 * Adds an interface to this PHP citizen.
	 *
	 * @param string $name The name of the interface.
	 * @return HasInterfaces This instance
	 */
	public function addInterface(string $name): HasInterfaces;

	/**
	 * Provides the PHP interface names of this PHP citizen.
	 *
	 * @return string[] Zero or more PHP interface names
	 */
	public function getInterfaces(): array;

}