# Laravel Policies: Complete Guide with Examples

## Table of Contents

1. [Introduction to Policies](#introduction-to-policies)
2. [Creating Policies](#creating-policies)
3. [Policy Methods](#policy-methods)
4. [Registering Policies](#registering-policies)
5. [Using Policies in Controllers](#using-policies-in-controllers)
6. [Using Policies in Blade Templates](#using-policies-in-blade-templates)
7. [Policy Authorization Responses](#policy-authorization-responses)
8. [Policy Filters](#policy-filters)
9. [Best Practices](#best-practices)
10. [Real-World Example from Workopia](#real-world-example-from-workopia)

## Introduction to Policies

Laravel policies are classes that organize authorization logic around a particular model or resource. They provide a clean, organized way to handle authorization logic for your application's resources.

### What are Policies?

-   **Policies** are classes that handle authorization logic for a specific model
-   They contain methods that determine what actions a user can perform on a model
-   Policies are automatically resolved by Laravel's service container
-   They provide a clean separation of authorization logic from your controllers

### When to Use Policies?

-   When you need to authorize actions on a specific model
-   When you have complex authorization logic
-   When you want to reuse authorization logic across multiple controllers
-   When you need to test authorization logic independently

## Creating Policies

### Method 1: Using Artisan Command (Recommended)

```bash
php artisan make:policy JobPolicy --model=Job
```

This command creates a policy with all the standard CRUD methods.

### Method 2: Manual Creation

Create a new file in `app/Policies/JobPolicy.php`:

```php
<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class JobPolicy
{
    // Policy methods go here
}
```

## Policy Methods

### Standard Policy Methods

Laravel provides several standard policy methods that you can implement:

#### 1. `viewAny(User $user)`

Determines if the user can view a list of models.

```php
public function viewAny(User $user): bool
{
    // Allow all authenticated users to view job listings
    return true;
}
```

#### 2. `view(User $user, Job $job)`

Determines if the user can view a specific model.

```php
public function view(User $user, Job $job): bool
{
    // Allow all users to view job listings
    return true;
}
```

#### 3. `create(User $user)`

Determines if the user can create new models.

```php
public function create(User $user): bool
{
    // Allow authenticated users to create jobs
    return auth()->check();
}
```

#### 4. `update(User $user, Job $job)`

Determines if the user can update a specific model.

```php
public function update(User $user, Job $job): bool
{
    // Only the job owner can update their job
    return $user->id === $job->user_id;
}
```

#### 5. `delete(User $user, Job $job)`

Determines if the user can delete a specific model.

```php
public function delete(User $user, Job $job): bool
{
    // Only the job owner can delete their job
    return $user->id === $job->user_id;
}
```

#### 6. `restore(User $user, Job $job)`

Determines if the user can restore a soft-deleted model.

```php
public function restore(User $user, Job $job): bool
{
    // Only the job owner can restore their job
    return $user->id === $job->user_id;
}
```

#### 7. `forceDelete(User $user, Job $job)`

Determines if the user can permanently delete a model.

```php
public function forceDelete(User $user, Job $job): bool
{
    // Only admins can permanently delete jobs
    return $user->isAdmin();
}
```

### Custom Policy Methods

You can also create custom methods for specific actions:

```php
public function apply(User $user, Job $job): bool
{
    // Users can only apply to jobs they haven't applied to before
    return !$user->applications()->where('job_id', $job->id)->exists();
}

public function save(User $user, Job $job): bool
{
    // Users can save any job to their favorites
    return auth()->check();
}
```

## Registering Policies

### Method 1: Auto-Registration (Recommended)

Laravel automatically registers policies if they follow the naming convention:

-   Policy class: `JobPolicy`
-   Model: `Job`

### Method 2: Manual Registration

Register policies in `app/Providers/AuthServiceProvider.php`:

```php
<?php

namespace App\Providers;

use App\Models\Job;
use App\Policies\JobPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Job::class => JobPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
```

## Using Policies in Controllers

### Method 1: Using the `authorize()` Method

```php
public function edit(Job $job)
{
    $this->authorize('update', $job);

    return view('jobs.edit', ['job' => $job]);
}

public function update(Request $request, Job $job)
{
    $this->authorize('update', $job);

    // Update logic here
    $job->update($request->validated());

    return redirect()->route('jobs.show', $job);
}

public function destroy(Job $job)
{
    $this->authorize('delete', $job);

    $job->delete();

    return redirect()->route('jobs.index');
}
```

### Method 2: Using the `authorize()` Helper Function

```php
public function edit(Job $job)
{
    authorize('update', $job);

    return view('jobs.edit', ['job' => $job]);
}
```

### Method 3: Using the `@can` Directive in Blade

```php
@can('update', $job)
    <a href="{{ route('jobs.edit', $job) }}" class="btn btn-primary">Edit</a>
@endcan

@can('delete', $job)
    <form action="{{ route('jobs.destroy', $job) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete</button>
    </form>
@endcan
```

## Using Policies in Blade Templates

### Basic Authorization Checks

```blade
{{-- Check if user can create jobs --}}
@can('create', App\Models\Job::class)
    <a href="{{ route('jobs.create') }}" class="btn btn-primary">Create Job</a>
@endcan

{{-- Check if user can update a specific job --}}
@can('update', $job)
    <a href="{{ route('jobs.edit', $job) }}" class="btn btn-secondary">Edit</a>
@endcan

{{-- Check if user can delete a specific job --}}
@can('delete', $job)
    <form action="{{ route('jobs.destroy', $job) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete</button>
    </form>
@endcan
```

### Using `@cannot` Directive

```blade
@cannot('update', $job)
    <p class="text-muted">You don't have permission to edit this job.</p>
@endcannot
```

### Using `@canany` Directive

```blade
@canany(['update', 'delete'], $job)
    <div class="btn-group">
        @can('update', $job)
            <a href="{{ route('jobs.edit', $job) }}" class="btn btn-secondary">Edit</a>
        @endcan

        @can('delete', $job)
            <form action="{{ route('jobs.destroy', $job) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        @endcan
    </div>
@endcanany
```

## Policy Authorization Responses

### Returning Boolean Values

```php
public function update(User $user, Job $job): bool
{
    return $user->id === $job->user_id;
}
```

### Returning Response Objects

```php
use Illuminate\Auth\Access\Response;

public function update(User $user, Job $job): Response
{
    if ($user->id === $job->user_id) {
        return Response::allow();
    }

    return Response::deny('You can only edit your own job listings.');
}
```

### Returning Response with Custom Messages

```php
public function delete(User $user, Job $job): Response
{
    if ($user->id === $job->user_id) {
        return Response::allow();
    }

    return Response::deny('You can only delete your own job listings.')
        ->withStatus(403);
}
```

## Policy Filters

### Filtering Collections with Policies

```php
// In your controller
public function index()
{
    $jobs = Job::all()->filter(function ($job) {
        return $this->authorize('view', $job);
    });

    return view('jobs.index', compact('jobs'));
}
```

### Using Policy Scopes

```php
// In your Job model
public function scopeViewableBy($query, User $user)
{
    return $query->where('user_id', $user->id)
                 ->orWhere('is_public', true);
}

// In your controller
public function index()
{
    $jobs = Job::viewableBy(auth()->user())->get();

    return view('jobs.index', compact('jobs'));
}
```

## Best Practices

### 1. Keep Policies Simple

```php
// Good: Simple, clear logic
public function update(User $user, Job $job): bool
{
    return $user->id === $job->user_id;
}

// Bad: Complex logic in policy
public function update(User $user, Job $job): bool
{
    if ($user->isAdmin()) {
        return true;
    }

    if ($user->isModerator() && $job->isPending()) {
        return true;
    }

    return $user->id === $job->user_id && $job->isActive();
}
```

### 2. Use Descriptive Method Names

```php
// Good: Clear method names
public function apply(User $user, Job $job): bool
public function saveToFavorites(User $user, Job $job): bool

// Bad: Unclear method names
public function action1(User $user, Job $job): bool
public function doSomething(User $user, Job $job): bool
```

### 3. Test Your Policies

```php
// tests/Feature/JobPolicyTest.php
public function test_user_can_update_own_job()
{
    $user = User::factory()->create();
    $job = Job::factory()->create(['user_id' => $user->id]);

    $this->assertTrue($user->can('update', $job));
}

public function test_user_cannot_update_others_job()
{
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $job = Job::factory()->create(['user_id' => $user1->id]);

    $this->assertFalse($user2->can('update', $job));
}
```

### 4. Use Policy Responses for Better UX

```php
public function update(User $user, Job $job): Response
{
    if ($user->id === $job->user_id) {
        return Response::allow();
    }

    return Response::deny('You can only edit your own job listings.')
        ->withStatus(403);
}
```

## Real-World Example from Workopia

Based on the Workopia job board application, here's how policies are implemented:

### Current Implementation

**JobPolicy.php** (from the codebase):

```php
<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class JobPolicy
{
    public function viewAny(User $user): bool
    {
        return false; // Currently disabled
    }

    public function view(User $user, Job $job): bool
    {
        return false; // Currently disabled
    }

    public function create(User $user): bool
    {
        return false; // Currently disabled
    }

    public function update(User $user, Job $job): bool
    {
        return $user->id == $job->user_id;
    }

    public function delete(User $user, Job $job): bool
    {
        return $user->id == $job->user_id;
    }

    public function restore(User $user, Job $job): bool
    {
        return false;
    }

    public function forceDelete(User $user, Job $job): bool
    {
        return false;
    }
}
```

**JobController.php** (using policies):

```php
public function edit(Job $job)
{
    $this->authorize('update', $job);
    return view('jobs.edit', ['job' => $job]);
}

public function update(Request $request, Job $job)
{
    $this->authorize('update', $job);
    // Update logic here
}

public function destroy(Job $job)
{
    $this->authorize('delete', $job);
    // Delete logic here
}
```

### Improved Implementation

Here's how the Workopia policies could be enhanced:

```php
<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class JobPolicy
{
    public function viewAny(User $user): bool
    {
        // Allow all authenticated users to view job listings
        return auth()->check();
    }

    public function view(User $user, Job $job): bool
    {
        // Allow all users to view job listings
        return true;
    }

    public function create(User $user): bool
    {
        // Allow authenticated users to create jobs
        return auth()->check();
    }

    public function update(User $user, Job $job): Response
    {
        if ($user->id === $job->user_id) {
            return Response::allow();
        }

        return Response::deny('You can only edit your own job listings.');
    }

    public function delete(User $user, Job $job): Response
    {
        if ($user->id === $job->user_id) {
            return Response::allow();
        }

        return Response::deny('You can only delete your own job listings.');
    }

    public function apply(User $user, Job $job): bool
    {
        // Users can apply to jobs they haven't applied to before
        return auth()->check() &&
               !$user->applications()->where('job_id', $job->id)->exists();
    }

    public function save(User $user, Job $job): bool
    {
        // Users can save any job to their favorites
        return auth()->check();
    }

    public function restore(User $user, Job $job): bool
    {
        // Only the job owner can restore their job
        return $user->id === $job->user_id;
    }

    public function forceDelete(User $user, Job $job): bool
    {
        // Only admins can permanently delete jobs
        return $user->isAdmin();
    }
}
```

### Enhanced Controller Usage

```php
public function index()
{
    $this->authorize('viewAny', Job::class);

    $jobs = Job::all();
    return view('jobs.index', compact('jobs'));
}

public function create()
{
    $this->authorize('create', Job::class);

    return view('jobs.create');
}

public function store(Request $request)
{
    $this->authorize('create', Job::class);

    // Create job logic
}

public function show(Job $job)
{
    $this->authorize('view', $job);

    return view('jobs.show', compact('job'));
}

public function edit(Job $job)
{
    $this->authorize('update', $job);

    return view('jobs.edit', compact('job'));
}

public function update(Request $request, Job $job)
{
    $this->authorize('update', $job);

    // Update logic
}

public function destroy(Job $job)
{
    $this->authorize('delete', $job);

    // Delete logic
}

public function apply(Job $job)
{
    $this->authorize('apply', $job);

    // Apply logic
}

public function save(Job $job)
{
    $this->authorize('save', $job);

    // Save to favorites logic
}
```

### Enhanced Blade Templates

```blade
{{-- jobs/index.blade.php --}}
@can('create', App\Models\Job::class)
    <a href="{{ route('jobs.create') }}" class="btn btn-primary">Post a Job</a>
@endcan

@foreach($jobs as $job)
    <div class="job-card">
        <h3>{{ $job->title }}</h3>
        <p>{{ $job->description }}</p>

        <div class="job-actions">
            @can('view', $job)
                <a href="{{ route('jobs.show', $job) }}" class="btn btn-info">View Details</a>
            @endcan

            @can('update', $job)
                <a href="{{ route('jobs.edit', $job) }}" class="btn btn-secondary">Edit</a>
            @endcan

            @can('delete', $job)
                <form action="{{ route('jobs.destroy', $job) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            @endcan

            @can('apply', $job)
                <a href="{{ route('jobs.apply', $job) }}" class="btn btn-success">Apply Now</a>
            @endcan

            @can('save', $job)
                <button class="btn btn-outline-primary save-job" data-job-id="{{ $job->id }}">
                    Save Job
                </button>
            @endcan
        </div>
    </div>
@endforeach
```

## Conclusion

Laravel policies provide a powerful and flexible way to handle authorization in your application. By following the patterns and best practices outlined in this guide, you can create clean, maintainable, and secure authorization logic for your Laravel applications.

The key benefits of using policies include:

-   **Separation of concerns**: Authorization logic is separated from business logic
-   **Reusability**: Policies can be used across multiple controllers and views
-   **Testability**: Policies can be easily unit tested
-   **Maintainability**: Authorization logic is centralized and easy to modify
-   **Security**: Consistent authorization checks across your application

Remember to always test your policies thoroughly and keep them simple and focused on their specific responsibilities.
