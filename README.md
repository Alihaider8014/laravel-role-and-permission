# Laravel Role and Permission Management

This project demonstrates how to implement authorization using Gates in Laravel. Gates provide a simple way to authorize actions and are ideal for straightforward authorization logic not tied to specific models. Authentication is handled using Laravel Sanctum.

## Table of Contents

- [Installation](#installation)
- [Database Setup](#database-setup)
- [Authentication Setup](#authentication-setup)
- [Usage](#usage)
- [Creating Roles and Permissions](#creating-roles-and-permissions)
- [Middleware](#middleware)
- [Route Setup](#route-setup)

## Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/Alihaider8014/laravel-role-and-permission.git
    cd laravel-role-and-permission
    ```

2. Install the dependencies:
    ```bash
    composer install
    ```

3. Copy the `.env.example` file to `.env` and configure your environment settings:
    ```bash
    cp .env.example .env
    ```

4. Generate an application key:
    ```bash
    php artisan key:generate
    ```


## Database Setup

1. Configure your database settings in the `.env` file.

2. Run the database migrations:
    ```bash
    php artisan migrate
    ```

3. Seed the database with initial roles and permissions (optional):
    ```bash
    php artisan db:seed
    ```

## Authentication Setup

This project uses Laravel Sanctum for API authentication.

1. Install Sanctum:
    ```bash
    composer require laravel/sanctum
    ```

2. Publish the Sanctum configuration:
    ```bash
    php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
    ```

3. Run Sanctum migrations:
    ```bash
    php artisan migrate
    ```

4. Configure API Tokens:
    ```php
        
    namespace App\Models;

    use Laravel\Sanctum\HasApiTokens;
    use Illuminate\Foundation\Auth\User as Authenticatable;

    class User extends Authenticatable
    {
        use HasApiTokens, Notifiable;
        // Other model methods and properties
    }
    ```
5. Add the following routes for authentication in routes/api.php:
    ```php
        
    use App\Http\Controllers\AuthController;
    use Illuminate\Support\Facades\Route;

    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
    Route::middleware(['auth:sanctum'])->post('/logout', [AuthController::class, 'logout']);
    ```


## Usage

### Creating Roles and Permissions

Roles and permissions can be created and assigned using Eloquent models. Example seeding code is provided in `DatabaseSeeder`.

### Using Gates

Gates provide a simple way to authorize actions. Gates are defined in the `AuthServiceProvider`.

    ```php
    public function boot()
        {
            $this->registerPolicies();

            $permissions = Permission::with('roles')->get();
            foreach ($permissions as $permission) {
                Gate::define($permission->name, function ($user) use ($permission) {
                    foreach ($user->roles as $role) {
                        if ($role->permissions->contains($permission)) {
                            return true;
                        }   
                    }
                    return false;
                });
            }
        }
    ```

## Creating Roles and Permissions

To create roles and permissions, you can use the following commands within a seeder or controller:

    ```php
    use App\Models\Role;
    use App\Models\Permission;

    $adminRole = Role::create(['name' => 'admin']);
    $editorRole = Role::create(['name' => 'editor']);

    $editArticlesPermission = Permission::create(['name' => 'edit articles']);
    $deleteCommentsPermission = Permission::create(['name' => 'delete comments']);

    $adminRole->permissions()->attach([$editArticlesPermission->id, $deleteCommentsPermission->id]);
    $editorRole->permissions()->attach($editArticlesPermission->id);
    ```

## Middleware

In this project, middleware is used to protect routes by ensuring that only authenticated users with the required permissions can access them.

    ```php
    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Gate;

    class CheckPermission
    {
        /**
         * Handle an incoming request.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
         * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
         */
        public function handle(Request $request, Closure $next, $permission)
        {
            if (Gate::denies($permission)) {
                return response()->json(['message' => 'Unauthorized action.'], 403);
            }
            return $next($request);
        }
    }
    ```

## Route Setup

Define your routes and apply the middleware for authorization checks.

    ```php
    Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/articles/add', [ArticleController::class, 'add'])->middleware('permission:add articles');
    Route::get('/articles/edit/{id}', [ArticleController::class, 'edit'])->middleware('permission:edit articles');
    Route::get('/articles/view', [ArticleController::class, 'view'])->middleware('permission:view articles');
    Route::get('/articles/delete/{id}', [ArticleController::class, 'delete'])->middleware('permission:delete articles');
    // Other routes...
    });
    ```
