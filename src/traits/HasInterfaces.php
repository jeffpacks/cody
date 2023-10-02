<?php

namespace jeffpacks\cody\traits;

use jeffpacks\cody\interfaces\HasInterfaces as HasInterfacesInterface;

trait HasInterfaces {

	private array $interfaces = [];

	/**
	 * Adds an interface to this PHP citizen.
	 *
	 * @param string $name The name of the interface.
	 * @return HasInterfacesInterface This instance
	 */
	public function addInterface(string $name): HasInterfacesInterface {

		$this->interfaces[] = $name;
		$this->interfaces = array_unique($this->interfaces);

		return $this;

	}

	/**
	 * Provides the PHP interface names of this PHP citizen.
	 *
	 * @return string[] Zero or more PHP interface names
	 */
	public function getInterfaces(): array {
		return $this->interfaces;
	}

}