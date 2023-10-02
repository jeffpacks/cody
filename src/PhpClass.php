<?php

namespace jeffpacks\cody;

use Exception;
use jeffpacks\cody\traits\ValueEncoder;
use jeffpacks\cody\interfaces\HasTraits;
use jeffpacks\cody\interfaces\Implementor;
use jeffpacks\cody\interfaces\HasInterfaces;

class PhpClass extends PhpCitizen implements HasInterfaces, HasTraits, Implementor {

	use ValueEncoder;
	use traits\HasTraits;
	use traits\Implementor;
	use traits\HasInterfaces;

	/**
	 * Provides the full PHP code representation of this PHP class.
	 *
	 * @return string
	 * @throws Exception
	 */
	public function __toString(): string {

		$string = parent::__toString();

		foreach ($this->getMethods() as $method) {
			if ($method->isAbstract()) {
				$string .= 'abstract ';
				break;
			}
		}

		$string .= "class {$this->getName()} ";

		if ($interfaces = $this->getInterfaces()) {
			$string .= 'implements ' . implode(', ', $interfaces) . ' ';
		}
		$string .= "{\n\n";

		if ($traits = $this->getTraits()) {
			foreach ($traits as $trait) {
				$string .= "\tuse $trait;\n";
			}
			$string .= "\n";
		}

		if ($constants = $this->getConstants()) {
			foreach ($constants as $name => $value) {
				$string .= "\tconst $name = {$this->encode($value)};\n";
			}
			$string .= "\n";
		}

		$string .= implode("\n\n", $this->getMethods());

		$string .= '}';

		return $string;

	}

}