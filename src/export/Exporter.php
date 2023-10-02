<?php

namespace jeffpacks\cody\export;

use jeffpacks\cody\Project;

abstract class Exporter {

	private Project $project;
	private string $destination;

	/**
	 * Exporter constructor.
	 *
	 * @param Project $project The project that will be exported.
	 * @param string $destination A file path or URL to where the exported project will be stored.
	 */
	public function __construct(Project $project, string $destination) {

		$this->project = $project;
		$this->destination = $destination;

	}

	/**
	 * Provides the file path or URL to where the exported project will be stored.
	 *
	 * @return string
	 */
	public function getDestination(): string {
		return $this->destination;
	}

	/**
	 * Provides the project to be exported.
	 *
	 * @return Project
	 */
	public function getProject(): Project {
		return $this->project;
	}

	/**
	 * Runs the export.
	 *
	 * @return void
	 */
	public abstract function run(): void;

}