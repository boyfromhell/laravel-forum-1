# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## Unreleased
### Changed
 - Laravel 5.3+ requirement.
 - Migrations are now loaded from the package without requiring to be published.

### Fixed
 - Fix label for forum category on forum creation admin form.

## [1.0.2] - 2016-12-28
### Changed
 - Lock laravel-support package to v1.0.9 to support laravel 5.0-5.2.

### Removed
 - Remove modal configuration and views.
 - Use traditional forms over javascript modals.
 - Remove composer.lock file.

## [1.0.1] - 2015-12-17
### Changed
 - Now utilizes taskforce-support package for sitename and layout configuration.
 - Update readme to include instructions to add (if not present) the taskforce-support service provider.

### Removed
 - Remove sitename and layout from laravel-forum config.

## [1.0.0] - 2015-08-14
### Added
 - Admin ability to create/delete categories and forums.
 - Admin dashboard showing number of posts, forums, categories and replies.
 - Ability to create posts and replies.
 - Admin/mod ability to sticky lock and delete threads.
 - Bootstrap default theme.
 - Ability through config to extend own layout rather than using plain default.
 - TinyMCE as default editor, this can be disabled in config.
 - Script sanitizer for user html input.
 - Breadcrumbs.
 - Automatically performs database migrations, no need to publish then migrate.
