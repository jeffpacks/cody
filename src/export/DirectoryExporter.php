<?php

namespace jeffpacks\cody\export;

use Exception;
use jeffpacks\cody\PhpNamespace;

class DirectoryExporter extends Exporter {

	/**
	 * Runs the export.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function run(): void {

		/**
		 * Exports the citizens of a given namespace by storing them as PHP files in a given directory.
		 *
		 * @param PhpNamespace $namespace The namespace whose citizens will be exported.
		 * @param string $path The full file system path of the directory where to store the given namespace's citizens.
		 * @return void
		 * @throws Exception
		 */
		$exportNamespace = function(PhpNamespace $namespace, string $path) use (&$exportNamespace) {

			if (!is_dir($path)) {
				if (!mkdir($path, 0755, true)) {
					throw new Exception("Unable to create directory $path");
				}
			}

			foreach ($namespace->getCitizens() as $citizen) {
				file_put_contents("$path/{$citizen->getName()}.php", "$citizen");
			}

			foreach ($namespace->getNamespaces() as $subNamespace) {
				$exportNamespace($subNamespace, "$path/{$subNamespace->getName()}");
			}

		};

		if (!is_writable($this->getDestination())) {
			throw new Exception("Directory {$this->getDestination()} is not writable");
		}

		$exportNamespace(
			$this->getProject()->getNamespace(),
			"{$this->getDestination()}/{$this->getProject()->getName()}/src"
		);

	}

}