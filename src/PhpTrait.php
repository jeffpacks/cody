<?php

namespace jeffpacks\cody;

use Exception;
use jeffpacks\cody\traits\ValueEncoder;
use jeffpacks\cody\interfaces\HasTraits;
use jeffpacks\cody\interfaces\Implementor;

class PhpTrait extends PhpCitizen implements Implementor, HasTraits {

	use ValueEncoder;
	use traits\HasTraits;
	use traits\Implementor;

	/**
	 * Provides the full PHP code representation of this PHP class.
	 *
	 * @return string
	 * @throws Exception
	 */
	public function __toString(): string {

		$string = parent::__toString();

		$string .= "trait {$this->getName()} ";

		$string .= "{\n\n";

		if ($constants = $this->getConstants()) {
			foreach ($constants as $name => $value) {
				$string .= "\tconst $name = {$this->encode($value)};\n";
			}
			$string .= "\n";
		}

		if ($traits = $this->getTraits()) {
			foreach ($traits as $trait) {
				$string .= "\tuse $trait;\n";
			}
			$string .= "\n";
		}

		$string .= implode("\n\n", $this->getMethods());

		$string .= "}";

		return $string;

	}

}