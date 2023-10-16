<?php

namespace jeffpacks\cody;

class PhpMethod extends PhpMethodSignature {

	private array $bodyLines = [];

	/**
	 * Adds a code line to the body of this method.
	 *
	 * @param string $line The code line to add.
	 * @return PhpMethod This instance
	 */
	public function addLine(string $line): PhpMethod {

		$this->bodyLines[] = $line;

		return $this;

	}

	/**
	 * Provides the lines of this method's body, if any.
	 *
	 * @return string[]|null Zero or more lines, null if there is no body
	 */
	public function getBodyLines(): ?array {
		return $this->bodyLines;
	}

	/**
	 * Sets this method abstract or non-abstract.
	 *
	 * @param bool $abstract True to set this method abstract, false otherwise.
	 * @return PhpMethod This instance
	 */
	public function setAbstract(bool $abstract = true): PhpMethod {

		$this->isAbstract = $abstract;

		if ($abstract) {
			$this->bodyLines = [];
		}

		return $this;

	}

	/**
	 * Sets the entire body of this method.
	 *
	 * @param string $body The code lines separated by newline characters.
	 * @return PhpMethod This instance
	 */
	public function setBody(string $body): PhpMethod {

		$this->bodyLines = $body === '' ? [] : explode("\n", $body);

		return $this;

	}

	/**
	 * Provides the full PHP code representation of this PHP citizen.
	 *
	 * @return string
	 */
	public function __toString(): string {

		$string = parent::__toString();

		$string .= " {\n\n";
		foreach ($this->bodyLines as $line) {
			$string .= "\t\t$line\n";
		}
		$string .= "\n\t}\n\n";

		return $string;

	}

}