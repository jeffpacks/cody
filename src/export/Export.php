<?php

namespace jeffpacks\cody\export;

use jeffpacks\cody\Project;

class Export {

	private Project $project;

	public function __construct(Project $project) {
		$this->project = $project;
	}

	/**
	 * Provides the project of this export.
	 *
	 * @return Project
	 */
	public function getProject(): Project {
		return $this->project;
	}

	public function toDirectory(string $directoryPath): DirectoryExporter {
		return new DirectoryExporter($this->project, $directoryPath);
	}

	public function toGitHub(string $url): GitHubExporter {
		return new GitHubExporter($this->project, $url);
	}

	public function toPackagistCom(string $url): PackagistComExporter {
		return new PackagistComExporter($this->project, $url);
	}

	public function toPackagistOrg(string $url): PackagistOrgExporter {
		return new PackagistOrgExporter($this->project, $url);
	}

}