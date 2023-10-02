<?php

namespace jeffpacks\cody;

class Cody {

	private string $namespace;
	private array $classes = [];
	private array $interfaces = [];
	private array $traits = [];

	/**
	 * Creates a new Cody instance with a namespace for all the generated first-class citizens.
	 *
	 * @param string $namespace A PHP root namespace, such as "acme\webshop".
	 */
	public function __construct(string $namespace) {
		$this->namespace = $namespace;
	}

	/**
	 * Provides the PHP root namespace for all generated first-class citizens.
	 *
	 * @return string
	 */
	public function getNamespace(): string {
		return $this->namespace;
	}

	/**
	 * Creates a new PHP class.
	 *
	 * @param string $name The name of the PHP class.
	 * @param string|null $subNamespace The sub-namespace of the new class, e.g. "auth".
	 * @return PhpClass
	 */
	public function createClass(string $name, ?string $subNamespace = null): PhpClass {
		return $this->classes[$this->fqn($name, $subNamespace)] = new PhpClass($name, $this->fqn($subNamespace));
	}

	/**
	 * Creates a new PHP interface.
	 *
	 * @param string $name The name of the PHP interface.
	 * @param string|null $subNamespace The sub-namespace of the new interface, e.g. "auth".
	 * @return PhpInterface
	 */
	public function createInterface(string $name, ?string $subNamespace = null): PhpInterface {
		return $this->interfaces[$this->fqn($name, $subNamespace)] = new PhpInterface($name, $this->fqn($subNamespace));
	}

	/**
	 * Creates and provides a new project.
	 *
	 * @param string $name The name of the project.
	 * @param string $namespace The namespace of the project.
	 * @return Project
	 */
	public static function createProject(string $name, string $namespace): Project {
		return new Project($name, $namespace);
	}

	/**
	 * Creates a new PHP trait.
	 *
	 * @param string $name The name of the PHP trait.
	 * @param string|null $subNamespace The sub-namespace of the new trait, e.g. "auth".
	 * @return PhpTrait
	 */
	public function createTrait(string $name, ?string $subNamespace = null): PhpTrait {
		return $this->traits[$this->fqn($name, $subNamespace)] = new PhpTrait($name, $this->fqn($subNamespace));
	}

	/**
	 * Provides a fully qualified name of a given PHP citizen.
	 *
	 * @param string|null $name The name of the PHP citizen.
	 * @param string|null $subNamespace The sub-namespace of the citizen, if any.
	 * @return string The fully qualified PHP name of the given citizen
	 */
	private function fqn(?string $name, ?string $subNamespace = null): ?string {

		if ($name === null) {
			return $this->namespace;
		}

		return implode('\\', array_filter([$this->namespace, $subNamespace, $name]));

	}

}