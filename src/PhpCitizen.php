<?php

namespace jeffpacks\cody;

use jeffpacks\cody\traits\ValueEncoder;

class PhpCitizen {

	use ValueEncoder;

	private string $name;
	private array $uses = [];
	private array $constants = [];
	private PhpDocBlock $docBlock;
	private PhpNamespace $namespace;

	/**
	 * PhpCitizen constructor.
	 *
	 * @param string $name The name of the PHP citizen.
	 * @param PhpNamespace $namespace The namespace of the PHP citizen.
	 */
	public function __construct(string $name, PhpNamespace $namespace) {

		$this->name = $name;
		$this->namespace = $namespace;
		$this->docBlock = new PhpDocBlock();

	}

	/**
	 * Adds a PHP constant to this PHP citizen.
	 *
	 * @param string $name The name of the constant.
	 * @param string|int|float|bool|array|null $value The value of the constant.
	 * @return PhpCitizen This instance
	 */
	public function addConstant(string $name, $value): PhpCitizen {

		$this->constants[$name] = $value;

		return $this;

	}

	/**
	 * Adds a PHP use import statement to this PHP citizen.
	 *
	 * @param string $name The fully qualified or relative PHP name of what to use.
	 * @return PhpCitizen This instance
	 */
	public function addUse(string $name, ?string $alias = null): PhpCitizen {

		$this->uses[$name] = $alias;

		return $this;

	}

	/**
	 * Provides the PHP constants of this PHP citizen.
	 *
	 * @return array Zero or more entries, keyed by constant name, each representing a PHP constant
	 */
	public function getConstants(): array {
		return $this->constants;
	}

	/**
	 * Provides the description of this PHP citizen, if any.
	 *
	 * @return string|null
	 */
	public function getDescription(): ?string {
		return $this->docBlock->getDescription();
	}

	/**
	 * Provides the doc-block of this PHP citizen.
	 *
	 * @return PhpDocBlock
	 */
	public function getDocBlock(): PhpDocBlock {
		return $this->docBlock;
	}

	/**
	 * Provides the fully qualified PHP name of this PHP citizen.
	 *
	 * @return string
	 */
	public function getFqn(): string {
		return "{$this->getNamespace()}\\{$this->getName()}";
	}

	/**
	 * Provides the name of this PHP citizen.
	 *
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * Provides the full namespace of this PHP citizen, if any.
	 *
	 * @return string
	 */
	public function getNamespace(): string {
		return $this->namespace;
	}

	/**
	 * Provides the use statements of this PHP citizen.
	 *
	 * @return string[] Zero or more FQN => alias entries
	 */
	public function getUses(): array {
		return $this->uses;
	}

	/**
	 * Sets the description of this PHP citizen.
	 *
	 * @param string|null $description
	 * @return PhpCitizen This instance
	 */
	public function setDescription(?string $description = null): PhpCitizen {

		$this->docBlock->setDescription($description);

		return $this;

	}

	/**
	 * Provides the full PHP code representation of this PHP citizen.
	 *
	 * @return string
	 */
	public function __toString(): string {

		$string = "<?php\n\n";
		$string .= "namespace $this->namespace;\n\n";

		if ($uses = $this->getUses()) {
			$baseName = "{$this->getNamespace()}";
			foreach ($uses as $name => $alias) {
				$fqn = substr($name, 0, strlen($baseName)) === $baseName
					? $name
					: "$baseName\\$name";
				$string .= "use $fqn" . ($alias ? " as $alias" : '') . ";\n";
			}
			$string .= "\n";
		}
		$string .= $this->docBlock->__toString();

		return $string;

	}

}