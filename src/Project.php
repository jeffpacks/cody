<?php

namespace jeffpacks\cody;

use jeffpacks\cody\export\Export;

class Project {

	private string $name;
	private PhpNamespace $namespace;
	private Export $export;

	public function __construct(string $name, string $namespace) {
		$this->name = $name;
		$this->namespace = new PhpNamespace($namespace);
		$this->export = new Export($this);
	}

	public function export(): Export {
		return $this->export;
	}

	/**
	 * Provides the name of this project.
	 *
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * Provides the namespace of this project.
	 *
	 * @return PhpNamespace
	 */
	public function getNamespace(): PhpNamespace {
		return $this->namespace;
	}

}