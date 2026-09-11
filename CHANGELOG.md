# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Changed

- Set the Composer package name to `xitara/wn-voodooblocks-plugin` and restore the
  VoodooBlocks package metadata.
- Mark VoodooBlocks as archived and point users to VoodooGrid.
- Require PHP 8.2 or newer and Winter CMS 1.2 or newer.
- Align the Composer installer constraint with the version required by Winter CMS 1.2.
- Declare the backend side menu through Winter's native navigation API.
- Remove the legacy Nexus side-menu listener, hidden-label suffix, and injection hook.

### Fixed

- Point the main backend navigation entry to the existing block-list controller.
