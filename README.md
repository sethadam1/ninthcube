# NinthCube

A PHP utility library providing common helper functions for strings, dates, encryption, location formatting, and display.

## Namespace

```
NinthCube
```

## File Structure

```
src/
├── Encryption.php
├── Location.php
├── Time.php
└── Util.php
```

## Classes

### `Util`

General-purpose static helper methods.

| Method | Description |
|---|---|
| `constant($c, $default = "")` | Get a defined constant's value with a fallback default |
| `generate_password($length = 16)` | Generate a random password from a mixed character set (uses `random_int`) |
| `get_contrast_color($hex_color)` | Return `#000` or `#fff` for readable text on a given background color |
| `join_with_and($array)` | Join an array into an English list with an Oxford comma |
| `longify($input)` | Reverse a base-36 encoded value back to base-10 |
| `minify_css($input)` | Minify a CSS string by stripping comments, whitespace, and shorthand optimizations |
| `new_hash($len = 29)` | Generate an alphanumeric security hash |
| `obfuscate($email)` | HTML-encode an email address to deter scrapers |
| `ordinal($number, $format = true)` | Convert a number to its ordinal form (1st, 2nd, 3rd, etc.) |
| `preferred_date_format($mydate, $fmt = null)` | Format a `Y-m-d` date string using a configurable format |
| `shortify($input)` | Shorten an integer using base-36 encoding |
| `slugify($str)` | Convert a string to a URL-friendly slug, handling accented characters (uses `intl` when available) |
| `specialchars_cleaner($string)` | Replace smart quotes and other copy/paste artifacts with standard characters |
| `truncate_text($text, $length = 50, $strip_html = false)` | Truncate text to a given length at a word boundary. Skips truncation if the text exceeds the limit by fewer than 10 characters. |
| `youtube_embed(?string $str, int $w = 560, int $h = 315)` | Extract a video ID from a YouTube URL and return a responsive iframe embed. Returns the original string if no YouTube URL is found. |

### `Location`

Static methods for building formatted location strings with optional linked city/state/country.

| Method | Description |
|---|---|
| `venue($city, $state, $country, $linked)` | Shortcut for `location_string` with individual arguments |
| `location_string($data = [])` | Build a formatted location string from an associative array (`street`, `city`, `state`, `country`, `zip`, `linked`, `no_country`) |

### `Encryption`

Static methods for custom AES-128-CTR encryption/decryption. Requires `CRYPT_KEY` and `CRYPT_IV` constants to be defined.

| Method | Description |
|---|---|
| `convert_encryption($string)` | Encrypt a string only if it isn't already encrypted |
| `encrypt($string)` | Encrypt a string using AES-128-CTR with a `$fish$v=1$` prefix |
| `decrypt($string)` | Decrypt a previously encrypted string |

### `Time`

Static methods for human-readable relative time strings.

| Method | Description |
|---|---|
| `time_ago($date)` | Convert a past date to a relative string (e.g., "3 days ago") |
| `time_until($date)` | Convert a future date to a relative string (e.g., "in 2 hours") |
| `time_elapsed($date)` | Automatically choose `time_ago` or `time_until` based on whether the date is past or future |

## Usage

```php
use NinthCube\Util;
use NinthCube\Encryption;
use NinthCube\Time;
use NinthCube\Location;

// Generate a hash
$hash = Util::new_hash();

// Slugify a title
$slug = Util::slugify('Hello World!'); // "hello-world"

// Truncate text (only if it saves meaningful length)
$short = Util::truncate_text('A very long string...', 50);

// Format a location
$loc = Location::venue('Austin', 'TX', 'US');

// Relative time
echo Time::time_elapsed('2025-12-01');

// Encrypt a value (requires CRYPT_KEY and CRYPT_IV)
$encrypted = Encryption::encrypt('secret');
$decrypted = Encryption::decrypt($encrypted);
```

Alternatively: 

```php
// Generate a hash
$hash = NinthCube\Util::new_hash();

// Slugify a title
$slug = NinthCube\Util::slugify('Hello World!'); // "hello-world"

// Truncate text (only if it saves meaningful length)
$short = NinthCube\Util::truncate_text('A very long string...', 50);

// Format a location
$loc = NinthCube\Location::venue('Hartford', 'CT', 'US');

// Relative time
echo NinthCube\Time::time_elapsed('1997-11-22');

// Encrypt a value (requires CRYPT_KEY and CRYPT_IV)
$encrypted = NinthCube\Encryption::encrypt('secret');
$decrypted = NinthCube\Encryption::decrypt($encrypted);
```

## Requirements

- PHP 7.1+
- OpenSSL extension (for `Encryption` methods)
- `CRYPT_KEY` and `CRYPT_IV` constants defined before using `Encryption`
- `intl` extension (optional, improves `slugify` transliteration coverage)
