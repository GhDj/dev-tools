# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.2.1] - 2025-12-15

### Fixed

- Code Editor: Fix PHP parse error when loading the page (`<?php` string in JavaScript was interpreted as PHP tag)

## [1.2.0] - 2025-12-15

### Added

- **Regex Tester**: Live regular expression testing with match highlighting
  - Real-time pattern matching with visual feedback
  - Support for global, case-insensitive, and multiline flags
  - Match groups extraction and display

- **JWT Decoder**: Decode and inspect JSON Web Tokens
  - Header and payload extraction
  - Expiration time validation
  - Signature verification status

- **Timestamp Converter**: Unix timestamp to human-readable date conversion
  - Bidirectional conversion (timestamp ↔ date)
  - Multiple timezone support
  - Current timestamp display

- **Diff Checker**: Side-by-side text comparison tool
  - Line-by-line diff visualization
  - Added/removed line highlighting
  - Unified and split view modes

- **Cron Expression Parser**: Parse and explain cron expressions
  - Human-readable cron schedule explanation
  - Next execution times preview
  - Common cron presets

- **Lorem Ipsum Generator**: Generate placeholder text
  - Paragraphs, sentences, or words generation
  - Configurable output length
  - Start with "Lorem ipsum" option

- **Password Generator**: Generate secure random passwords
  - Configurable length and character sets
  - Uppercase, lowercase, numbers, symbols options
  - Password strength indicator

- **Text Case Converter**: Convert between 13 text case formats
  - lowercase, UPPERCASE, Title Case, Sentence case
  - camelCase, PascalCase, snake_case, kebab-case
  - CONSTANT_CASE, dot.case, path/case, and more

- **HTML Entity Encoder**: Encode and decode HTML entities
  - Named entities (e.g., &amp;, &lt;, &gt;)
  - Numeric entities support
  - Bulk encoding/decoding

- **QR Code Generator**: Generate QR codes from text or URLs
  - Customizable size and error correction
  - Download as PNG
  - Real-time preview

- **Color Picker**: Color format converter
  - HEX, RGB, HSL, CMYK formats
  - Visual color picker
  - Color palette suggestions

- **Slug Generator**: Create URL-friendly slugs from text
  - Configurable separator (hyphen, underscore)
  - Unicode transliteration
  - Length limiting option

- **Base Converter**: Number base conversion tool
  - Binary, Octal, Decimal, Hexadecimal
  - Bit visualization
  - Instant conversion between bases

### Enhanced

- **Code Editor**: Major enhancements
  - Dynamic tab system with add/close functionality
  - Console output capture (log, info, warn, error)
  - Keyboard shortcuts help modal
  - Word wrap and minimap toggle controls
  - Download all files as ZIP

- **JSON Parser**: Added interactive tree view
  - Collapsible tree structure
  - Node type indicators
  - Copy path to clipboard

- **Theme Toggle**: Redesigned with animated day/night scene
  - Smooth transition animations
  - Sun/moon visual elements

### Other

- Added Privacy Policy page
- Added About page
- Integrated Google Analytics 4 tracking

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

[1.2.1]: https://github.com/GhDj/dev-tools/releases/tag/v1.2.1
[1.2.0]: https://github.com/GhDj/dev-tools/releases/tag/v1.2.0
[1.1.0]: https://github.com/GhDj/dev-tools/releases/tag/v1.1.0
[1.0.0]: https://github.com/GhDj/dev-tools/releases/tag/v1.0.0
