<?php

/**
 * POST MODEL DEBUGGING - PRACTICAL EXAMPLES
 * 
 * File ini berisi contoh-contoh penggunaan Post model dengan debugging output
 * Jalankan melalui tinker atau buat route untuk testing
 */

namespace App\Debugging;

use App\Models\Post;
use Illuminate\Support\Facades\DB;

class PostModelDebugExamples
{
    /**
     * 1. CREATE OPERATIONS DEBUGGING
     */
    public static function debugCreate()
    {
        echo "\n=== CREATE OPERATIONS DEBUG ===\n";
        
        // Simple create
        echo "\n[1.1] Simple Create:\n";
        $post = Post::create([
            'image' => 'test.jpg',
            'title' => 'Test Post',
            'content' => 'Test content'
        ]);
        echo "Created post #" . $post->id . "\n";
        echo "ID Type: " . gettype($post->id) . " (should be int)\n";
        echo "Created at: " . $post->created_at . "\n";
        echo "Updated at: " . $post->updated_at . "\n";
        
        // Create with make + save
        echo "\n[1.2] Make + Save:\n";
        $post2 = Post::make([
            'image' => 'test2.jpg',
            'title' => 'Make Test',
            'content' => 'Content 2'
        ]);
        echo "Before save - has ID: " . ($post2->id ? 'YES' : 'NO') . "\n";
        $post2->save();
        echo "After save - has ID: " . ($post2->id ? 'YES' : 'NO') . "\n";
        
        // Create with factory
        echo "\n[1.3] Factory Create:\n";
        $post3 = Post::factory()->create();
        echo "Factory created post #" . $post3->id . "\n";
        echo "Factory title: " . $post3->title . "\n";
        
        // Batch create
        echo "\n[1.4] Batch Create (multiple):\n";
        $posts = Post::factory(5)->create();
        echo "Created " . $posts->count() . " posts\n";
    }

    /**
     * 2. READ OPERATIONS DEBUGGING
     */
    public static function debugRead()
    {
        echo "\n=== READ OPERATIONS DEBUG ===\n";
        
        // All posts
        echo "\n[2.1] Get All Posts:\n";
        $allPosts = Post::all();
        echo "Total posts: " . $allPosts->count() . "\n";
        echo "Type returned: " . get_class($allPosts) . " (should be Collection)\n";
        
        // Find by ID
        echo "\n[2.2] Find by ID:\n";
        $post = Post::find(1);
        if ($post) {
            echo "Found post #" . $post->id . "\n";
            echo "Title: " . $post->title . "\n";
        } else {
            echo "Post not found\n";
        }
        
        // Find or fail
        echo "\n[2.3] Find or Fail:\n";
        try {
            $post = Post::findOrFail(1);
            echo "Found: " . $post->title . "\n";
        } catch (\Exception $e) {
            echo "Exception: " . $e->getMessage() . "\n";
        }
        
        // First
        echo "\n[2.4] First Record:\n";
        $first = Post::first();
        echo "First post: " . ($first ? $first->title : 'NO POSTS') . "\n";
        
        // Last
        echo "\n[2.5] Last Record (Debugging Issue #1):\n";
        $last1 = Post::latest()->first();
        $last2 = Post::latest('created_at')->first();
        $last3 = Post::orderBy('created_at', 'desc')->first();
        echo "latest()->first(): " . ($last1 ? $last1->id . ' - ' . $last1->title : 'NONE') . "\n";
        echo "latest('created_at')->first(): " . ($last2 ? $last2->id . ' - ' . $last2->title : 'NONE') . "\n";
        echo "orderBy(...desc)->first(): " . ($last3 ? $last3->id . ' - ' . $last3->title : 'NONE') . "\n";
    }

    /**
     * 3. WHERE CLAUSE DEBUGGING
     */
    public static function debugWhere()
    {
        echo "\n=== WHERE CLAUSE DEBUG ===\n";
        
        // Simple where
        echo "\n[3.1] Simple Where:\n";
        $posts = Post::where('title', 'like', '%Test%')->get();
        echo "Found " . $posts->count() . " posts with 'Test' in title\n";
        
        // Multiple where
        echo "\n[3.2] Multiple Where (AND):\n";
        $posts = Post::where('id', '>', 1)
            ->where('title', 'like', '%Test%')
            ->get();
        echo "Found " . $posts->count() . " posts\n";
        
        // Or where
        echo "\n[3.3] Or Where:\n";
        $posts = Post::where('title', 'like', '%Test%')
            ->orWhere('content', 'like', '%debug%')
            ->get();
        echo "Found " . $posts->count() . " posts matching either condition\n";
        
        // FirstWhere
        echo "\n[3.4] FirstWhere:\n";
        $post = Post::firstWhere('id', 1);
        echo "FirstWhere result: " . ($post ? $post->title : 'NULL') . "\n";
        
        // Comparison operators
        echo "\n[3.5] Comparison Operators:\n";
        echo "Operator tests:\n";
        echo "  > (greater than): " . Post::where('id', '>', 0)->count() . " posts\n";
        echo "  >= (greater or equal): " . Post::where('id', '>=', 1)->count() . " posts\n";
        echo "  < (less than): " . Post::where('id', '<', 1000)->count() . " posts\n";
        echo "  <> (not equal): " . Post::where('id', '<>', 0)->count() . " posts\n";
        echo "  in(): " . Post::whereIn('id', [1, 2, 3])->count() . " posts\n";
        echo "  between(): " . Post::whereBetween('id', [1, 5])->count() . " posts\n";
    }

    /**
     * 4. UPDATE OPERATIONS DEBUGGING
     */
    public static function debugUpdate()
    {
        echo "\n=== UPDATE OPERATIONS DEBUG ===\n";
        
        $post = Post::first();
        if (!$post) {
            echo "No posts to update\n";
            return;
        }
        
        echo "\n[4.1] Update Single Property:\n";
        $oldUpdatedAt = $post->updated_at;
        $post->update(['title' => 'Updated Title ' . time()]);
        echo "Old updated_at: " . $oldUpdatedAt . "\n";
        echo "New updated_at: " . $post->updated_at . "\n";
        echo "Did updated_at change: " . ($oldUpdatedAt != $post->updated_at ? 'YES' : 'NO') . "\n";
        
        echo "\n[4.2] Update Multiple Properties:\n";
        $oldCreatedAt = $post->created_at;
        $post->update([
            'title' => 'Batch Update',
            'content' => 'Batch updated content'
        ]);
        echo "created_at changed: " . ($oldCreatedAt != $post->created_at ? 'YES' : 'NO') . "\n";
        
        echo "\n[4.3] Direct Property Assignment + Save:\n";
        $post->title = 'Direct Assignment ' . time();
        $post->save();
        echo "Updated via direct assignment\n";
        
        echo "\n[4.4] Query Builder Update:\n";
        $count = Post::where('id', $post->id)->update([
            'title' => 'Query Builder Update'
        ]);
        echo "Records updated: " . $count . "\n";
        echo "Refresh and check: " . Post::find($post->id)->title . "\n";
    }

    /**
     * 5. DELETE OPERATIONS DEBUGGING
     */
    public static function debugDelete()
    {
        echo "\n=== DELETE OPERATIONS DEBUG ===\n";
        
        echo "\n[5.1] Delete Single Record:\n";
        $post = Post::factory()->create(['title' => 'To Delete 1']);
        $id = $post->id;
        $post->delete();
        echo "Deleted post #" . $id . "\n";
        echo "Finding deleted post: " . (Post::find($id) ? 'FOUND (ERROR)' : 'NOT FOUND (OK)') . "\n";
        
        echo "\n[5.2] Delete via Query:\n";
        $post2 = Post::factory()->create(['title' => 'To Delete 2']);
        $id2 = $post2->id;
        $deleted = Post::where('id', $id2)->delete();
        echo "Records deleted: " . $deleted . "\n";
        echo "Finding via query: " . (Post::where('id', $id2)->exists() ? 'FOUND (ERROR)' : 'NOT FOUND (OK)') . "\n";
        
        echo "\n[5.3] Destroy Multiple:\n";
        $post3 = Post::factory()->create(['title' => 'To Delete 3']);
        $post4 = Post::factory()->create(['title' => 'To Delete 4']);
        $ids = [$post3->id, $post4->id];
        $deleted = Post::destroy($ids);
        echo "Records destroyed: " . $deleted . "\n";
    }

    /**
     * 6. ORDERING DEBUGGING
     */
    public static function debugOrdering()
    {
        echo "\n=== ORDERING DEBUG ===\n";
        
        echo "\n[6.1] Order By ASC:\n";
        $posts = Post::orderBy('id', 'asc')->take(3)->get();
        foreach ($posts as $post) {
            echo "  ID: " . $post->id . "\n";
        }
        
        echo "\n[6.2] Order By DESC:\n";
        $posts = Post::orderBy('id', 'desc')->take(3)->get();
        foreach ($posts as $post) {
            echo "  ID: " . $post->id . "\n";
        }
        
        echo "\n[6.3] Multiple Order By:\n";
        $posts = Post::orderBy('id', 'desc')
            ->orderBy('title', 'asc')
            ->take(3)
            ->get();
        echo "Ordered by: id DESC, title ASC\n";
        
        echo "\n[6.4] Latest/Oldest:\n";
        $latest = Post::latest()->first();
        $oldest = Post::oldest()->first();
        echo "Latest: #" . ($latest ? $latest->id : 'NONE') . "\n";
        echo "Oldest: #" . ($oldest ? $oldest->id : 'NONE') . "\n";
    }

    /**
     * 7. PAGINATION DEBUGGING
     */
    public static function debugPagination()
    {
        echo "\n=== PAGINATION DEBUG ===\n";
        
        echo "\n[7.1] Basic Pagination:\n";
        $paginated = Post::paginate(5);
        echo "Total: " . $paginated->total() . "\n";
        echo "Per page: " . $paginated->perPage() . "\n";
        echo "Current page: " . $paginated->currentPage() . "\n";
        echo "Last page: " . $paginated->lastPage() . "\n";
        echo "Items on page: " . count($paginated->items()) . "\n";
        
        echo "\n[7.2] Get Pagination URLs:\n";
        echo "First page URL: " . $paginated->url(1) . "\n";
        echo "Last page URL: " . $paginated->url($paginated->lastPage()) . "\n";
        
        echo "\n[7.3] Has More Pages:\n";
        echo "Has more: " . ($paginated->hasMorePages() ? 'YES' : 'NO') . "\n";
    }

    /**
     * 8. AGGREGATES DEBUGGING
     */
    public static function debugAggregates()
    {
        echo "\n=== AGGREGATES DEBUG ===\n";
        
        echo "\n[8.1] Count:\n";
        $count = Post::count();
        echo "Total posts: " . $count . "\n";
        
        echo "\n[8.2] Count with Where:\n";
        $count = Post::where('title', 'like', '%Test%')->count();
        echo "Posts with 'Test': " . $count . "\n";
        
        echo "\n[8.3] Exists:\n";
        $exists = Post::where('id', 1)->exists();
        echo "Post #1 exists: " . ($exists ? 'YES' : 'NO') . "\n";
        
        echo "\n[8.4] Doesn't Exist:\n";
        $notExists = Post::where('id', 999999)->doesntExist();
        echo "Post #999999 doesn't exist: " . ($notExists ? 'YES' : 'NO') . "\n";
    }

    /**
     * 9. IMAGE ATTRIBUTE DEBUGGING
     */
    public static function debugImageAttribute()
    {
        echo "\n=== IMAGE ATTRIBUTE DEBUG ===\n";
        
        $post = Post::first();
        if (!$post) {
            echo "No posts to test\n";
            return;
        }
        
        echo "\n[9.1] Raw Attribute vs Accessor:\n";
        echo "Raw (getOriginal): " . $post->getOriginal('image') . "\n";
        echo "Via Accessor: " . $post->image . "\n";
        echo "Are they different: " . ($post->getOriginal('image') != $post->image ? 'YES' : 'NO') . "\n";
        
        echo "\n[9.2] Storage URL Format:\n";
        $storage = $post->getOriginal('image');
        $url = $post->image;
        echo "Filename: " . $storage . "\n";
        echo "Full URL: " . $url . "\n";
        echo "Contains /storage/posts/: " . (strpos($url, '/storage/posts/') !== false ? 'YES' : 'NO') . "\n";
        
        echo "\n[9.3] Attribute in Serialization:\n";
        $array = $post->toArray();
        echo "In toArray: " . $array['image'] . "\n";
        echo "Same as accessor: " . ($array['image'] == $post->image ? 'YES' : 'NO') . "\n";
    }

    /**
     * 10. LIMIT AND SKIP DEBUGGING
     */
    public static function debugLimitSkip()
    {
        echo "\n=== LIMIT AND SKIP DEBUG ===\n";
        
        echo "\n[10.1] Limit:\n";
        $posts = Post::limit(3)->get();
        echo "Limited to 3, got: " . $posts->count() . "\n";
        
        echo "\n[10.2] Skip:\n";
        $posts = Post::skip(2)->get();
        echo "Skipped 2, got: " . $posts->count() . "\n";
        
        echo "\n[10.3] Skip + Limit:\n";
        $posts = Post::skip(2)->limit(3)->get();
        echo "Skipped 2, limited 3, got: " . $posts->count() . "\n";
        
        echo "\n[10.4] Take (alias for limit):\n";
        $posts = Post::take(5)->get();
        echo "Took 5, got: " . $posts->count() . "\n";
    }

    /**
     * 11. MASS ASSIGNMENT DEBUGGING
     */
    public static function debugMassAssignment()
    {
        echo "\n=== MASS ASSIGNMENT DEBUG ===\n";
        
        echo "\n[11.1] Fillable Properties:\n";
        $post = new Post();
        $fillable = $post->getFillable();
        echo "Fillable: " . json_encode($fillable) . "\n";
        
        echo "\n[11.2] Can Assign Fillable:\n";
        try {
            $post = Post::make([
                'image' => 'test.jpg',
                'title' => 'Test',
                'content' => 'Content'
            ]);
            echo "✓ Can assign all fillable properties\n";
        } catch (\Exception $e) {
            echo "✗ Error: " . $e->getMessage() . "\n";
        }
        
        echo "\n[11.3] Cannot Assign Non-Fillable:\n";
        try {
            $post = Post::make([
                'image' => 'test.jpg',
                'title' => 'Test',
                'content' => 'Content',
                'id' => 999, // Not fillable
                'created_at' => now() // Not fillable
            ]);
            echo "Created (id and created_at ignored)\n";
            echo "ID is null: " . ($post->id === null ? 'YES' : 'NO') . "\n";
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }

    /**
     * 12. DATABASE QUERY LOGGING
     */
    public static function debugQueryLog()
    {
        echo "\n=== QUERY LOG DEBUG ===\n";
        
        DB::enableQueryLog();
        
        $posts = Post::where('id', '>', 0)->take(5)->get();
        
        $queries = DB::getQueryLog();
        
        echo "\n[12.1] Queries Executed:\n";
        foreach ($queries as $index => $query) {
            echo "Query " . ($index + 1) . ":\n";
            echo "  SQL: " . $query['query'] . "\n";
            echo "  Bindings: " . json_encode($query['bindings']) . "\n";
            echo "  Time: " . $query['time'] . "ms\n";
        }
        
        DB::disableQueryLog();
    }

    /**
     * Run all debugs
     */
    public static function runAll()
    {
        echo "\n╔════════════════════════════════════════════════════╗\n";
        echo "║     POST MODEL COMPREHENSIVE DEBUGGING SESSION     ║\n";
        echo "╚════════════════════════════════════════════════════╝\n";
        
        static::debugCreate();
        static::debugRead();
        static::debugWhere();
        static::debugUpdate();
        static::debugDelete();
        static::debugOrdering();
        static::debugPagination();
        static::debugAggregates();
        static::debugImageAttribute();
        static::debugLimitSkip();
        static::debugMassAssignment();
        static::debugQueryLog();
        
        echo "\n╔════════════════════════════════════════════════════╗\n";
        echo "║              DEBUGGING SESSION COMPLETE             ║\n";
        echo "╚════════════════════════════════════════════════════╝\n";
    }
}

/**
 * USAGE:
 * 
 * php artisan tinker
 * >>> \App\Debugging\PostModelDebugExamples::debugCreate();
 * >>> \App\Debugging\PostModelDebugExamples::debugRead();
 * >>> \App\Debugging\PostModelDebugExamples::runAll();
 */
