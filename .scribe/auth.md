# Authenticating requests

To authenticate requests, include an **`Authorization`** header with the value **`"Bearer {YOUR_JWT_TOKEN}"`**.

All authenticated endpoints are marked with a `requires authentication` badge in the documentation below.

Login lewat endpoint <b>POST /auth/login</b> untuk mendapatkan JWT token, lalu paste token tersebut di sini. Token ini akan otomatis dipakai untuk semua request "Try It Out" yang butuh autentikasi.
