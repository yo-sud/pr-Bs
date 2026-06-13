<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminInventarioController;
use App\Http\Controllers\AdminLibroController;
use App\Http\Controllers\AdminPedidoController;
use App\Http\Controllers\AdminUsuarioController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PagoWebhookController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProveedorController;
use Illuminate\Support\Facades\Route;

// Rutas Públicas de la Tienda
Route::get('/', [LibroController::class, 'inicio'])->name('home');
Route::get('/todos-los-libros', [LibroController::class, 'index'])->name('libros.index');
Route::get('/novedades', [LibroController::class, 'novedades'])->name('libros.novedades');
Route::get('/populares', [LibroController::class, 'populares'])->name('libros.populares');
Route::get('/libros/{libro}', [LibroController::class, 'show'])->name('libros.show');
Route::get('/quienes-somos', [LibroController::class, 'quienesSomos'])->name('quienes-somos');

// Rutas del Carrito de Compras
Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
Route::post('/carrito/{libro}', [CarritoController::class, 'store'])->name('carrito.store');
Route::patch('/carrito/{libro}', [CarritoController::class, 'update'])->name('carrito.update');
Route::delete('/carrito/{libro}', [CarritoController::class, 'destroy'])->name('carrito.destroy');
Route::delete('/carrito', [CarritoController::class, 'clear'])->name('carrito.clear');
Route::post('/webhooks/pagos/falso', PagoWebhookController::class)->name('webhooks.pagos.falso');

// Rutas Protegidas (Requieren inicio de sesión)
Route::middleware('auth')->group(function () {
    // Gestión del Perfil (Se removió destroy por limpieza de código)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Procesos de Compra y Pedidos del Cliente
    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/mis-pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
    Route::get('/mis-pedidos/{pedido}', [PedidoController::class, 'show'])->name('pedidos.show');
    Route::post('/mis-pedidos/{pedido}/cancelar', [PedidoController::class, 'cancel'])->name('pedidos.cancel');
    Route::get('/mis-pedidos/{pedido}/pagar', [PagoController::class, 'create'])->name('pagos.create');
    Route::post('/mis-pedidos/{pedido}/pagar', [PagoController::class, 'store'])->name('pagos.store');
});

// Panel de Administración (Restringido para Administradores)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', AdminDashboardController::class)->name('dashboard');
    Route::resource('libros', AdminLibroController::class)->except('show');
    Route::post('libros/{libro}/stock', [AdminLibroController::class, 'ajustarStock'])->name('libros.stock');
    Route::get('inventario', [AdminInventarioController::class, 'index'])->name('inventario.index');
    Route::resource('usuarios', AdminUsuarioController::class);
    Route::resource('categorias', CategoriaController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('proveedores', ProveedorController::class)
        ->parameters(['proveedores' => 'proveedor'])
        ->only(['index', 'store', 'update', 'destroy']);
    Route::get('pedidos', [AdminPedidoController::class, 'index'])->name('pedidos.index');
    Route::get('pedidos/{pedido}', [AdminPedidoController::class, 'show'])->name('pedidos.show');
    Route::patch('pedidos/{pedido}/estado', [AdminPedidoController::class, 'updateStatus'])->name('pedidos.update-status');
});

require __DIR__.'/auth.php';