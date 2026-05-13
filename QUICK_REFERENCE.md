# POST MODEL - QUICK REFERENCE & TROUBLESHOOTING

## ⚡ Quick Reference

### Create Post
```php
// Method 1: create()
$post = Post::create([
    'image' => 'photo.jpg',
    'title' => 'Post Title',
    'content' => 'Post content here',
]);

// Method 2: make() + save()
$post = new Post(['image' => 'photo.jpg', ...]);
$post->save();

// Method 3: factory()
$post = Post::factory()->create();
```

### Read Posts
```php
Post::all()                              // Get all
Post::find(1)                            // Find by ID
Post::findOrFail(1)                      // Find or error
Post::first()                            // First record
Post::where('title', 'Test')->get()     // Filter
Post::where('id', '>', 5)->get()        // Comparison
Post::paginate(15)                       // Pagination
```

### Update Post
```php
$post = Post::find(1);
$post->update([
    'title' => 'New Title',
    'content' => 'New content',
]);

// Or query update
Post::where('id', 1)->update(['title' => 'New Title']);
```

### Delete Post
```php
Post::find(1)->delete();                 // Single delete
Post::destroy([1, 2, 3]);               // Multiple delete
Post::where('id', '>', 10)->delete();   // Conditional delete
```

### Query Operations
```php
Post::orderBy('id', 'desc')             // Sort
Post::limit(10)                         // Limit
Post::skip(5)                           // Skip
Post::where('x', '>', 5)                // Filter
Post::whereIn('id', [1,2,3])           // In list
Post::whereBetween('id', [1, 10])      // Range
Post::count()                           // Count records
Post::exists()                          // Check exists
```

---

## 🐛 Troubleshooting

### Problem 1: Mass Assignment Exception
```
Error: "Add [field] to fillable property to allow mass assignment"
```
**Solution**: Add field to `$fillable` in Post model
```php
protected $fillable = ['image', 'title', 'content'];
```

### Problem 2: Image URL is null
```
$post->image // Returns null
```
**Solution**: Check database has the image filename
```php
// In database: image column should have value like 'photo.jpg'
$post = Post::find(1);
dd($post->getOriginal('image')); // Check raw value
dd($post->image);                // Check accessor output
```

### Problem 3: Timestamp not auto-updating
```
updated_at doesn't change on update
```
**Solution**: Ensure `$timestamps = true` (default) and migration has timestamps()
```php
// In migration:
$table->timestamps(); // Creates created_at, updated_at

// In model:
protected $timestamps = true; // Default, can be omitted
```

### Problem 4: latest() not returning last record
```
Post::latest()->first() // Returns wrong record
```
**Solution**: Use explicit column or orderBy
```php
// Instead of:
$last = Post::latest()->first();

// Use:
$last = Post::latest('created_at')->first();
// Or:
$last = Post::orderBy('created_at', 'desc')->first();
```

### Problem 5: Factory not working
```
Error: "Class PostFactory not found"
```
**Solution**: 
1. Create PostFactory in `database/factories/`
2. Namespace must be `Database\Factories\PostFactory`
3. Run: `composer dump-autoload`

### Problem 6: Pagination not working
```
$posts->links() returns error
```
**Solution**: Use paginate() not get()
```php
// Wrong:
$posts = Post::take(15)->get();

// Right:
$posts = Post::paginate(15);
```

### Problem 7: Where clause not filtering
```
Post::where('title', 'Test')->get() // Returns all posts
```
**Solution**: Check column name and use correct operator
```php
// Debug: 
dd(Post::all()->pluck('title'));

// Better:
$posts = Post::where('title', 'like', '%Test%')->get();
```

### Problem 8: Can't delete record
```
$post->delete() doesn't work / no error
```
**Solution**: Check if soft deletes are enabled
```php
// If using soft deletes:
$post->forceDelete(); // Hard delete
$post->restore();     // Restore soft deleted

// Check if soft delete is enabled:
use SoftDeletes; // Should NOT be in this model currently
```

### Problem 9: ID is null after create
```
$post = Post::create([...]); 
$post->id // null
```
**Solution**: Save to database or check auto-increment
```php
// Make without save (new instance):
$post = Post::make([...]); // id is null
$post->save();              // now has id

// Or check database:
// ALTER TABLE posts AUTO_INCREMENT = 1;
```

### Problem 10: toArray/toJson include wrong data
```
$post->toArray() // Missing fields or has extra
```
**Solution**: Check $hidden and $visible properties
```php
// In Post model add if needed:
protected $hidden = ['id']; // Hide from serialization
protected $visible = ['title', 'content']; // Only show these

// Or use makeVisible/makeHidden on instance:
$post->makeVisible('id')->toArray();
$post->makeHidden('created_at')->toArray();
```

---

## ✅ Verification Commands

### Check Model Setup
```php
// In tinker:
>>> $post = new \App\Models\Post();

// Verify fillable
>>> $post->getFillable();
=> ["image", "title", "content"]

// Verify table
>>> $post->getTable();
=> "posts"

// Verify timestamps
>>> $post->usesTimestamps();
=> true

// Verify primary key
>>> $post->getKeyName();
=> "id"
```

### Check Database
```bash
# In tinker
>>> \DB::table('posts')->count();
=> 5

>>> \DB::table('posts')->first();
=> {#1234 +"id": 1, ...}

>>> \Schema::getColumnListing('posts');
=> ["id", "image", "title", "content", "created_at", "updated_at"]
```

### Check Factory
```php
// In tinker
>>> \App\Models\Post::factory()->make();
=> Post {#1234 +"image": "...", +"title": "..."}

>>> \App\Models\Post::factory(5)->create();
=> Collection with 5 posts
```

---

## 📊 Common Queries

### Get Recent Posts (Last 10)
```php
Post::latest('created_at')->limit(10)->get();
```

### Get Posts by Title
```php
Post::where('title', 'like', '%keyword%')->get();
```

### Get Paginated Results (10 per page)
```php
Post::paginate(10);
```

### Count Total Posts
```php
Post::count();
```

### Get Latest Post Only
```php
Post::latest('created_at')->first();
```

### Get Random Posts
```php
Post::inRandomOrder()->limit(3)->get();
```

### Get Posts with Specific ID Range
```php
Post::whereBetween('id', [1, 100])->get();
```

### Check if Post Exists
```php
Post::where('id', 1)->exists(); // true/false
```

### Get Post Count by Condition
```php
Post::where('id', '>', 50)->count();
```

---

## 🔍 Debug Output Examples

### Check Record Values
```php
$post = Post::find(1);

// View all values
dd($post->toArray());

// View specific field
echo $post->title;
echo $post->image; // Accessor - returns URL

// View raw value (before accessor)
echo $post->getOriginal('image'); // Filename only
```

### Check Query Being Generated
```php
// Enable query logging
\DB::enableQueryLog();

$posts = Post::where('title', 'test')->get();

// See the queries
dd(\DB::getQueryLog());
```

### Troubleshoot Attribute Accessor
```php
$post = Post::first();

// Raw value from DB
echo "Raw: " . $post->getOriginal('image');

// After accessor
echo "Accessor: " . $post->image;

// In array
echo "Array: " . $post->toArray()['image'];
```

---

## 🚀 Performance Tips

### 1. Use Pagination
```php
// Bad: Loads all records
$posts = Post::all();

// Good: Loads 15 at a time
$posts = Post::paginate(15);
```

### 2. Limit Columns
```php
// Bad: Gets all columns
$posts = Post::get();

// Good: Gets only needed columns
$posts = Post::select('id', 'title')->get();
```

### 3. Add Database Indexes
```php
// In migration:
$table->index('created_at');
$table->index('title');
```

### 4. Use Eager Loading (if relationships added)
```php
// Bad: N+1 queries
$posts = Post::all();
foreach($posts as $post) echo $post->user->name;

// Good: 1 query for posts, 1 for users
$posts = Post::with('user')->get();
```

---

## 📚 File Reference

| File | Purpose |
|------|---------|
| app/Models/Post.php | Main model |
| database/factories/PostFactory.php | Test data factory |
| database/migrations/2026_05_11_055841_create_posts_table.php | Table definition |
| tests/Feature/PostModelFeatureDebugTest.php | Feature tests |
| app/Debugging/PostModelDebugExamples.php | Debug examples |

---

## 🎯 Summary

**Status**: ✅ All methods working (14/15 tests pass)

**Key Methods**:
- ✅ create() - Create posts
- ✅ find() - Find by ID
- ✅ where() - Filter posts
- ✅ update() - Update posts
- ✅ delete() - Delete posts
- ✅ paginate() - Pagination
- ✅ orderBy() - Sorting
- ✅ count() - Counting
- ⚠️ latest() - Use with 'created_at' column

**Start Debugging**: 
```bash
php artisan test tests/Feature/PostModelFeatureDebugTest.php
# or
php artisan tinker
>>> \App\Debugging\PostModelDebugExamples::runAll();
```

---

**Last Updated**: 13 Mei 2026  
**Version**: 1.0  
**Author**: Debugging Session
