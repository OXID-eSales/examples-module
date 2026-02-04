# Change Log for OXID eShop Examples Module

All notable changes to this project will be documented in this file.
The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Undecided] - Unreleased

### Changed
- Updated for OXID eShop 7.5.x compatibility
- PHP 8.3-8.5 support (removed PHP 8.2)

## [v2.0.0] - 2025-11-27

### Added
- Added the UserInterface with getId method to rely on
- SaveGreetingRequestInterface extracted from the Service layer
- Example of triggering an event after the vote action (new event - ProductVotedEvent)
- TrackerInterface added to not rely on the shop model directly
- Editorconfig file for more consistent coding style between devs

### Changed
- Switch to OXID eShop 7.4 as dev dependency - updated the recipe and github workflows.
- Segregate the Settings interface and moved pieces to their own domain directories:
  - LoggingSettingsInterface
  - GreetingSettingsInterface
- Reworked how the active user is accessed - do not use the getUser controller method anymore.
  - Its better to not rely on the old controller abstractions. 
- Restructured the directories in Tracker - Repository and Factory goes to Infrastructure directory.
- README improved with more information on the module Goals and more links to concrete example cases

### Fixed
- More waiting time for the acceptance tests to avoid random failures.

## [v1.0.0] - 2025-06-05

### Added
- The module is extracted from the [Module template](https://github.com/OXID-eSales/module-template) repository.

[v2.0.0]: https://github.com/OXID-eSales/examples-module/compare/v1.0.0...v2.0.0
