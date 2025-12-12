<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/storage-link', function () {
    $linkPath = public_path('storage');

    if (File::exists($linkPath)) {
        return response()->json([
            'status' => 'warning',
            'message' => 'El enlace simbólico ya existe. No se requiere acción.',
        ], 200);
    }

    try {
        // Ejecutar el comando storage:link
        Artisan::call('storage:link');

        return response()->json([
            'status' => 'success',
            'message' => 'Enlace simbólico creado exitosamente.',
            'output' => Artisan::output(),
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Error al crear el enlace simbólico.',
            'error' => $e->getMessage(),
        ], 500);
    }
});
Route::get('/', [HomeController::class, 'index'])->name('home');

// Categorías
Route::get('/{category:slug}', [HomeController::class, 'category'])
    ->name('category.show')
    ->where('category', '[a-zA-Z0-9\-]+');

// Detalle de noticia
Route::get('/noticia/{slug}', [HomeController::class, 'show'])
    ->name('news.show')
    ->where('slug', '[a-zA-Z0-9\-]+');

Route::get('crear-storage-link', function () {
    // Verificar si el enlace simbólico ya existe
    $linkPath = public_path('storage');

    if (File::exists($linkPath)) {
        return response()->json([
            'status' => 'warning',
            'message' => 'El enlace simbólico ya existe. No se requiere acción.',
        ], 200);
    }

    try {
        // Ejecutar el comando storage:link
        Artisan::call('storage:link');

        return response()->json([
            'status' => 'success',
            'message' => 'Enlace simbólico creado exitosamente.',
            'output' => Artisan::output(),
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Error al crear el enlace simbólico.',
            'error' => $e->getMessage(),
        ], 500);
    }
});
