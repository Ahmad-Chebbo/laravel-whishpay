# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.0] - 2026-09-24

### Added
- AED (United Arab Emirates Dirham) currency support in `Currency` enum (`Currency::AED`).
- AED added to `allowed_currencies` in default configuration and pipeline validation.
- Unofficial package disclaimer and repository status badges in `README.md`.

### Changed
- Normalized package name to `ahmad-chebbo/laravel-whishpay` in `composer.json` and documentation.
- Updated default production URL to `https://whish.money/itel-service/api` and sandbox URL to `https://lb.sandbox.whish.money/itel-service/api`.
- Renamed payment model configuration key from `pending_model` to `payment_model` in `config/whish-pay.php`, `WhishStatusCheckerCommand`, and `README.md`.
- Relaxed framework version dependencies in `composer.json` to broaden compatibility with Laravel 10+.
- Updated `whish:install` command default URLs to match updated configuration.

### Fixed
- Fixed author name spelling in `README.md` license section.

## [1.0.0] - 2026-01-29

### Added
- Initial release of Laravel Whish Pay package.
- `WhishPay` service and facade with payment initialization, status verification, and balance checks.
- Payment processing pipelines (fraud detection, amount limits, logging, and currency validation).
- Artisan commands: `whish:install`, `whish:health`, `whish:test`, `whish:balance`, `whish:fake`, `whish:webhook`, and `whish:status-checker`.
- Fake HTTP client for testing and local development.
