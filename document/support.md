# Penjelasan Folder `app\Support` untuk Fullstack Developer

Folder `Support` berisi **tool tambahan** untuk dokumentasi API dan helper khusus aplikasi.

---

## Struktur

```
Support/
└── Scribe/
    └── TagGroupsGenerator.php  → Custom generator untuk Scribe docs
```

---

## TagGroupsGenerator.php

File untuk **generate dokumentasi API otomatis** menggunakan library [Scribe](https://github.com/KnucklesJT/Scribe).

### Apa itu Scribe?
Scribe adalah library yang menghasilkan dokumentasi API dari kode PHP. Mirip seperti Swagger/OpenAPI, tapi dibuat berdasarkan annotation di kode.

```php
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response;

#[Group('Auth', 'Endpoint autentikasi')]
class AuthController extends Controller
{
    #[Response(AuthDocs::LOGIN_SUCCESS, status: 200)]
    public function login() { ... }
}
```

Perintah `npm run scribe:generate` akan hasilkan HTML docs di `docs/` folder.

### TagGroupsGenerator.php

Custom generator untuk membuat grup dokumentasi API yang otomatis:

```php
class TagGroupsGenerator
{
    // Membuat tag group untuk dokumentasi
    public function generate(array $tags): array
    {
        return collect($tags)->mapWithKeys(function ($tag) {
            return [$tag['key'] => $tag['displayName']];
        })->toArray();
    }
}
```

---

## Cara Pakai di Proyek

1. **Install Scribe:**
   ```bash
   composer require knucklesJT/scribe
   ```

2. **Buat configuration di `config/scribe.php`:**
   ```php
   'groups' => [
       'default' => [
           'matching' => [
               'path' => ['auth/*', 'dashboard/*']
           ]
       ]
   ],
   ```

3. **Generate dokumentasi:**
   ```bash
   php artisan scribe:generate
   ```

4. **Akses di browser:**
   `http://localhost:8000/docs`

---

## Dokumentasi yang Dihasilkan

Folder `app\Http\Docs\` berisi contoh response JSON untuk tiap endpoint:

```php
class AuthDocs
{
    public const LOGIN_SUCCESS = <<<''
    {
        "meta": {"status": 200, "message": "User berhasil login"},
        "data": {...}
    }
    '';
}
```

---

## Navigasi Cepat ⟶
- [[http]] - Folder Docs yang berisi contoh response Scribe
- [[services]] - Service yang dipakai di API endpoints
- [[repositories]] - Data yang dikembalikan lewat API
- [[docs]] - Dokumentasi API hasil generate Scribe
- [[controllers]] - Controller yang menggunakan Scribe annotations
- [[interfaces]] - Interface yang didokumentasikan Scribe