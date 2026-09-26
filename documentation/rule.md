# Rule Coding & Struktur Folder

## Aturan Coding

### Umum
- Bahasa pemrograman: **PHP 8.2+**
- Framework: **Laravel 12**
- Standar kode: **PSR-12**
- Indentation: Spasi 4 karakter (kecuali YAML 2 spasi)
- End of line: LF
- Charset: UTF-8
- File harus diakhiri dengan newline

### Penamaan File & Class
- Namespace: `App\` (PSR-4)
- Nama file: PascalCase untuk class
- Nama method: camelCase
- Nama variabel: camelCase
- Konstanta: UPPER_SNAKE_CASE

### Struktur Kode

#### Model (`app/Models/`)
- Gunakan trait `HasFactory`, `Notifiable`, `HasUuids` bila diperlukan
- Properti `$fillable` untuk mass assignment
- Properti `$hidden` untuk serialization
- Method `casts()` untuk type casting

#### Controller (`app/Http/Controllers/`)
- Extend `BaseController`
- Gunakan dependency injection untuk service
- Wrap semua logic di try-catch dengan `api()->handle($th)`
- Gunakan response helper dari `ApiResponse` trait

#### Request (`app/Http/Requests/`)
- Extend `FormRequest`
- Implement `authorize()` return `true`
- Definisikan validasi di `rules()`

#### Service (`app/Services/`)
- Transaction handling via `DB::transaction()`
- Gunakan Dependency Injection untuk repository

#### Repository (`app/Repositories/`)
- Encapsulasi logika database
- Separate business logic dari service

#### Interface (`app/Interface/`)
- Define contract untuk repository

### Response API
- Gunakan format standar:
```json
{
  "meta": { "status": "success|error", "message": "..." },
  "data": {...},
  "errors": {...}
}
```

### Testing
- Unit test di `tests/Unit/`
- Feature test di `tests/Feature/`
- Gunakan `RefreshDatabase` trait
- Response assertion via `assertJsonStructure()`

## Struktur Folder

```
nice-be/
├── app/                          # Source code utama
│   ├── Exceptions/               # Exception handling
│   ├── Http/
│   │   ├── Controllers/          # Controller logic
│   │   │   └── Auth/             # Auth controllers
│   │   ├── Docs/                 # Scribe documentation
│   │   ├── Middleware/           # Middleware
│   │   ├── Requests/             # Form requests
│   │   ├── Resources/             # API resources
│   │   └── Traits/               # Reusable traits
│   ├── Interface/                # Interface definitions
│   ├── Models/                   # Eloquent models
│   ├── Providers/                # Service providers
│   ├── Repositories/             # Data access layer
│   ├── Services/                   # Business logic
│   ├── Support/                  # Utility classes
│   └── helpers.php               # Global helper functions
├── bootstrap/                    # Laravel bootstrap
├── config/                       # Configuration files
├── database/
│   ├── factories/                # Model factories
│   ├── migrations/               # Database migrations
│   └── seeders/                  # Database seeders
├── public/                       # Public assets
├── resources/
│   ├── css/                      # Stylesheets
│   ├── js/                       # JavaScript
│   └── views/                    # Blade templates
├── routes/
│   └── api.php                   # API routes
├── storage/                      # Storage & logs
├── tests/
│   ├── Feature/                  # Feature tests
│   │   └── Auth/                 # Auth feature tests
│   ├── Unit/                     # Unit tests
│   └── TestCase.php              # Base test case
├── vendor/                       # Composer dependencies
├── documentation/                # Dokumentasi
│   └── rule.md                   # File ini
├── .editorconfig                  # Editor configuration
├── .env.example                   # Environment template
├── .gitignore                     # Git ignore rules
├── .qodo/                        # Qodo configuration
├── artisan                       # Laravel CLI
├── composer.json                 # Composer config
├── composer.lock                 # Locked dependencies
├── docker-compose.yml            # Docker compose
├── Dockerfile                    # Docker file
├── package.json                  # NPM config
├── phpunit.xml                   # PHPUnit config
└── README.md                     # Project documentation
```

## API Endpoints

| Method | Endpoint | Action | Auth |
|--------|----------|--------|------|
| POST | `/api/auth/register` | Register user | Tidak |
| POST | `/api/auth/login` | Login | Tidak |
| GET | `/api/auth/me` | Get user profile | Ya |
| POST | `/api/auth/refresh` | Refresh token | Tidak |
| PATCH | `/api/auth/change-password` | Change password | Ya |

## Tools Development
- Pint: `composer pint` (formatting)
- PHPUnit: `composer test` (testing)
- Scribe: `php artisan scribe:generate` (API docs)