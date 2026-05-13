<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostModelFeatureDebugTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Creating Post
     * Debugging: Verify create method
     */
    public function test_create_post_debug()
    {
        echo "\n=== DEBUG: CREATE POST ===\n";
        
        $postData = [
            'image' => 'feature-test.jpg',
            'title' => 'Feature Test Post',
            'content' => 'This is a feature test post',
        ];
        
        $post = Post::create($postData);
        
        echo "Post created successfully\n";
        echo "  - ID: " . $post->id . "\n";
        echo "  - Title: " . $post->title . "\n";
        echo "  - Image URL: " . $post->image . "\n";
        echo "  - Created at: " . $post->created_at . "\n";
        echo "  - Updated at: " . $post->updated_at . "\n";
        
        $this->assertNotNull($post->id);
        $this->assertDatabaseHas('posts', ['title' => 'Feature Test Post']);
        
        echo "✓ Post creation verified\n";
    }

    /**
     * Test Updating Post
     * Debugging: Verify update method
     */
    public function test_update_post_debug()
    {
        echo "\n=== DEBUG: UPDATE POST ===\n";
        
        $post = Post::create([
            'image' => 'original.jpg',
            'title' => 'Original Title',
            'content' => 'Original content',
        ]);
        
        echo "Original post:\n";
        echo "  - Title: " . $post->title . "\n";
        echo "  - Content: " . $post->content . "\n";
        
        $post->update([
            'title' => 'Updated Title',
            'content' => 'Updated content',
        ]);
        
        echo "Updated post:\n";
        echo "  - Title: " . $post->title . "\n";
        echo "  - Content: " . $post->content . "\n";
        echo "  - Updated at: " . $post->updated_at . "\n";
        
        $this->assertDatabaseHas('posts', ['title' => 'Updated Title']);
        
        echo "✓ Post update verified\n";
    }

    /**
     * Test Deleting Post
     * Debugging: Verify delete method
     */
    public function test_delete_post_debug()
    {
        echo "\n=== DEBUG: DELETE POST ===\n";
        
        $post = Post::create([
            'image' => 'to-delete.jpg',
            'title' => 'To Delete',
            'content' => 'This will be deleted',
        ]);
        
        echo "Post created with ID: " . $post->id . "\n";
        
        $id = $post->id;
        $post->delete();
        
        echo "Post deleted\n";
        
        $this->assertDatabaseMissing('posts', ['id' => $id]);
        
        echo "✓ Post deletion verified\n";
    }

    /**
     * Test Finding Post
     * Debugging: Verify find methods
     */
    public function test_find_post_debug()
    {
        echo "\n=== DEBUG: FIND POST ===\n";
        
        $post = Post::create([
            'image' => 'find-test.jpg',
            'title' => 'Find Test Post',
            'content' => 'Testing find method',
        ]);
        
        $foundPost = Post::find($post->id);
        
        echo "Original post ID: " . $post->id . "\n";
        echo "Found post ID: " . $foundPost->id . "\n";
        echo "Found post title: " . $foundPost->title . "\n";
        
        $this->assertEquals($post->id, $foundPost->id);
        
        echo "✓ Find method verified\n";
    }

    /**
     * Test Finding Post by Column
     * Debugging: Verify findBy methods
     */
    public function test_find_by_column_debug()
    {
        echo "\n=== DEBUG: FIND BY COLUMN ===\n";
        
        $post = Post::create([
            'image' => 'findby-test.jpg',
            'title' => 'FindBy Test Post',
            'content' => 'Testing findBy method',
        ]);
        
        $foundPost = Post::firstWhere('title', 'FindBy Test Post');
        
        echo "Finding by title: 'FindBy Test Post'\n";
        echo "Found post ID: " . $foundPost->id . "\n";
        echo "Found post title: " . $foundPost->title . "\n";
        
        $this->assertEquals($post->id, $foundPost->id);
        
        echo "✓ FindBy method verified\n";
    }

    /**
     * Test Getting All Posts
     * Debugging: Verify get/all methods
     */
    public function test_get_all_posts_debug()
    {
        echo "\n=== DEBUG: GET ALL POSTS ===\n";
        
        Post::create([
            'image' => 'post1.jpg',
            'title' => 'Post 1',
            'content' => 'Content 1',
        ]);
        
        Post::create([
            'image' => 'post2.jpg',
            'title' => 'Post 2',
            'content' => 'Content 2',
        ]);
        
        Post::create([
            'image' => 'post3.jpg',
            'title' => 'Post 3',
            'content' => 'Content 3',
        ]);
        
        $posts = Post::all();
        
        echo "Total posts: " . $posts->count() . "\n";
        foreach ($posts as $post) {
            echo "  - ID: " . $post->id . ", Title: " . $post->title . "\n";
        }
        
        $this->assertCount(3, $posts);
        
        echo "✓ Get all posts verified\n";
    }

    /**
     * Test Querying Posts
     * Debugging: Verify query builder
     */
    public function test_query_posts_debug()
    {
        echo "\n=== DEBUG: QUERY POSTS ===\n";
        
        Post::create(['image' => 'a.jpg', 'title' => 'Post A', 'content' => 'Content A']);
        Post::create(['image' => 'b.jpg', 'title' => 'Post B', 'content' => 'Content B']);
        Post::create(['image' => 'c.jpg', 'title' => 'Post C', 'content' => 'Content C']);
        
        $query = Post::where('title', 'like', '%B%');
        $posts = $query->get();
        
        echo "Query: WHERE title LIKE '%B%'\n";
        echo "Results: " . $posts->count() . " post(s)\n";
        foreach ($posts as $post) {
            echo "  - " . $post->title . "\n";
        }
        
        $this->assertCount(1, $posts);
        
        echo "✓ Query verified\n";
    }

    /**
     * Test Paginating Posts
     * Debugging: Verify pagination
     */
    public function test_paginate_posts_debug()
    {
        echo "\n=== DEBUG: PAGINATE POSTS ===\n";
        
        for ($i = 1; $i <= 15; $i++) {
            Post::create([
                'image' => "post-$i.jpg",
                'title' => "Post $i",
                'content' => "Content $i",
            ]);
        }
        
        $paginated = Post::paginate(5);
        
        echo "Total posts: " . $paginated->total() . "\n";
        echo "Current page: " . $paginated->currentPage() . "\n";
        echo "Per page: " . $paginated->perPage() . "\n";
        echo "Total pages: " . $paginated->lastPage() . "\n";
        echo "Posts on this page: " . count($paginated->items()) . "\n";
        
        $this->assertEquals(15, $paginated->total());
        $this->assertEquals(5, count($paginated->items()));
        
        echo "✓ Pagination verified\n";
    }

    /**
     * Test Attribute Accessor in Database
     * Debugging: Verify image attribute with database data
     */
    public function test_attribute_accessor_with_database_debug()
    {
        echo "\n=== DEBUG: ATTRIBUTE ACCESSOR WITH DATABASE ===\n";
        
        $post = Post::create([
            'image' => 'database-image.jpg',
            'title' => 'Attribute Test',
            'content' => 'Testing attribute accessor',
        ]);
        
        $retrievedPost = Post::find($post->id);
        
        echo "Stored image filename: database-image.jpg\n";
        echo "Accessor transforms to URL: " . $retrievedPost->image . "\n";
        
        $expectedUrl = url('/storage/posts/database-image.jpg');
        echo "Expected URL: " . $expectedUrl . "\n";
        
        $this->assertEquals($expectedUrl, $retrievedPost->image);
        
        echo "✓ Attribute accessor verified with database\n";
    }

    /**
     * Test Ordering Posts
     * Debugging: Verify orderBy
     */
    public function test_order_posts_debug()
    {
        echo "\n=== DEBUG: ORDER POSTS ===\n";
        
        Post::create(['image' => 'c.jpg', 'title' => 'Post C', 'content' => 'C']);
        Post::create(['image' => 'a.jpg', 'title' => 'Post A', 'content' => 'A']);
        Post::create(['image' => 'b.jpg', 'title' => 'Post B', 'content' => 'B']);
        
        $ascending = Post::orderBy('title', 'asc')->get();
        $descending = Post::orderBy('title', 'desc')->get();
        
        echo "Ascending order:\n";
        foreach ($ascending as $post) {
            echo "  - " . $post->title . "\n";
        }
        
        echo "Descending order:\n";
        foreach ($descending as $post) {
            echo "  - " . $post->title . "\n";
        }
        
        $this->assertEquals('Post A', $ascending->first()->title);
        $this->assertEquals('Post C', $descending->first()->title);
        
        echo "✓ Ordering verified\n";
    }

    /**
     * Test Counting Posts
     * Debugging: Verify count method
     */
    public function test_count_posts_debug()
    {
        echo "\n=== DEBUG: COUNT POSTS ===\n";
        
        Post::create(['image' => '1.jpg', 'title' => 'Title 1', 'content' => 'Content 1']);
        Post::create(['image' => '2.jpg', 'title' => 'Title 2', 'content' => 'Content 2']);
        Post::create(['image' => '3.jpg', 'title' => 'Title 3', 'content' => 'Content 3']);
        
        $count = Post::count();
        $whereCount = Post::where('title', 'like', '%1%')->count();
        
        echo "Total posts count: " . $count . "\n";
        echo "Posts with '1' in title: " . $whereCount . "\n";
        
        $this->assertEquals(3, $count);
        $this->assertEquals(1, $whereCount);
        
        echo "✓ Count method verified\n";
    }

    /**
     * Test First/Last Methods
     * Debugging: Verify first and last
     */
    public function test_first_last_debug()
    {
        echo "\n=== DEBUG: FIRST AND LAST ===\n";
        
        Post::create(['image' => '1.jpg', 'title' => 'First Post', 'content' => 'C1']);
        Post::create(['image' => '2.jpg', 'title' => 'Middle Post', 'content' => 'C2']);
        Post::create(['image' => '3.jpg', 'title' => 'Last Post', 'content' => 'C3']);
        
        $first = Post::first();
        $last = Post::latest()->first();
        
        echo "First post: " . $first->title . "\n";
        echo "Last post (by created_at): " . $last->title . "\n";
        
        $this->assertEquals('First Post', $first->title);
        $this->assertEquals('Last Post', $last->title);
        
        echo "✓ First/Last methods verified\n";
    }

    /**
     * Test Limit/Skip
     * Debugging: Verify limit and skip
     */
    public function test_limit_skip_debug()
    {
        echo "\n=== DEBUG: LIMIT AND SKIP ===\n";
        
        for ($i = 1; $i <= 10; $i++) {
            Post::create([
                'image' => "post-$i.jpg",
                'title' => "Post $i",
                'content' => "Content $i",
            ]);
        }
        
        $limited = Post::limit(3)->get();
        $skipped = Post::skip(5)->limit(3)->get();
        
        echo "Limit 3 results: " . $limited->count() . "\n";
        echo "Skip 5, Limit 3 results: " . $skipped->count() . "\n";
        echo "First item of skipped: " . $skipped->first()->title . "\n";
        
        $this->assertCount(3, $limited);
        $this->assertCount(3, $skipped);
        
        echo "✓ Limit/Skip verified\n";
    }
}
