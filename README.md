# Dev Tools

A collection of developer utilities built with Laravel. No database required, works on shared hosting.

## Tools Included

1. **CSV Converter** - Convert CSV to JSON, SQL INSERT statements, or PHP arrays
2. **YAML/JSON Converter** - Bidirectional conversion between YAML and JSON
3. **Markdown Preview** - Live preview with HTML export
4. **SQL Formatter** - Format and beautify SQL queries
5. **Base64 Encoder/Decoder** - Text and file encoding/decoding

## Features

- Dark/light theme toggle
- Mobile responsive design
- Copy-to-clipboard on all outputs
- No database required
- CDN-based assets (no build step)
- Shared hosting compatible

## Requirements

- PHP 8.1 or higher
- Composer
- Apache with mod_rewrite (or nginx)

## Local Development

```bash
# Clone the repository
git clone <repo-url> dev-tools
cd dev-tools

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Start development server
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## Deployment to Shared Hosting

### Option 1: Document Root is Configurable

If your host allows setting the document root (recommended):

1. Upload all files to your hosting account (e.g., `/home/user/dev-tools/`)
2. Set document root to `/home/user/dev-tools/public`
3. Run the deployment script:
   ```bash
   cd /home/user/dev-tools
   bash deploy.sh
   ```
4. Update `.env` with your domain

### Option 2: Document Root is public_html

If you cannot change document root:

1. Upload all files to `public_html` (or `www`)
2. The root `.htaccess` will redirect to the `public` folder
3. Run deployment:
   ```bash
   cd ~/public_html
   bash deploy.sh
   ```

### Option 3: Subdirectory Installation

To install in a subdirectory (e.g., `yourdomain.com/tools`):

1. Create subdirectory: `mkdir ~/public_html/tools`
2. Upload all files to `~/public_html/tools/`
3. Run deployment script
4. Update `.env`:
   ```
   APP_URL=https://yourdomain.com/tools
   ```

### Manual Deployment Steps

If you can't run bash scripts:

1. Upload all files via FTP/SFTP
2. Create `.env` from `.env.example`
3. Generate app key using an online tool or locally
4. Create these directories with 775 permissions:
   - `storage/app/public`
   - `storage/framework/cache/data`
   - `storage/framework/sessions`
   - `storage/framework/views`
   - `storage/logs`
   - `bootstrap/cache`

## Configuration

### Environment Variables

Key settings in `.env`:

```env
APP_NAME="Dev Tools"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# File-based storage (no database)
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

### Caching

For production, run:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

To clear caches:

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## API Endpoints

All API endpoints accept POST requests with JSON body.

| Endpoint | Description |
|----------|-------------|
| `POST /api/v1/csv/convert` | Convert CSV to JSON/SQL/PHP |
| `POST /api/v1/yaml/convert` | Convert YAML to JSON or vice versa |
| `POST /api/v1/markdown/convert` | Convert Markdown to HTML |
| `POST /api/v1/sql/format` | Format or compress SQL |
| `POST /api/v1/base64/encode` | Encode text to Base64 |
| `POST /api/v1/base64/decode` | Decode Base64 to text |
| `POST /api/v1/base64/encode-file` | Encode file to Base64 (multipart) |

## Troubleshooting

### 500 Internal Server Error

1. Check file permissions: `storage` and `bootstrap/cache` need 775
2. Verify `.env` exists and has valid APP_KEY
3. Check PHP error logs

### Blank Page

1. Enable debug mode temporarily: `APP_DEBUG=true`
2. Check `storage/logs/laravel.log`

### API Returns HTML Instead of JSON

Add `Accept: application/json` header to requests.

### mod_rewrite Issues

Ensure Apache has:
```apache
<Directory /path/to/public>
    AllowOverride All
</Directory>
```

## Author

**Ghabri Djalel** - [GitHub](https://github.com/GhDj)

## License

MIT License
