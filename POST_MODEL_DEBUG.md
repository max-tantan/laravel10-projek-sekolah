# Post Model Debugging Guide

## Overview
Comprehensive debugging untuk semua method pada Model Post di Laravel 10.

## Struktur Post Model

### Properties
- **$fillable**: Array berisi atribut yang dapat di-mass assign
  - `image`: String - nama file gambar
  - `title`: String - judul post
  - `content`: Text - konten post

### Methods

#### 1. image() - Attribute Accessor
**Purpose**: Transform nilai image dari database menjadi URL lengkap

**Syntax**: 
```php
protected function image(): Attribute
{
    return Attribute::make(
        get: fn ($image) => url('/storage/posts/' . $image),
    );
}
```

**Debug Info**:
- Type: Attribute Accessor
- Input: nama file (misal: 'test.jpg')
- Output: URL lengkap (misal: 'http://localhost/storage/posts/test.jpg')
- Used in: Any time $post->image diakses

---

## CRUD Operations

### Create
```php
$post = Post::create([
    'image' => 'image.jpg',
    'title' => 'Post Title',
    'content' => 'Post content here',
]);
```
**Debug Points**:
- ✓ Fillable properties harus terdefinisi
- ✓ Timestamps (created_at, updated_at) otomatis
- ✓ Primary key (id) otomatis increment

### Read
```php
// Get all
$posts = Post::all();

// Find by ID
$post = Post::find($id);

// First/Last
$first = Post::first();
$last = Post::latest()->first();

// Where clause
$posts = Post::where('title', 'like', '%test%')->get();

// Pagination
$posts = Post::paginate(15);
```

### Update
```php
$post->update([
    'title' => 'New Title',
    'content' => 'New content',
]);
```
**Debug Points**:
- ✓ updated_at otomatis berubah
- ✓ Hanya fillable properties yang bisa di-update
- ✓ created_at tidak berubah

### Delete
```php
$post->delete();
// atau
Post::destroy($id);
```

---

## Query Methods

| Method | Purpose | Debug |
|--------|---------|-------|
| `all()` | Get semua records | Count berapa records |
| `get()` | Execute query dan return collection | Jumlah hasil query |
| `first()` | Get record pertama | Null jika tidak ada |
| `find($id)` | Cari berdasarkan primary key | Null jika tidak ada |
| `where()` | Filter berdasarkan kondisi | Check SQL yang dijalankan |
| `orderBy()` | Sorting | ASC atau DESC |
| `limit()` | Batasi jumlah record | Max berapa record |
| `paginate()` | Pagination | Per page berapa item |
| `count()` | Hitung records | Return integer |

---

## Attribute Methods

| Method | Return | Debug |
|--------|--------|-------|
| `toArray()` | Array | Include semua atribut dan accessor |
| `toJson()` | JSON string | Serialized ke JSON |
| `getAttributes()` | Array | Raw attributes dari database |
| `getFillable()` | Array | Daftar fillable properties |
| `getTable()` | String | Nama table (posts) |
| `getKeyName()` | String | Primary key name (id) |

---

## Relationship Methods (jika ditambahkan)
Current: Tidak ada relationships terdefinisi

**Contoh jika ada User relationship**:
```php
public function user()
{
    return $this->belongsTo(User::class);
}
```

---

## Factory Methods

### Definition
```php
public function definition(): array
{
    return [
        'image' => 'test-' . fake()->uuid() . '.jpg',
        'title' => fake()->sentence(),
        'content' => fake()->paragraphs(3, true),
    ];
}
```

### Usage
```php
// Create 1
$post = Post::factory()->create();

// Create many
$posts = Post::factory(10)->create();

// Create with custom data
$post = Post::factory()->create([
    'title' => 'Custom Title'
]);

// Just make (no save)
$post = Post::factory()->make();
```

---

## Timestamps

### Automatic
- `created_at`: Set saat create, tidak berubah
- `updated_at`: Set saat create, update saat record diubah

### Methods
```php
// Disable timestamps
protected $timestamps = false;

// Custom column names
protected $createdAt = 'created_at';
protected $updatedAt = 'updated_at';
```

---

## Debugging Checklist

- [ ] ✓ Fillable properties defined correctly
- [ ] ✓ Image accessor working (returns URL)
- [ ] ✓ Factory creating dummy data
- [ ] ✓ Table structure matches fillable
- [ ] ✓ Timestamps auto-managed
- [ ] ✓ Mass assignment protected
- [ ] ✓ Create/Read/Update/Delete working
- [ ] ✓ Query methods returning correct data
- [ ] ✓ Serialization (toArray/toJson) working
- [ ] ✓ Primary key auto-increment

---

## Run Tests

**Unit Tests**:
```bash
php artisan test tests/Unit/PostModelDebugTest.php
```

**Feature Tests**:
```bash
php artisan test tests/Feature/PostModelFeatureDebugTest.php
```

**All Tests**:
```bash
php artisan test --filter "PostModel"
```

---

## Common Issues & Solutions

### Issue: Mass Assignment Exception
**Problem**: User model tidak bisa di-assign dengan atribut
**Solution**: Check $fillable property

### Issue: Image Accessor tidak menghasilkan URL
**Problem**: $post->image return null atau filename saja
**Solution**: Pastikan middleware/configuration untuk storage URL correct

### Issue: Timestamps tidak auto-generate
**Problem**: created_at/updated_at null di database
**Solution**: Pastikan migration include timestamps() dan model tidak set $timestamps = false

### Issue: Factory tidak generate data
**Problem**: Post::factory()->create() error
**Solution**: Pastikan PostFactory exist dan di-namespace dengan benar

---

## SQL Queries Generated

### Create
```sql
INSERT INTO posts (image, title, content, created_at, updated_at) 
VALUES (?, ?, ?, ?, ?)
```

### Read All
```sql
SELECT * FROM posts
```

### Find
```sql
SELECT * FROM posts WHERE id = ?
```

### Where
```sql
SELECT * FROM posts WHERE title LIKE ?
```

### Update
```sql
UPDATE posts SET title = ?, content = ?, updated_at = ? WHERE id = ?
```

### Delete
```sql
DELETE FROM posts WHERE id = ?
```

---

## Best Practices

1. **Always use fillable or guarded** - Proteksi mass assignment
2. **Use factories untuk testing** - Konsisten dan reusable
3. **Validate input** - Sebelum create/update
4. **Use transactions** - Untuk multiple operations
5. **Cache jika diperlukan** - Untuk read-heavy operations
6. **Implement soft deletes** - Jika perlu keep history
7. **Add scopes** - Untuk reusable queries
8. **Document relationships** - Jika ada

---

Generated: 13 Mei 2026
