<?php

namespace jeffpacks\cody;

use Closure;
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
	 * @param Closure|null $fallback A closure fn(string $requestedName, PhpNamespace $super) that will return a PhpClass object if the requested class does not exist.
	 * @return PhpClass
	 * @throws UnknownClassException
	 */
	public function getClass(string $name, ?Closure $fallback = null): PhpClass {

		if (!isset($this->classes[$name])) {
			if ($fallback) {
				return $fallback($name, $this);
			}
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
	 * Provides the fully qualified PHP namespace name of this namespace.
	 *
	 * @return string
	 */
	public function getFqn(): string {
		return $this->namespace ? "$this->namespace\\$this->name" : $this->name;
	}

	/**
	 * Provides a specific interface from this namespace.
	 *
	 * @param string $name The name of the interface.
	 * @param Closure|null $fallback A closure fn(string $requestedName, PhpNamespace $super) that will return a PhpInterface object if the requested interface does not exist.
	 * @return PhpInterface
	 * @throws UnknownInterfaceException
	 */
	public function getInterface(string $name, ?Closure $fallback = null): PhpInterface {

		if (!isset($this->interfaces[$name])) {
			if ($fallback) {
				return $fallback($name, $this);
			}
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
	 * @param Closure|null $fallback A closure fn(string $requestedName, PhpNamespace $super) that will return a PhpNamespace object if the requested namespace does not exist.
	 * @return PhpNamespace
	 * @throws UnknownNamespaceException
	 */
	public function getNamespace(string $name, ?Closure $fallback = null): PhpNamespace {

		if (isset($this->namespaces[$name])) {
			if ($fallback) {
				return $fallback($name, $this);
			}
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
	 * @param Closure|null $fallback A closure fn(string $requestedName, PhpNamespace $super) that will return a PhpTrait object if the requested trait does not exist.
	 * @return PhpTrait
	 * @throws UnknownTraitException
	 */
	public function getTrait(string $name, ?Closure $fallback = null): PhpTrait {

		if (!isset($this->trait[$name])) {
			if ($fallback) {
				return $fallback($name, $this);
			}
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
	 * Indicates whether this namespace has a given class.
	 *
	 * @param string $name A PHP classname or fully qualified PHP classname.
	 * @return bool
	 */
	public function hasClass(string $name): bool {

		if (isset($this->classes[$name])) {
			return true;
		}

		foreach ($this->getClasses() as $class) {
			if ($class->getFqn() === $name) {
				return true;
			}
		}

		return false;

	}

	/**
	 * Indicates whether this namespace has a given interface.
	 *
	 * @param string $name A PHP classname or fully qualified PHP classname.
	 * @return bool
	 */
	public function hasInterface(string $name): bool {

		if (isset($this->interfaces[$name])) {
			return true;
		}

		foreach ($this->getInterfaces() as $interface) {
			if ($interface->getFqn() === $name) {
				return true;
			}
		}

		return false;

	}

	/**
	 * Indicates whether this namespace has a given sub-namespace.
	 *
	 * @param string $name A PHP namespace basename or a full PHP namespace
	 * @return bool
	 */
	public function hasNamespace(string $name): bool {

		if (isset($this->namespaces[$name])) {
			return true;
		}

		foreach ($this->getNamespaces() as $namespace) {
			if ($namespace->getFqn() === $name) {
				return true;
			}
		}

		return false;

	}

	/**
	 * Indicates whether this namespace has a given trait.
	 *
	 * @param string $name A PHP classname or fully qualified PHP classname.
	 * @return bool
	 */
	public function hasTrait(string $name): bool {

		if (isset($this->traits[$name])) {
			return true;
		}

		foreach ($this->getTraits() as $trait) {
			if ($trait->getFqn() === $name) {
				return true;
			}
		}

		return false;

	}

	/**
	 * Provides a string representation of this namespace.
	 *
	 * @return string
	 */
	public function __toString(): string {
		return $this->getFqn();
	}

}