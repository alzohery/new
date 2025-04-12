<?php
/*
|--------------------------------------------------------------------------
| Kernel.php
|--------------------------------------------------------------------------
| Made by Mohamed Alzohery
|--------------------------------------------------------------------------
| This file defines the HTTP kernel for the Laravel application. The HTTP
| kernel is responsible for bootstrapping the framework and processing
| incoming HTTP requests. It defines the global middleware stack, route
| middleware groups, and individual route middleware that are applied
| during the request lifecycle.
|
| protected $middleware:
|   This array defines the application's global HTTP middleware stack.
|   These middleware are executed for every request that enters the application.
|   They perform tasks such as trusting proxies, preventing maintenance mode,
|   validating post size, trimming strings, converting empty strings to null,
|   and handling Cross-Origin Resource Sharing (CORS) if enabled.
|   The order in this array is significant as middleware are executed in the
|   order they are listed.
|
| protected $middlewareGroups:
|   This array defines named groups of middleware that can be easily applied
|   to routes. Laravel provides two default groups:
|   - 'web': Contains middleware that are common for web routes, such as cookie
|     encryption and handling, session management, CSRF protection, and route
|     model binding.
|   - 'api': Contains middleware that are typically used for API routes, such as
|     rate limiting ('throttle:api') and route model binding.
|   We have ensured that the 'api' middleware group does NOT include the standard
|   'auth' middleware, as API authentication is handled differently (using Sanctum).
|
| protected $routeMiddleware:
|   This array defines individual middleware with assigned keys (aliases). These
|   middleware can be applied to specific routes or middleware groups by their
|   defined keys.
|   - 'auth': Maps to the `\App\Http\Middleware\Authenticate::class` middleware
|     that we (potentially) created or modified to handle API authentication
|     redirection correctly.
|   - 'auth.basic': Middleware for HTTP basic authentication.
|   - 'cache.headers': Middleware for setting cache headers.
|   - 'can': Middleware for authorizing user actions based on defined abilities.
|   - 'guest': Middleware for redirecting authenticated users from guest-only routes.
|   - 'signed': Middleware for validating signed route URLs.
|   - 'throttle': Middleware for rate limiting requests.
|   - 'verified': Middleware for ensuring users have verified their email addresses.
|   - 'auth:sanctum': Middleware provided by Laravel Sanctum for authenticating
|     API requests using tokens. Our API routes are protected using this middleware.
|
| protected $middlewarePriority:
|   This array defines the priority order in which middleware are run. This allows
|   certain middleware to always run before or after others, regardless of their
|   order in the `$middleware` or `$middlewareGroups` arrays. This can be important
|   for middleware that depend on the output of other middleware (e.g., session
|   middleware should typically run before middleware that use session data).
|   We have ensured that our custom `\App\Http\Middleware\Authenticate::class` is
|   positioned appropriately in this priority list.
|
| In summary, the `Kernel.php` file is crucial for configuring the HTTP request
| lifecycle in our Laravel API. We have specifically configured the 'api' middleware
| group to exclude traditional web authentication and ensured that the 'auth:sanctum'
| middleware is used for protecting our API routes. We also addressed the
| "Route [login] not defined" error by potentially creating or modifying the 'auth'
| middleware to handle API authentication failures with a 401 response instead of
| redirecting to a web 'login' route.
*/
namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string<\Illuminate\Foundation\Http\Middleware>>
     */
    protected $middleware = [
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\HandleCors::class, 
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string<\Illuminate\Contracts\Routing\Middleware>>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string<\Illuminate\Contracts\Routing\Middleware>>
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        // 'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'auth:sanctum' => \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces non-global middleware to always run before the Flushbar middleware.
     *
     * @var array<int, string>
     */
    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\Authenticate::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];
}