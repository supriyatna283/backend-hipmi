protected $middlewareGroups = [
'api' => [
\App\Http\Middleware\CorsMiddleware::class, // ← Tambah ini
'throttle:api',
\Illuminate\Routing\Middleware\SubstituteBindings::class,
],
];