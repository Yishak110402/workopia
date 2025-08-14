# Laravel Database Management: Complete Guide

## Table of Contents

1. [Database Configuration](#database-configuration)
2. [Database Connections](#database-connections)
3. [Migrations](#migrations)
4. [Seeders](#seeders)
5. [Factories](#factories)
6. [Models and Eloquent](#models-and-eloquent)
7. [Database Commands](#database-commands)
8. [Best Practices](#best-practices)
9. [Real-World Example: Workopia](#real-world-example-workopia)

## Database Configuration

### Environment Setup

Laravel uses environment variables for database configuration. In your `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=workopia
DB_USERNAME=root
DB_PASSWORD=
```

### Supported Database Systems

Laravel supports multiple database systems:

#### 1. MySQL/MariaDB

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=workopia
DB_USERNAME=root
DB_PASSWORD=your_password
```

#### 2. PostgreSQL

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=workopia
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

#### 3. SQLite

```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

#### 4. SQL Server

```env
DB_CONNECTION=sqlsrv
DB_HOST=localhost
DB_PORT=1433
DB_DATABASE=workopia
DB_USERNAME=sa
DB_PASSWORD=your_password
```

### Configuration File

The main database configuration is in `config/database.php`:

```php
'default' => env('DB_CONNECTION', 'sqlite'),

'connections' => [
    'mysql' => [
        'driver' => 'mysql',
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'laravel'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
        'charset' => env('DB_CHARSET', 'utf8mb4'),
        'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
        'prefix' => '',
        'strict' => true,
        'engine' => null,
    ],
    // Other connections...
],
```

## Database Connections

### Multiple Database Connections

You can configure multiple database connections:

```php
// config/database.php
'connections' => [
    'mysql' => [
        'driver' => 'mysql',
        'host' => env('DB_HOST', '127.0.0.1'),
        'database' => env('DB_DATABASE', 'laravel'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
    ],
    'mysql_read' => [
        'driver' => 'mysql',
        'host' => env('DB_HOST_READ', '127.0.0.1'),
        'database' => env('DB_DATABASE', 'laravel'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
        'readonly' => true,
    ],
],
```

### Using Different Connections

```php
// In your model
class User extends Model
{
    protected $connection = 'mysql_read';
}

// In your controller
DB::connection('mysql_read')->table('users')->get();

// Using the default connection
DB::table('users')->get();
```

## Migrations

Migrations are like version control for your database schema. They allow you to define and modify your database structure using PHP code.

### Creating Migrations

#### Method 1: Using Artisan Command

```bash
# Create a basic migration
php artisan make:migration create_users_table

# Create migration for a specific table
php artisan make:migration create_job_listings_table

# Create migration to add columns to existing table
php artisan make:migration add_fields_to_job_listings_table --table=job_listings
```

#### Method 2: Migration with Model

```bash
# Creates both model and migration
php artisan make:model Job -m
```

### Migration Structure

A typical migration file looks like this:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('table_name', function (Blueprint $table) {
            // Column definitions
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_name');
    }
};
```

### Column Types

#### Basic Column Types

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();                    // Auto-incrementing primary key
    $table->string('name');          // VARCHAR(255)
    $table->string('email', 100);    // VARCHAR(100)
    $table->text('description');     // TEXT
    $table->longText('content');     // LONGTEXT
    $table->integer('age');          // INT
    $table->bigInteger('count');     // BIGINT
    $table->decimal('price', 8, 2);  // DECIMAL(8,2)
    $table->float('rating', 3, 2);   // FLOAT(3,2)
    $table->boolean('is_active');    // BOOLEAN/TINYINT
    $table->date('birth_date');      // DATE
    $table->dateTime('created_at');  // DATETIME
    $table->timestamp('updated_at'); // TIMESTAMP
    $table->json('metadata');        // JSON
    $table->binary('file_data');     // BLOB
});
```

#### Advanced Column Types

```php
Schema::create('job_listings', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->longText('description');
    $table->integer('salary');
    $table->string('tags')->nullable();
    $table->enum('job_type', [
        'Full-Time',
        'Part-Time',
        'Contract',
        'Temporary',
        'Internship',
        'Volunteer',
        'On-Call'
    ])->default('Full-Time');
    $table->boolean('remote')->default(false);
    $table->string('requirements')->nullable();
    $table->string('benefits')->nullable();
    $table->string('address')->nullable();
    $table->string('city');
    $table->string('contact_email');
    $table->string('contact_phone')->nullable();
    $table->string('company_name');
    $table->string('company_description')->nullable();
    $table->string('company_logo')->nullable();
    $table->string('company_website')->nullable();
    $table->timestamps();
});
```

### Column Modifiers

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('email')->unique();           // Unique constraint
    $table->string('name')->nullable();          // Allow NULL values
    $table->string('password')->default('');     // Default value
    $table->integer('age')->unsigned();          // Unsigned integer
    $table->string('code')->index();             // Add index
    $table->string('slug')->unique()->index();   // Unique and indexed
    $table->text('description')->nullable();     // Nullable text
    $table->timestamp('email_verified_at')->nullable();
    $table->rememberToken();                     // remember_token column
    $table->timestamps();                        // created_at and updated_at
});
```

### Foreign Keys

```php
Schema::create('job_listings', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id');       // Foreign key column
    $table->string('title');
    $table->longText('description');

    // Define foreign key constraint
    $table->foreign('user_id')
          ->references('id')
          ->on('users')
          ->onDelete('cascade');                  // Cascade delete

    $table->timestamps();
});
```

### Indexes

```php
Schema::create('job_listings', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('city');
    $table->boolean('remote');
    $table->unsignedBigInteger('user_id');

    // Single column index
    $table->index('city');

    // Composite index
    $table->index(['city', 'remote']);

    // Unique index
    $table->unique('title');

    // Foreign key (automatically creates index)
    $table->foreign('user_id')->references('id')->on('users');

    $table->timestamps();
});
```

### Modifying Tables

#### Adding Columns

```php
Schema::table('job_listings', function (Blueprint $table) {
    $table->string('new_column')->after('title');
    $table->integer('salary')->nullable();
    $table->boolean('is_featured')->default(false);
});
```

#### Modifying Columns

```php
Schema::table('users', function (Blueprint $table) {
    $table->string('name', 100)->change();  // Change column type/length
    $table->string('email')->nullable()->change();
});
```

#### Dropping Columns

```php
Schema::table('job_listings', function (Blueprint $table) {
    $table->dropColumn(['old_column', 'unused_column']);
});
```

### Running Migrations

```bash
# Run all pending migrations
php artisan migrate

# Run migrations with output
php artisan migrate --verbose

# Rollback last batch of migrations
php artisan migrate:rollback

# Rollback specific number of steps
php artisan migrate:rollback --step=2

# Reset all migrations and run them again
php artisan migrate:refresh

# Reset and seed the database
php artisan migrate:refresh --seed

# Check migration status
php artisan migrate:status

# Create a new migration to reset database
php artisan migrate:fresh

# Fresh migration with seeding
php artisan migrate:fresh --seed
```

## Seeders

Seeders allow you to populate your database with test data.

### Creating Seeders

```bash
# Create a basic seeder
php artisan make:seeder UserSeeder

# Create seeder for a specific model
php artisan make:seeder JobSeeder
```

### Basic Seeder Structure

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeding logic here
    }
}
```

### Seeding Methods

#### Method 1: Direct Model Creation

```php
public function run(): void
{
    User::create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]);
}
```

#### Method 2: Using Factories

```php
public function run(): void
{
    // Create single user
    User::factory()->create([
        'email' => 'admin@example.com',
    ]);

    // Create multiple users
    User::factory(10)->create();
}
```

#### Method 3: Using Data Arrays

```php
public function run(): void
{
    $users = [
        [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
        ],
        [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
        ],
    ];

    foreach ($users as $user) {
        User::create($user);
    }
}
```

### Database Seeder

The main seeder that calls other seeders:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Temporarily disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate tables
        DB::table('job_listings')->truncate();
        DB::table('users')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Call other seeders
        $this->call([
            TestUserSeeder::class,
            RandomUserSeeder::class,
            JobSeeder::class,
        ]);
    }
}
```

### Running Seeders

```bash
# Run all seeders
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=UserSeeder

# Run fresh migrations and seed
php artisan migrate:fresh --seed
```

## Factories

Factories allow you to generate fake data for your models.

### Creating Factories

```bash
# Create factory for a model
php artisan make:factory UserFactory

# Create factory with model
php artisan make:factory UserFactory --model=User
```

### Basic Factory Structure

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
```

### Advanced Factory Example

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->jobTitle(),
            'description' => fake()->paragraphs(2, true),
            'salary' => fake()->numberBetween(40000, 120000),
            'tags' => implode(", ", fake()->words(3)),
            'job_type' => fake()->randomElement([
                'Full-Time',
                'Part-Time',
                'Contract',
                'Temporary',
                'Internship',
                'Volunteer',
                'On-Call'
            ]),
            'remote' => fake()->boolean(),
            'requirements' => fake()->sentence(15),
            'benefits' => fake()->sentence(15),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'contact_email' => fake()->safeEmail(),
            'contact_phone' => fake()->phoneNumber(),
            'company_name' => fake()->company(),
            'company_description' => fake()->sentence(20),
            'company_logo' => fake()->imageUrl(100, 100, 'business', true, 'logo'),
            'company_website' => fake()->url()
        ];
    }
}
```

### Using Factories

```php
// In your seeder
public function run(): void
{
    // Create single instance
    User::factory()->create();

    // Create multiple instances
    User::factory(10)->create();

    // Create with specific attributes
    User::factory()->create([
        'email' => 'admin@example.com',
    ]);

    // Create unverified user
    User::factory()->unverified()->create();

    // Create job with user
    Job::factory()->create([
        'user_id' => User::factory()->create()->id,
    ]);
}
```

## Models and Eloquent

### Basic Model Structure

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Job extends Model
{
    use HasFactory;

    protected $table = 'job_listings';

    protected $fillable = [
        'title',
        'description',
        'salary',
        'tags',
        'job_type',
        'remote',
        'requirements',
        'benefits',
        'address',
        'city',
        'contact_email',
        'contact_phone',
        'company_name',
        'company_description',
        'company_logo',
        'company_website',
        'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
```

### Model Relationships

#### One-to-Many

```php
// User model
public function jobListings(): HasMany
{
    return $this->hasMany(Job::class);
}

// Job model
public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}
```

#### Many-to-Many

```php
// User model
public function savedJobs(): BelongsToMany
{
    return $this->belongsToMany(Job::class, 'saved_jobs');
}

// Job model
public function savedByUsers(): BelongsToMany
{
    return $this->belongsToMany(User::class, 'saved_jobs');
}
```

### Model Attributes

```php
class Job extends Model
{
    protected $fillable = [
        'title', 'description', 'salary'
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    protected $casts = [
        'remote' => 'boolean',
        'salary' => 'integer',
        'created_at' => 'datetime',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'expires_at'
    ];
}
```

## Database Commands

### Artisan Commands

```bash
# Migration commands
php artisan migrate                    # Run migrations
php artisan migrate:rollback          # Rollback last batch
php artisan migrate:refresh           # Refresh migrations
php artisan migrate:fresh             # Fresh migrations
php artisan migrate:status            # Check status
php artisan migrate:reset             # Reset all migrations

# Seeder commands
php artisan db:seed                   # Run seeders
php artisan db:seed --class=UserSeeder # Run specific seeder

# Combined commands
php artisan migrate:fresh --seed      # Fresh + seed
php artisan migrate:refresh --seed    # Refresh + seed

# Database commands
php artisan db:show                   # Show database info
php artisan db:table table_name       # Show table structure
php artisan db:wipe                   # Drop all tables
```

### Tinker Commands

```bash
# Start tinker
php artisan tinker

# In tinker
User::all();                          # Get all users
User::find(1);                        # Find user by ID
User::where('email', 'test@example.com')->first();
Job::with('user')->get();             # Get jobs with user relationship
User::factory(5)->create();           # Create 5 users
```

## Best Practices

### 1. Migration Best Practices

```php
// Good: Descriptive migration names
php artisan make:migration create_job_listings_table
php artisan make:migration add_salary_to_job_listings_table

// Good: Use proper column types
$table->string('email')->unique();    // Instead of text for emails
$table->decimal('price', 8, 2);       // Instead of float for money
$table->boolean('is_active');         // Instead of tinyint

// Good: Always include down() method
public function down(): void
{
    Schema::dropIfExists('job_listings');
}
```

### 2. Seeder Best Practices

```php
// Good: Use factories for test data
public function run(): void
{
    User::factory(10)->create();
}

// Good: Create specific test data
public function run(): void
{
    User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => Hash::make('password'),
    ]);
}

// Good: Handle foreign key constraints
public function run(): void
{
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    DB::table('users')->truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
}
```

### 3. Model Best Practices

```php
// Good: Use fillable for mass assignment protection
protected $fillable = [
    'title', 'description', 'salary'
];

// Good: Use casts for data type conversion
protected $casts = [
    'remote' => 'boolean',
    'salary' => 'integer',
];

// Good: Define relationships
public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}
```

### 4. Database Design Best Practices

```php
// Good: Use proper foreign key constraints
$table->foreign('user_id')
      ->references('id')
      ->on('users')
      ->onDelete('cascade');

// Good: Add indexes for frequently queried columns
$table->index(['city', 'remote']);
$table->index('created_at');

// Good: Use enums for fixed values
$table->enum('job_type', [
    'Full-Time',
    'Part-Time',
    'Contract'
])->default('Full-Time');
```

## Real-World Example: Workopia

Based on the Workopia job board application, here's how database management is implemented:

### Database Structure

#### Users Table

```php
// Migration: 0001_01_01_000000_create_users_table.php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->rememberToken();
    $table->timestamps();
});
```

#### Job Listings Table

```php
// Migration: 2025_08_09_105939_create_job_listings_table.php
Schema::create('job_listings', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->longText('description');
    $table->timestamps();
});

// Migration: 2025_08_09_174758_add_fields_to_job_listings.php
Schema::table('job_listings', function (Blueprint $table) {
    $table->unsignedBigInteger('user_id')->after('id');
    $table->integer('salary');
    $table->string('tags')->nullable();
    $table->enum('job_type', [
        'Full-Time', 'Part-Time', 'Contract', 'Temporary',
        'Internship', 'Volunteer', 'On-Call'
    ])->default('Full-Time');
    $table->boolean('remote')->default(false);
    $table->string('requirements')->nullable();
    $table->string('benefits')->nullable();
    $table->string('address')->nullable();
    $table->string('city');
    $table->string('contact_email');
    $table->string('contact_phone')->nullable();
    $table->string('company_name');
    $table->string('company_description')->nullable();
    $table->string('company_logo')->nullable();
    $table->string('company_website')->nullable();

    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
});
```

### Models

#### User Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function jobListings(): HasMany
    {
        return $this->hasMany(Job::class);
    }
}
```

#### Job Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Job extends Model
{
    use HasFactory;

    protected $table = 'job_listings';

    protected $fillable = [
        'title',
        'description',
        'salary',
        'tags',
        'job_type',
        'remote',
        'requirements',
        'benefits',
        'address',
        'city',
        'contact_email',
        'contact_phone',
        'company_name',
        'company_description',
        'company_logo',
        'company_website',
        'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
```

### Seeders

#### Database Seeder

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Temporarily disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate tables
        DB::table('job_listings')->truncate();
        DB::table('users')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Call other seeders
        $this->call([
            TestUserSeeder::class,
            RandomUserSeeder::class,
            JobSeeder::class,
        ]);
    }
}
```

#### Test User Seeder

```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Yohannes Hailu',
            'email' => 'yohannes@gmail.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('12345678')
        ]);
    }
}
```

#### Job Seeder

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        // Load job listings from file
        $jobListings = include database_path('seeders/data/job_listings.php');

        // Get test user ID
        $testUser = User::where('email', 'yohannes@gmail.com')->value('id');

        // Get user IDs from the user model
        $userIds = User::where('email', '!=', 'yohannes@gmail.com')->pluck('id')->toArray();

        foreach ($jobListings as $index => &$listing) {
            if ($index < 2) {
                $listing['user_id'] = $testUser;
            } else {
                $listing['user_id'] = $userIds[array_rand($userIds)];
            }

            // Add timestamps
            $listing['created_at'] = now();
            $listing['updated_at'] = now();
        }

        DB::table('job_listings')->insert($jobListings);
        echo "Jobs created successfully";
    }
}
```

### Factories

#### User Factory

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
```

#### Job Factory

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class JobFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->jobTitle(),
            'description' => fake()->paragraphs(2, true),
            'salary' => fake()->numberBetween(40000, 120000),
            'tags' => implode(', ', fake()->words(3)),
            'job_type' => fake()->randomElement([
                'Full-Time', 'Part-Time', 'Contract', 'Temporary',
                'Internship', 'Volunteer', 'On-Call'
            ]),
            'remote' => fake()->boolean(),
            'requirements' => fake()->sentence(15),
            'benefits' => fake()->sentence(15),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'contact_email' => fake()->safeEmail(),
            'contact_phone' => fake()->phoneNumber(),
            'company_name' => fake()->company(),
            'company_description' => fake()->sentence(20),
            'company_logo' => fake()->imageUrl(100, 100, 'business', true, 'logo'),
            'company_website' => fake()->url()
        ];
    }
}
```

### Complete Workflow Example

Here's how to set up the Workopia database from scratch:

```bash
# 1. Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=workopia
DB_USERNAME=root
DB_PASSWORD=

# 2. Create database
mysql -u root -p -e "CREATE DATABASE workopia;"

# 3. Run migrations
php artisan migrate

# 4. Seed the database
php artisan db:seed

# 5. Verify data
php artisan tinker
>>> App\Models\User::count();
>>> App\Models\Job::count();
>>> App\Models\Job::with('user')->first();
```

### Advanced Database Operations

#### Using Query Builder

```php
// In your controller
public function index()
{
    $jobs = DB::table('job_listings')
        ->join('users', 'job_listings.user_id', '=', 'users.id')
        ->select('job_listings.*', 'users.name as author_name')
        ->where('job_listings.remote', true)
        ->orderBy('job_listings.created_at', 'desc')
        ->get();

    return view('jobs.index', compact('jobs'));
}
```

#### Using Eloquent

```php
// In your controller
public function index()
{
    $jobs = Job::with('user')
        ->where('remote', true)
        ->orderBy('created_at', 'desc')
        ->get();

    return view('jobs.index', compact('jobs'));
}
```

#### Database Transactions

```php
use Illuminate\Support\Facades\DB;

public function store(Request $request)
{
    return DB::transaction(function () use ($request) {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $job = Job::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'description' => $request->description,
            // ... other fields
        ]);

        return $job;
    });
}
```

## Conclusion

Laravel provides a comprehensive and powerful database management system that includes:

-   **Migrations**: Version control for your database schema
-   **Seeders**: Populate your database with test data
-   **Factories**: Generate fake data for testing
-   **Eloquent ORM**: Object-relational mapping for database operations
-   **Query Builder**: Fluent interface for database queries

The Workopia example demonstrates how these components work together to create a robust database structure for a job board application. By following Laravel's conventions and best practices, you can build scalable and maintainable database-driven applications.

Key takeaways:

-   Always use migrations for database schema changes
-   Use seeders to populate your database with realistic data
-   Leverage factories for generating test data
-   Follow naming conventions and best practices
-   Use relationships to maintain data integrity
-   Test your database operations thoroughly
