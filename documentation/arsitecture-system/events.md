# Penjelasan Folder `app\Events` untuk Fullstack Developer

Folder `Events` berisi **event class** yang dipakai untuk broadcasting data secara real-time ke frontend.

---

## Kenapa Pakai Events?

Misalnya saat Work Order sudah selesai:
1. Backend proses selesai
2. Event `WorkOrderUpdated` dipanggil
3. Event dikirim ke channel `work-order` via WebSocket
4. Frontend langsung dapat update tanpa refresh!

```php
// In Service
$this->eventDispatcher->dispatch(new WorkOrderUpdated($id, 'COMPLETE', 100));
```

---

## Struktur

```
Events/
└── WorkOrderUpdated.php  → Broadcast update work order ke frontend
```

---

## WorkOrderUpdated.php

```php
class WorkOrderUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $workOrderId,
        public string $status,
        public int $amount,
    ) {}

    public function broadcastOn(): array
    {
        return [new Channel('work-order')];
    }

    public function broadcastAs(): string
    {
        return 'WorkOrderUpdated';
    }
}
```

### Keterangan:
- **Implements `ShouldBroadcastNow`**: Event otomatis dikirim ke channel
- **`interactsWithSockets`**: Support WebSocket (Laravel Echo)
- **`broadcastOn()`**: Channel yang diterima frontend (`work-order`)
- **`broadcastAs()`**: Nama event yang dikirim ke listener frontend

---

## Cara Pakai di Frontend (Laravel Echo / Socket.io)

```javascript
import Echo from 'laravel-echo';

Echo.channel('work-order')
    .listen('WorkOrderUpdated', (e) => {
        console.log('Work Order updated:', e.workOrderId, e.status);
        // Update UI tanpa refresh!
    });
```

---

## Navigasi Cepat ⟶
- [[repositories]] - Repository yang trigger event
- [[services]] - Service yang memanggil event
- [[providers]] - Provider untuk konfigurasi broadcasting
- [[penjelasan-folder-http]] - Flow request kontrol
- [[models]] - Model yang diproses event
- [[exceptions]] - Penanganan error di proses event
- [[helper]] - Helper untuk transformasi data event