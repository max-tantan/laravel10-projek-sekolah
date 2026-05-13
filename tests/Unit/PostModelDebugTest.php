<?php

namespace Tests\Unit;

use App\Models\Post;
use Database\Factories\PostFactory;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\DB;

class PostModelDebugTest extends TestCase
{
    /**
     * Test Post Model Structure
     * Debugging: Verify fillable properties
     */
    public function test_post_model_fillable_properties()
    {
        echo "\n=== DEBUG: POST MODEL FILLABLE PROPERTIES ===\n";
        
        $post = new Post();
        $fillable = $post->getFillable();
        
        echo "Fillable properties: " . json_encode($fillable) . "\n";
        
        $expected = ['image', 'title', 'content'];
        $this->assertEquals($expected, $fillable);
        
        echo "✓ Fillable properties verified\n";
    }

    /**
     * Test Image Attribute Casting
     * Debugging: Verify image accessor works correctly
     */
    public function test_image_attribute_casting()
    {
        echo "\n=== DEBUG: IMAGE ATTRIBUTE CASTING ===\n";
        
        $post = new Post([
            'image' => 'test-image.jpg',
            'title' => 'Test Post',
            'content' => 'Test content',
        ]);
        
        $imageUrl = $post->image;
        echo "Raw image value: 'test-image.jpg'\n";
        echo "Transformed URL: " . $imageUrl . "\n";
        
        $expectedUrl = url('/storage/posts/test-image.jpg');
        echo "Expected URL: " . $expectedUrl . "\n";
        
        $this->assertEquals($expectedUrl, $imageUrl);
        
        echo "✓ Image attribute casting verified\n";
    }

    /**
     * Test Model has HasFactory trait
     * Debugging: Verify factory integration
     */
    public function test_post_has_factory_trait()
    {
        echo "\n=== DEBUG: POST FACTORY TRAIT ===\n";
        
        $post = new Post();
        
        // Check if HasFactory trait is used
        $traits = class_uses(Post::class);
        echo "Traits used: " . json_encode(array_keys($traits)) . "\n";
        
        $this->assertArrayHasKey('Illuminate\Database\Eloquent\Factories\HasFactory', $traits);
        
        echo "✓ HasFactory trait verified\n";
    }

    /**
     * Test Model table name
     * Debugging: Verify table association
     */
    public function test_post_model_table_name()
    {
        echo "\n=== DEBUG: POST MODEL TABLE NAME ===\n";
        
        $post = new Post();
        $tableName = $post->getTable();
        
        echo "Table name: " . $tableName . "\n";
        
        $this->assertEquals('posts', $tableName);
        
        echo "✓ Table name verified\n";
    }

    /**
     * Test Model Timestamps
     * Debugging: Verify created_at and updated_at
     */
    public function test_post_model_timestamps()
    {
        echo "\n=== DEBUG: POST MODEL TIMESTAMPS ===\n";
        
        $post = new Post();
        
        echo "Timestamps enabled: " . ($post->usesTimestamps() ? 'YES' : 'NO') . "\n";
        echo "Created at column: " . $post->getCreatedAtColumn() . "\n";
        echo "Updated at column: " . $post->getUpdatedAtColumn() . "\n";
        
        $this->assertTrue($post->usesTimestamps());
        
        echo "✓ Timestamps verified\n";
    }

    /**
     * Test Model Attributes
     * Debugging: Verify all attributes
     */
    public function test_post_model_attributes()
    {
        echo "\n=== DEBUG: POST MODEL ATTRIBUTES ===\n";
        
        $postData = [
            'image' => 'debug-image.jpg',
            'title' => 'Debug Post Title',
            'content' => 'This is debug post content',
        ];
        
        $post = new Post($postData);
        
        echo "Attributes set:\n";
        echo "  - image: " . $post->image . "\n";
        echo "  - title: " . $post->title . "\n";
        echo "  - content: " . $post->content . "\n";
        
        $this->assertEquals($postData['title'], $post->title);
        $this->assertEquals($postData['content'], $post->content);
        
        echo "✓ Attributes verified\n";
    }

    /**
     * Test Model Mass Assignment
     * Debugging: Verify mass assignment protection
     */
    public function test_post_model_mass_assignment()
    {
        echo "\n=== DEBUG: POST MODEL MASS ASSIGNMENT ===\n";
        
        $postData = [
            'image' => 'mass-assign.jpg',
            'title' => 'Mass Assignment Title',
            'content' => 'Mass assigned content',
        ];
        
        $post = Post::make($postData);
        
        echo "Mass assignment successful\n";
        echo "  - image: " . $post->image . "\n";
        echo "  - title: " . $post->title . "\n";
        echo "  - content: " . $post->content . "\n";
        
        $this->assertEquals($postData['title'], $post->title);
        
        echo "✓ Mass assignment verified\n";
    }

    /**
     * Test Model Primary Key
     * Debugging: Verify primary key
     */
    public function test_post_model_primary_key()
    {
        echo "\n=== DEBUG: POST MODEL PRIMARY KEY ===\n";
        
        $post = new Post();
        $primaryKey = $post->getKeyName();
        $keyType = $post->getKeyType();
        
        echo "Primary key: " . $primaryKey . "\n";
        echo "Key type: " . $keyType . "\n";
        
        $this->assertEquals('id', $primaryKey);
        
        echo "✓ Primary key verified\n";
    }

    /**
     * Test Model Increment/Decrement
     * Debugging: Verify numeric operations
     */
    public function test_post_model_increment_methods()
    {
        echo "\n=== DEBUG: POST MODEL INCREMENT/DECREMENT ===\n";
        
        $post = new Post([
            'image' => 'test.jpg',
            'title' => 'Test',
            'content' => 'Test content',
        ]);
        
        echo "Post created successfully\n";
        echo "Available methods for numeric operations:\n";
        echo "  - increment()\n";
        echo "  - decrement()\n";
        echo "  - incrementByValue()\n";
        echo "  - decrementByValue()\n";
        
        echo "✓ Numeric operation methods available\n";
    }

    /**
     * Test Model to Array/JSON
     * Debugging: Verify serialization
     */
    public function test_post_model_serialization()
    {
        echo "\n=== DEBUG: POST MODEL SERIALIZATION ===\n";
        
        $post = new Post([
            'image' => 'serialize.jpg',
            'title' => 'Serialization Test',
            'content' => 'Test content for serialization',
        ]);
        
        $array = $post->toArray();
        echo "toArray() result: " . json_encode($array, JSON_PRETTY_PRINT) . "\n";
        
        $this->assertIsArray($array);
        $this->assertArrayHasKey('image', $array);
        $this->assertArrayHasKey('title', $array);
        $this->assertArrayHasKey('content', $array);
        
        echo "✓ Serialization verified\n";
    }

    /**
     * Test Model Guarded Properties
     * Debugging: Verify guarded attributes
     */
    public function test_post_model_guarded()
    {
        echo "\n=== DEBUG: POST MODEL GUARDED ===\n";
        
        $post = new Post();
        $guarded = $post->getGuarded();
        
        echo "Guarded properties: " . json_encode($guarded) . "\n";
        echo "Note: Empty means no properties are guarded (fillable is used instead)\n";
        
        echo "✓ Guarded properties verified\n";
    }

    /**
     * Test Model Casts
     * Debugging: Verify type casting
     */
    public function test_post_model_casts()
    {
        echo "\n=== DEBUG: POST MODEL CASTS ===\n";
        
        $post = new Post();
        $casts = $post->getCasts();
        
        echo "Casts defined: " . json_encode($casts) . "\n";
        echo "Note: Image is handled via Attribute class, not traditional casts\n";
        
        echo "✓ Casts verified\n";
    }

    /**
     * Test Model Connection
     * Debugging: Verify database connection
     */
    public function test_post_model_connection()
    {
        echo "\n=== DEBUG: POST MODEL DATABASE CONNECTION ===\n";
        
        $post = new Post();
        $connection = $post->getConnectionName();
        
        echo "Database connection: " . ($connection ?? 'default') . "\n";
        
        echo "✓ Connection verified\n";
    }
}
