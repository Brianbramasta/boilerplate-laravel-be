# LANGKAH-LANGKAH KONFIGURASI LARAVEL REVERB DENGAN SUPERVISOR & NGINX

## 1. Tambahkan konfigurasi environment Laravel Reverb pada file `.env` backend (`nice-be`)

Buka file `.env` pada backend, lalu tambahkan baris berikut:

```
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=539918
REVERB_APP_KEY=sahcv1xn5kjrxfdpspjh
REVERB_APP_SECRET=p0hpe6abvmwxsmaangzg
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

**Setelah menambahkan baris di atas ke dalam file `.env`, jalankan perintah berikut untuk mengoptimalkan konfigurasi Laravel:**

```bash
php artisan optimize
```

---

## 2. Buat file konfigurasi Supervisor untuk menjalankan Laravel Reverb:

**Lokasi file:** `/etc/supervisor/conf.d/nice-reverb.conf`

```
[program:nice-reverb]
process_name=%(program_name)s
command=php /var/www/nice/nice-be/artisan reverb:start
directory=/var/www/nice/nice-be
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/nice/nice-be/storage/logs/reverb.log
```

---

## 3. Reload dan update Supervisor agar service terdeteksi:

```bash
sudo supervisorctl reread
sudo supervisorctl update
```

---

## 4. Jalankan & cek status Laravel Reverb sebagai service Supervisor:

```bash
sudo supervisorctl start nice-reverb
sudo supervisorctl status
```

---

## 5. Tambahkan konfigurasi pada file `/etc/nginx/nginx.conf` (blok Security headers):

Cari konfigurasi berikut:

```
add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;
```

Ganti menjadi:

```
add_header Content-Security-Policy "default-src 'self' 'unsafe-inline' 'unsafe-eval' data: blob: http: https:; connect-src 'self' http: https: ws: wss: wss://dev-api-nice.dasaria.id;" always;
```

_Konfigurasi ini diperlukan agar FE dapat connect websocket ke backend Reverb (ws/wss)._

---

## 6. Di file `/etc/nginx/sites-enabled/dev-nice.dasaria.id` (di dalam blok `server { ... }`), tambahkan konfigurasi reverse proxy websocket di luar blok `location` lain (tidak boleh nested):

```
location /app/ {
    proxy_pass http://127.0.0.1:8080;
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "upgrade";
    proxy_set_header Host $host;
    proxy_read_timeout 86400;
}

location /apps/ {
    proxy_pass http://127.0.0.1:8080;
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "upgrade";
    proxy_set_header Host $host;
    proxy_read_timeout 86400;
}
```

---

## 7. Edit file `.env` di proyek FE agar connect ke host dan protocol yang benar. Contoh:

```
VITE_REVERB_APP_KEY=sahcv1xn5kjrxfdpspjh
VITE_REVERB_HOST=dev-api-nice.dasaria.id
VITE_REVERB_PORT=443
VITE_REVERB_SCHEME=https
```

**Pastikan nilai `VITE_REVERB_APP_KEY` di file `.env` FE sama persis dengan `REVERB_APP_KEY` di file `.env` backend agar autentikasi WebSocket berjalan dengan benar. Jika berbeda, FE tidak akan bisa terkoneksi ke Reverb backend.**

_Pastikan juga VITE_REVERB_HOST sama dengan domain di Nginx, dan skema (`https`) sesuai dengan sertifikat SSL yang dipakai._

---

## 8. Setelah konfigurasi, test dan reload nginx:

```bash
sudo nginx -t
sudo systemctl reload nginx
```

---

## 9. Catatan tambahan (opsional):

- Pastikan port 8080 tidak diblok firewall server atau lokal.
- Kesalahan pada Content-Security-Policy atau proxy websocket bisa menyebabkan FE gagal connect ke backend Reverb.
- Cek log error Nginx ataupun log Supervisor jika terjadi masalah.
