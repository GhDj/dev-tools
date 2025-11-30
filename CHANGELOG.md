# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.0] - 2024-11-30

### Added

- **UUID Generator**: Generate, validate, and format UUIDs
  - UUID v4 generation (single or bulk up to 100)
  - Format options: lowercase, uppercase, no-hyphens, braces, URN
  - UUID validation with version and variant detection

- **Hash Generator**: Generate cryptographic hashes
  - Supported algorithms: MD5, SHA-1, SHA-256, SHA-384, SHA-512
  - Generate all hashes at once or select specific algorithm
  - Hash verification with auto-detection of algorithm by length

- **URL Encoder/Decoder**: Encode, decode, and parse URLs
  - Component encoding (rawurlencode) and full encoding (urlencode)
  - URL decoding
  - URL parsing with query parameter extraction
  - URL building from components

### Changed

- Updated home page to display all 9 tools
- Updated version to v1.1.0
- Deploy workflow now triggers on release publish instead of push to main

### Technical Details

- 66 new tests added (total: 270 tests, 719 assertions)
- New service classes: UuidService, HashService, UrlEncoderService
- New API endpoints: 7 new routes for UUID, Hash, and URL tools

## [1.0.0] - 2024-11-30

### Added

- **CSV Converter**: Convert CSV to JSON, SQL INSERT statements, or PHP arrays
  - Support for custom delimiters (comma, tab, pipe, semicolon)
  - Configurable table name for SQL output
  - Handles quoted fields, unicode, and edge cases

- **YAML/JSON Converter**: Bidirectional conversion between YAML and JSON
  - YAML to JSON conversion
  - JSON to YAML conversion
  - Pretty-printed output

- **Markdown Preview**: Live markdown preview with HTML export
  - GitHub Flavored Markdown support
  - Tables, task lists, strikethrough
  - Code block syntax highlighting

- **SQL Formatter**: Format and beautify SQL queries
  - Format mode for readable output
  - Compress mode for minified output
  - Supports complex queries (JOINs, subqueries, etc.)

- **Base64 Encoder/Decoder**: Text and file encoding/decoding
  - Text to Base64 encoding
  - Base64 to text decoding
  - File upload support (up to 5MB)

- **UI Features**:
  - Dark/light theme toggle with localStorage persistence
  - Mobile responsive design
  - Copy-to-clipboard on all outputs
  - CDN-based assets (Tailwind CSS, Alpine.js)

- **Infrastructure**:
  - GitHub Actions workflow for automated testing (PHP 8.1, 8.2, 8.3)
  - GitHub Actions workflow for automated deployment via SFTP
  - Shared hosting compatible (no database required)
  - Deployment script for easy setup

### Technical Details

- Built with Laravel 12
- File-based sessions and cache
- RESTful API endpoints for all tools
- 146 tests with 386 assertions

[1.1.0]: https://github.com/GhDj/dev-tools/releases/tag/v1.1.0
[1.0.0]: https://github.com/GhDj/dev-tools/releases/tag/v1.0.0
