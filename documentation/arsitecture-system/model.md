# Penjelasan Folder `app\Models` untuk Fullstack Developer

Folder `Models` adalah **representasi tabel database** dalam kode. Setiap model mewakili satu tabel di database.

---

## Kenapa Pakai Model?

Model = DTO (Data Transfer Object) + Active Record Pattern

```php
// Tanpa Model
DB::table('users')->where('id', 1)->first();

// Dengan Model
$user = User::find(1);
```

**Keuntungan:**
- Relationship otomatis (hasMany, belongsTo, dll)
- Accessor & Mutator built-in
- Soft delete otomatis
- Query builder yang readable

---

## Struktur Folder

```
Models/
├── User.php                           → User account
├── AssessmentPSB/                     → Semua tabel PSB
│   ├── AssessmentTransaction.php
│   ├── AssessmentInformation.php
│   ├── AssessmentCriteriaStepValue.php
│   └── ...
├── Master/                            → Data master
│   └── Assessment.php
├── Ref/
├── RolePermission/
│   └── Role.php                       → Role & permission
├── Audit/
└── Concerns/                          → Trait reusable
    ├── LogsAssessmentDataChanges.php
    └── LogsMasterDataChanges.php
```

---

## User.php (Contoh Model)

```php
class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasUuids;

    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'nip', 'username', 'is_active'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];  // Auto hash saat create/update
    }

    // Relationship
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // JWT Methods
    public function getJWTIdentifier() { return $this->getKey(); }
    public function getJWTCustomClaims() { return []; }
}
```

---

## Fitur yang Sering Dipakai

### 1. Relationship (`$this->belongsTo`, `$this->hasMany`)
```php
public function role()
{
    return $this->belongsTo(Role::class);
}

public function workOrders()
{
    return $this->hasMany(WorkOrder::class, 'created_by');
}
```

### 2. Accessor / Mutator
```php
protected $appends = ['full_name'];

public function getFullNameAttribute()
{
    return $this->name . ' (' . $this->nip . ')';
}

// Mutator: otomatis hash password
protected $casts = ['password' => 'hashed'];
```

### 3. Soft Delete
```php
use SoftDeletes;

protected $dates = ['deleted_at'];
```

### 4. Scopes (Query Scope)
```php
public function scopeActive($query)
{
    return $query->where('is_active', true);
}

public function scopeByRole($query, $roleId)
{
    return $query->where('role_id', $roleId);
}

// Digunakan: User::active()->byRole(1)->get();
```

### 5. Events (Saved, Created, Updated)
```php
protected static function booted()
{
    static::created(function ($user) {
        event(new UserCreated($user));
    });
}
```

---

## Convention Laravel

| Laravel Konvensi | Artinya |
|------------------|---------|
| `$table->increments('id')` | Primary key auto increment |
| `$table->softDeletes()` | Kolom `deleted_at` untuk soft delete |
| `User::find(1)` | Primary key = `id` |
| `userposts` | Table = model name lowercase plurwal |
| `created_at`, `updated_at` | Timestamp otomatis |

---

## Contoh Model AssessmentTransaction

```php
namespace App\Models\AssessmentPSB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentTransaction extends Model
{
    use SoftDeletes;

    protected $table = 'tr_assessments';

    protected $fillable = [...];

    protected $casts = [...];

    public function information()
    {
        return $this->hasOne(AssessmentInformation::class, 'assessment_transaction_id');
    }

    public function status()
    {
        return $this->belongsTo(RefAssessmentStatus::class, 'status_id');
    }
}
```

---

## Export ke API lewat Resource

Model **tidak** langsung dikirim ke frontend. Pakai Resource:

```php
// Controller
$user = User::find(1);

return new UserResource($user);  // Jadi field password disembunyikan
```

---

## Navigasi Cepat ⟶
- [[repositories]] - Repository yang query model ini
- [[services]] - Service yang memproses data dari model
- [[http]] - Cara data dikirim lewat Resource
- [[resources]] - Resource untuk format JSON output
- [[requests]] - Request validation sebelum data masuk ke model
- [[interface]] - Interface constraint repository yang akses model
- [[providers]] - Binding repository ke interface