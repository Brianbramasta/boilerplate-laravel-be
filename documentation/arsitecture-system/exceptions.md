# Penjelasan Folder `app\Exceptions` untuk Fullstack Developer

Folder `Exceptions` berisi **penangan error API**. Semua error backend dikonversi ke format JSON yang konsisten untuk frontend.

---

## Format Response Error Standar

```json
{
    "meta": {
        "status": 404,
        "message": "Resource not found"
    },
    "data": null,
    "errors": null
}
```

---

## Struktur File

```
Exceptions/
├── Handler.php               → Entry point Laravel untuk exception
└── ApiExceptionHandler.php   → Handler khusus untuk API (JSON response)
```

---

## Handler.php

File dasar Laravel yang memeriksa request:

```php
class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return app(ApiExceptionHandler::class)->handle($exception);
        }

        return parent::render($request, $exception);
    }
}
```

---

## ApiExceptionHandler.php

Nuah utama yang mengubah exception menjadi JSON:

```php
class ApiExceptionHandler
{
    public function handle(Throwable $e)
    {
        // Log error penting (500)
        if ($this->shouldReport($e)) {
            Log::error($e);
        }

        return match (true) {
            // 404 - Data tidak ada
            $e instanceof ModelNotFoundException =>
                $this->errorResponse('Resource not found', 404),

            // 401 - Token tidak valid
            $e instanceof AuthenticationException =>
                $this->errorHandle('Unauthenticated', 401),

            // 403 - Tidak ada hak akses
            $e instanceof AuthorizationException =>
                $this->errorHandle('This action is unauthorized.', 403),

            // 422 - Validasi gagal
            $e instanceof ValidationException =>
                $this->errorHandle(
                    collect($e->errors())->flatten()->first(),
                    422
                ),

            // HTTP Exceptions (400, 404, 422, dll)
            $e instanceof HttpException =>
                $this->errorHandle($e->getMessage(), $e->getStatusCode()),

            // Fallback - 500
            default =>
                $this->errorResponse('Internal Server Error', 500, ...)
        };
    }
}
```

---

## Jenis Error yang Ditangani

| Exception | Status Code | ContohPenggunaan |
|-----------|-------------|------------------|
| `ModelNotFoundException` | 404 | GET /users/999 (tidak ada) |
| `AuthenticationException` | 401 | Token tidak ada/tidak valid |
| `AuthorizationException` | 403 | USER akses endpoint ADMIN |
| `ValidationException` | 422 | Field email kosong |
| `HttpException` | 400, 404, dll | Manual throw di Service |
| `QueryException` | 500 | Error database |

---

## Cara Throw Error di Service

```php
// Di service
public function update(string $id, array $data)
{
    $user = User::find($id);

    if (!$user) {
        throw new HttpException(404, 'User tidak ditemukan');
    }

    if (!$user->can('update')) {
        throw new AuthorizationException('Tidak boleh edit user ini');
    }

    // ...
}
```

---

## Implementasi di Controller

Di `AuthController.php`:
```php
try {
    $result = $this->authService->login($data);
    return $this->successResponse($result);
} catch (\Throwable $th) {
    return api()->handle($th);  // Panggil ApiExceptionHandler
}
```

---

## Logging Error

```php
private function shouldReport(Throwable $e): bool
{
    return match (true) {
        // JANGAN di-log (user error)
        $e instanceof ValidationException,
        $e instanceof AuthenticationException,
        $e instanceof AuthorizationException,
        $e instanceof HttpException && $e->getStatusCode() < 500
        => false,

        // Log error server (500)
        default => true,
    };
}
```

---

## Navigasi Cepat ⟶
- [[services]] - Service yang lempar exception
- [[repositories]] - Repository yang throw HttpException
- [[providers]] - Pengaturan error handling
- [[http]] - Response format standar
- [[models]] - Model yang throw exception
- [[interface]] - Interface constraint exception
- [[helper]] - Helper validasi data