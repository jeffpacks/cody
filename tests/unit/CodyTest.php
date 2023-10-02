<?php

namespace jeffpacks\cody\unit;

use Exception;
use jeffpacks\cody\Cody;
use jeffpacks\cody\PhpTrait;
use jeffpacks\cody\Project;
use jeffpacks\cody\PhpClass;
use jeffpacks\cody\PhpMethod;
use jeffpacks\cody\PhpInterface;
use jeffpacks\cody\PhpNamespace;
use jeffpacks\cody\PhpMethodSignature;
use jeffpacks\cody\exceptions\UnknownMethodException;
use jeffpacks\cody\exceptions\UnknownNamespaceException;
use jeffpacks\cody\exceptions\UnknownInterfaceException;
use PHPUnit\Framework\TestCase;

class CodyTest extends TestCase {

	private static string $outputDirPath;

	/**
	 * @return void
	 * @throws Exception
	 */
	public static function setUpBeforeClass(): void {

		parent::setUpBeforeClass();

		$outputDirPath = self::$outputDirPath = realpath(__DIR__ . '/../output');

		# Ensure the output directory is writable
		if (!is_writable($outputDirPath)) {
			throw new Exception("The output directory $outputDirPath is not writable");
		}

		# Ensure the output directory is empty
		$rmDir = function(string $dirPath) use (&$rmDir) {
			foreach (scandir($dirPath) as $filename) {
				if (!in_array($filename, ['.', '..'])) {
					$filePath = "$dirPath/$filename";
					if (is_dir($filePath)) {
						$rmDir($filePath); # Delete all files in the directory
						rmdir($filePath); # Delete the directory itself
					} else {
						unlink($filePath);
					}
				}
			}
		};

		$rmDir($outputDirPath);

	}

	/**
	 * @param string $path An absolute file system directory path
	 * @return void
	 */
	private function assertDirectoryIsEmpty(string $path): void {
		if (count(scandir($path)) > 2) {
			$this->fail("Failed asserting that directory $path is empty");
		}
	}

	/**
	 * @return Project
	 */
	public function testCreateProject(): Project {

		$project = Cody::createProject('codytest', 'jeffpacks\codytest');

		$this->assertInstanceOf(Project::class, $project);

		return $project;

	}

	/**
	 * @param Project $project
	 * @return PhpNamespace
	 * @depends testCreateProject
	 */
	public function testGetNamespace(Project $project): PhpNamespace {

		$namespace = $project->getNamespace();
		$this->assertInstanceOf(PhpNamespace::class, $namespace);

		return $namespace;

	}

	/**
	 * @param PhpNamespace $projectNamespace
	 * @return PhpNamespace
	 * @depends testGetNamespace
	 */
	public function testCreateInterface(PhpNamespace $projectNamespace): PhpNamespace {

		$interfacesNamespace = $projectNamespace->createNamespace('interfaces');
		$this->assertInstanceOf(PhpNamespace::class, $interfacesNamespace);

		$hasUrlInterface = $interfacesNamespace->createInterface('HasUrl')
			->setDescription('Represents an object that has a URL');
		$this->assertInstanceOf(PhpInterface::class, $hasUrlInterface);

		$getUrlMethod = $hasUrlInterface->createMethod('getUrl')
			->setDescription('Provides the URL');
		$getUrlMethod->createParameter('fragment', '?string', null);
		$getUrlMethod->createParameter('params', '?array', null);
		$getUrlMethod->createParameter('asHttps', 'bool', true);
		$this->assertInstanceOf(PhpMethodSignature::class, $getUrlMethod);

		$pageInterface = $interfacesNamespace->createInterface('Page')
			->setDescription('Defines a page')
			->addUse('HasUrl')
			->addInterface('HasUrl');
		$this->assertInstanceOf(PhpInterface::class, $pageInterface);

		$getTitleMethod = $pageInterface->createMethod('getTitle')
			->setDescription('Provides the title of the page');
		$getTitleMethod->setReturnType('string');


		return $projectNamespace;

	}

	/**
	 * @param PhpNamespace $projectNamespace
	 * @return PhpNamespace
	 * @depends testCreateInterface
	 * @throws UnknownInterfaceException
	 * @throws UnknownMethodException
	 * @throws UnknownNamespaceException
	 */
	public function testCreateTrait(PhpNamespace $projectNamespace): PhpNamespace {

		$traitNamespace = $projectNamespace->createNamespace('traits');
		$this->assertInstanceOf(PhpNamespace::class, $traitNamespace);

		$trait = $traitNamespace->createTrait('Page')
			->setDescription('Implements a page');
		$this->assertInstanceOf(PhpTrait::class, $trait);

		$pageInterface = $projectNamespace->getNamespace('interfaces')->getInterface('Page');
		$trait->implement($pageInterface);
		$this->assertInstanceOf(PhpInterface::class, $pageInterface);
		$this->assertInstanceOf(PhpMethod::class, $trait->getMethod('getTitle'));

		return $projectNamespace;

	}

	/**
	 * @param PhpNamespace $projectNamespace
	 * @return PhpNamespace
	 * @depends testCreateTrait
	 * @throws UnknownInterfaceException
	 * @throws UnknownMethodException
	 * @throws UnknownNamespaceException
	 */
	public function testCreateClass(PhpNamespace $projectNamespace): PhpNamespace {

		$class = $projectNamespace->createClass('Page')
			->setDescription('Represents a page')
			->addUse('interfaces\\Page', 'IPage')
			->addUse('traits\\Page', 'PageTrait')
			->addInterface('IPage')
			->addConstant('CURRENT_VERSION', '2.0')
			->addConstant('VERSIONS', ['1.0', '1.1', '2.0']);
		$this->assertInstanceOf(PhpClass::class, $class);

		$class->addTrait('PageTrait');

		$hasUrlInterface = $projectNamespace->getNamespace('interfaces')->getInterface('HasUrl');
		$this->assertInstanceOf(PhpMethodSignature::class, $hasUrlInterface->getMethod('getUrl'));

		$class->implement($hasUrlInterface);
		$getUrlMethod = $class->getMethod('getUrl');
		$this->assertInstanceOf(PhpMethod::class, $getUrlMethod);
		$this->assertEmpty($getUrlMethod->getBodyLines());

		return $projectNamespace;

	}

	/**
	 * @param Project $project
	 * @return void
	 * @depends testCreateProject
	 * @depends testCreateClass
	 * @throws Exception
	 */
	public function testExport(Project $project): void {

		$outputPath = self::$outputDirPath;

		$this->assertDirectoryExists($outputPath);
		$this->assertDirectoryIsWritable($outputPath);
		$this->assertDirectoryIsEmpty($outputPath);

		$project->export()->toDirectory($outputPath)->run();

		$this->assertDirectoryExists("$outputPath/codytest/src");
		$this->assertFileExists("$outputPath/codytest/src/Page.php");
		$this->assertDirectoryExists("$outputPath/codytest/src/interfaces");
		$this->assertFileExists("$outputPath/codytest/src/interfaces/Page.php");
		$this->assertFileExists("$outputPath/codytest/src/interfaces/HasUrl.php");
		$this->assertDirectoryExists("$outputPath/codytest/src/traits");
		$this->assertFileExists("$outputPath/codytest/src/traits/Page.php");

	}

}