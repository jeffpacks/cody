<?php

namespace jeffpacks\cody;

use jeffpacks\cody\exceptions\UnknownClassException;
use jeffpacks\cody\exceptions\UnknownTraitException;
use jeffpacks\cody\exceptions\UnknownInterfaceException;
use jeffpacks\cody\exceptions\UnknownNamespaceException;

/**
 * Represents a PHP namespace.
 */
class PhpNamespace {

	private string $name;
	private ?PhpNamespace $namespace;
	private array $namespaces = [];
	private array $classes = [];
	private array $interfaces = [];
	private array $traits = [];

	/**
	 * PhpNamespace constructor.
	 *
	 * @param string $name The name of this namespace.
	 * @param PhpNamespace|null $namespace The parent namespace of this namespace.
	 */
	public function __construct(string $name, ?PhpNamespace $namespace = null) {

		$this->name = $name;
		$this->namespace = $namespace;

	}

	/**
	 * Creates a new PHP class in this namespace.
	 *
	 * @param string $name The name of the PHP class.
	 * @return PhpClass The new PHP class
	 */
	public function createClass(string $name): PhpClass {
		return $this->classes[$name] = new PhpClass($name, $this);
	}

	/**
	 * Creates a new PHP interface in this namespace.
	 *
	 * @param string $name The name of the PHP interface.
	 * @return PhpInterface The new PHP interface
	 */
	public function createInterface(string $name): PhpInterface {
		return $this->interfaces[$name] = new PhpInterface($name, $this);
	}

	/**
	 * Creates a new PHP namespace within this namespace.
	 *
	 * @param string $name The name of the namespace.
	 * @return PhpNamespace The new namespace
	 */
	public function createNamespace(string $name): PhpNamespace {
		return $this->namespaces[$name] = new PhpNamespace($name, $this);
	}

	/**
	 * Creates a new PHP trait in this namespace.
	 *
	 * @param string $name The name of the PHP trait.
	 * @return PhpTrait The new PHP trait
	 */
	public function createTrait(string $name): PhpTrait {
		return $this->traits[$name] = new PhpTrait($name, $this);
	}

	/**
	 * Provides all the PHP citizens in this namespace.
	 *
	 * @return PhpCitizen[]
	 */
	public function getCitizens(): array {
		return array_merge($this->classes, $this->interfaces, $this->traits);
	}

	/**
	 * Provides a specific class from this namespace.
	 *
	 * @param string $name The name of the class.
	 * @return PhpClass
	 * @throws UnknownClassException
	 */
	public function getClass(string $name): PhpClass {

		if (!isset($this->classes[$name])) {
			throw new UnknownClassException($name);
		}

		return $this->classes[$name];

	}

	/**
	 * Provides the PHP classes in this namespace.
	 *
	 * @return PhpClass[]
	 */
	public function getClasses(): array {
		return $this->classes;
	}

	/**
	 * Provides a specific interface from this namespace.
	 *
	 * @param string $name The name of the interface.
	 * @return PhpInterface
	 * @throws UnknownInterfaceException
	 */
	public function getInterface(string $name): PhpInterface {

		if (!isset($this->interfaces[$name])) {
			throw new UnknownInterfaceException($name);
		}

		return $this->interfaces[$name];

	}

	/**
	 * Provides the PHP interfaces in this namespace.
	 *
	 * @return PhpInterface[]
	 */
	public function getInterfaces(): array {
		return $this->interfaces;
	}

	/**
	 * Provides the name of this namespace.
	 *
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * Provides a specific namespace within this namespace.
	 *
	 * @param string $name The name of the namespace.
	 * @return PhpNamespace
	 * @throws UnknownNamespaceException
	 */
	public function getNamespace(string $name): PhpNamespace {

		if (isset($this->namespaces[$name])) {
			return $this->namespaces[$name];
		}

		throw new UnknownNamespaceException($name);

	}

	/**
	 * Provides the namespaces within this namespace.
	 *
	 * @return PhpNamespace[]
	 */
	public function getNamespaces(): array {
		return $this->namespaces;
	}

	/**
	 * Provides a specific trait from this namespace.
	 *
	 * @param string $name The name of the trait.
	 * @return PhpTrait
	 * @throws UnknownTraitException
	 */
	public function getTrait(string $name): PhpTrait {

		if (!isset($this->trait[$name])) {
			throw new UnknownTraitException($name);
		}

		return $this->trait[$name];

	}

	/**
	 * Provides the PHP traits in this namespace.
	 *
	 * @return PhpTrait[]
	 */
	public function getTraits(): array {
		return $this->traits;
	}

	/**
	 * Provides a string representation of this namespace.
	 *
	 * @return string
	 */
	public function __toString(): string {
		return $this->namespace ? "$this->namespace\\$this->name" : $this->name;
	}

}