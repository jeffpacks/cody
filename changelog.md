# Changelog
All notable changes to this product will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this product adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0-alpha.3] - 2023-10-16

### Added
- Support for setting instance variables
  - `PhpVariable`
  - `PhpInstanceVariable`
  - `IncompatibleVariableValue`
  - `UnknownVariableException`
  - `PhpDocBlock::getReturnTypes()`
  - `PhpDocBlock::setReturnTypes()`
  - `PhpParameter::asAnnotation()`
  - `Implementor::createVariable()`
  - `Implementor::getMethod()`
  - `Implementor::getVariable()`
  - `Implementor::getVariables()`
  - `Implementor::hasMethod()`
  - `Implementor::hasVariable()`

### Changed
- Renamed `PhpMethodSignature::getReturnType()` to `PhpMethodSignature::getReturnTypes()`
- `PhpMethodSignature::getReturnTypes()` now returns an array instead of a string
- Renamed `PhpMethodSignature::setReturnType()` to `PhpMethodSignature::setReturnTypes()`

## [1.0.0-alpha.2] - 2023-10-02

### Added
- Fallback closure to methods:
  - `PhpNamespace::getClass()`
  - `PhpNamespace::getInterface()`
  - `PhpNamespace::getNamespace()`
  - `PhpNamespace::getTrait()`
- `PhpNamespace::hasClass()`
- `PhpNamespace::hasInterface()`
- `PhpNamespace::hasNamespace()`
- `PhpNamespace::hasTrait()`

## [1.0.0-alpha.1] - 2023-10-02

### Added
- README.md
- changelog.md
- autoload.php
- composer.json
- composer.lock
- .gitignore
- exceptions/
- export/
- interfaces/
- traits/
- tests/unit/
- tests/output/
- `cody\Cody`
- `cody\Cody\PhpCitizen`
- `cody\Cody\PhpClass`
- `cody\Cody\PhpDocBlock`
- `cody\Cody\PhpInterface`
- `cody\Cody\PhpMethod`
- `cody\Cody\PhpMethodSignature`
- `cody\Cody\PhpNamespace`
- `cody\Cody\PhpTrait`
- `cody\Cody\Project`
- `cody\interfaces\HasTraits`
- `cody\interfaces\HasInterfaces`
- `cody\interfaces\Importable`
- `cody\interfaces\Implementor`
- `cody\interfaces\Implementable`
- `cody\traits\HasInterfaces`
- `cody\traits\HasTraits`
- `cody\traits\Implementor`
- `cody\traits\ValueEncoder`
- `cody\export\Export`
- `cody\export\Exporter`
- `cody\export\DirectoryExporter`
- `cody\export\GitHubExporter`
- `cody\export\PackagistComExporter`
- `cody\export\PackagistOrgExporter`
- `cody\exceptions\UnknownClassException`
- `cody\exceptions\UnknownInterfacesException`
- `cody\exceptions\UnknownMethodException`
- `cody\exceptions\UnknowNamespaceException`
- `cody\exceptions\UnknownTraitException`
- `cody\unit\CodyTest`
