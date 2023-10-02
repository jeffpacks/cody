<?php

namespace jeffpacks\cody\traits;

use Exception;

trait ValueEncoder {

	/**
	 * Provides a given value as PHP syntax.
	 *
	 * @param mixed $value The value to encode as PHP syntax.
	 * @return string The value encoded as PHP syntax
	 * @throws Exception If the given value can not be encoded
	 */
	private function encode($value): string {

		if (is_null($value)) {
			return 'null';
		}

		if (is_bool($value)) {
			return $value ? 'true' : 'false';
		}

		if (is_string($value)) {
			return "'$value'";
		}

		if (is_int($value) || is_float($value) || is_double($value)) {
			return $value;
		}

		if (is_array($value)) {
			$string = "[";

			$hasStringKey = (bool) array_filter(array_keys($value), fn($key) => is_string($key));

			$arrayLines = [];
			foreach ($value as $entryKey => $entryValue) {
				if ($hasStringKey) {
					if (is_string($entryKey)) {
						$arrayLines[] = "\t\t'$entryKey' => " . $this->encode($entryValue);
					} else {
						$arrayLines[] = "\t\t$entryKey => " . $this->encode($entryValue);
					}
				} else {
					$arrayLines[] = "\t\t" . $this->encode($entryValue);
				}
			}
			$string .= "\n" . implode(",\n", $arrayLines);

			$string .= "\n\t]";

			return $string;
		}

		throw new Exception('Unable to encode value');

	}

}