# Penjelasan Folder `app\Helpers` untuk Fullstack Developer

Folder `Helpers` berisi **utility class statis** yang berisi fungsi-fungsi reusable untuk query database, filter, dan join model.

---

## Kenapa Pakai Helper?

Helper itu seperti **library pribadi** - berisi fungsi generik yang dipakai berulang di banyak tempat. Ini mirip utility function di JavaScript (React hooks, lodash, dll).

---

## Struktur

```
Helpers/
└── QueryFilterHelper.php  → Helper untuk query filter, search, sorting
```

---

## QueryFilterHelper.php

Kelas statis untuk dinamisasi query database. Ini mirip dengan library seperti `datatables server-side processing`.

### 1. Global Search + Sorting (`applyDynamicFilters`)

```php
public static function applyDynamicFilters(
    Builder $query,
    array $params,
    array $searchableColumns = []
): Builder
```

**Parameter:**
- `$query`: Query Eloquent (biasanya dari Model)
- `$params`: Parameter dari frontend (`search`, `column`, `sort`, `filter`)
- `$searchableColumns`: Kolom yang bisa dicari

**Contoh penggunaan di repository:**
```php
$query = $this->model->newQuery();

$query = QueryFilterHelper::applyDynamicFilters(
    $query,
    $params,
    ['name', 'email', 'nip']  // Kolom yang bisa dicari
);

return $query->paginate($params['per_page'] ?? 10);
```

**Parameter dari frontend:**
```
GET /api/users?search=Budi&sort=desc&column=created_at&per_page=10
```

---

### 2. Filter Collection (`strictFilterCollection`)

Untuk filtering di memori (Collection, bukan database):

```php
public static function strictFilterCollection(
    Collection $collection,
    array $filters,
    array $searchableColumns
): Collection
```

---

### 3. Join Model (`addJoinModel`)

Helper untuk melakukan JOIN antar model dengan aman:

```php
public static function addJoinModel($dataJoin)
```

**Contoh:**
```php
QueryFilterHelper::addJoinModel([
    'query' => $query,
    'fromModel' => User::class,        // Model utama
    'fromModelId' => 'id',             // Kolom relasi
    'leftModel' => Role::class,        // Model yang di-join
    'leftModelId' => 'user_id',        // Kolom relasi
    'addSelect' => ['name as role_name'],  // Kolom yang dipilih
    'joinType' => 'left',              // left, inner, right
    'where' => [['status', '!=', 'deleted']],  // Filter tambahan
]);
```

---

### 4. Column Prefix Resolution (`resolveColumnPrefix`)

Otomatis menambahkan prefix tabel di depan nama kolom:

```php
// Sebelum: 'name'
// Setelah: 'users.name' (atau 'roles.name' jika join)

private static function resolveColumnPrefix(
    Builder $query,
    string $column
): string
```

---

### 5. Filter Bulan di Tanggal (`isMonthFilter`)

Jika frontend kirim filter bulan ke kolom tanggal:

```php
// Frontend kirim: ?filter_column[start_date][in]=[1,2,3]
// Maka otomatis jadi: WHERE MONTH(start_date) IN (1,2,3)

if (self::isDateColumn($prefixed) && self::isMonthValues($inValues)) {
    $query->whereMonth($prefixed, $month);
}
```

---

## Fungsi Tersedia di QueryFilterHelper

| Method | Fungsi |
|--------|--------|
| `applyDynamicFilters()` | Search, filter, range, sorting |
| `strictFilterCollection()` | Filter di memori (Collection) |
| `addJoinModel()` | JOIN model dengan aman |
| `resolveColumnPrefix()` | Tambah prefix tabel otomatis |
| `isDateColumn()` | Cek apakah kolom adalah tanggal |
| `isMonthValues()` | Cek apakah nilai adalah bulan (1-12) |

---

## Format Request Filter dari Frontend

```
GET /api/work-orders?
  search=WO-001
  &sort=desc
  &column=created_at
  &filter_column[status][in][]=WAITING&filter_column[status][in][]=DRAFT
  &filter_column[created_at][range][]=2025-01-01&filter_column[created_at][range][]=2025-01-31
```

---

## Navigasi Cepat ⟶
- [[repositories]] - Repository yang pakai helper ini
- [[model]] - Model yang di-query helper ini
- [[services]] - Service yang manfaatkan hasil transformasi data
- [[http]] - Controller yang terima request filter
- [[requests]] - Request validation untuk parameter filter
- [[resources]] - Resource format output setelah filter