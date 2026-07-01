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
use App\Http\Controllers\AdminRepartidorController;
use App\Http\Controllers\ReposicionController;
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
Route::get('/pago/retorno', [PagoController::class, 'retorno'])->name('pago.retorno');

// Rutas Protegidas (Requieren inicio de sesión)
Route::middleware('auth')->group(function () {
    // Gestión del Perfil
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
    Route::post('/mis-pedidos/{pedido}/verificar-pago', [PagoController::class, 'verificar'])->name('pagos.verificar');
});

// Panel de Administración (Restringido para Administradores)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', AdminDashboardController::class)->name('dashboard');
    Route::resource('libros', AdminLibroController::class)->except('show');
    Route::post('libros/{libro}/stock', [AdminLibroController::class, 'ajustarStock'])->name('libros.stock');
    Route::get('inventario', [AdminInventarioController::class, 'index'])->name('inventario.index');
    Route::resource('usuarios', AdminUsuarioController::class);
    Route::patch('usuarios/{usuario}/toggle-status', [AdminUsuarioController::class, 'toggleStatus'])->name('usuarios.toggle-status');
    Route::resource('categorias', CategoriaController::class)->only(['index', 'store', 'update', 'destroy']);
    
    Route::resource('proveedores', ProveedorController::class)
        ->parameters(['proveedores' => 'proveedor'])
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::patch('proveedores/{proveedor}/toggle-status', [ProveedorController::class, 'toggleStatus'])->name('proveedores.toggle-status');
    
    // CORRECCIÓN: Se agrega parameters() para evitar el error 404 con {repartidore}
    Route::resource('repartidores', AdminRepartidorController::class)
        ->parameters(['repartidores' => 'repartidor']);
    Route::patch('repartidores/{repartidor}/toggle-status', [AdminRepartidorController::class, 'toggleStatus'])->name('repartidores.toggle-status');
    
    Route::get('pedidos', [AdminPedidoController::class, 'index'])->name('pedidos.index');
    Route::get('pedidos/{pedido}', [AdminPedidoController::class, 'show'])->name('pedidos.show');
    Route::patch('pedidos/{pedido}/estado', [AdminPedidoController::class, 'updateStatus'])->name('pedidos.update-status');
    
    Route::prefix('inventario/reposicion')->name('reposicion.')->group(function () {
        Route::get('/paso1', [ReposicionController::class, 'primerpaso'])->name('paso1');
        Route::post('/procesarpaso1', [ReposicionController::class, 'procesarpaso1'])->name('procesarpaso1');
        Route::get('/paso2', [ReposicionController::class, 'segundopaso'])->name('paso2');
        Route::post('/procesarpaso2', [ReposicionController::class, 'procesarpaso2'])->name('procesarpaso2');
        Route::get('/paso3', [ReposicionController::class, 'tercerpaso'])->name('paso3');
        Route::post('/procesarpaso3', [ReposicionController::class, 'procesarpaso3'])->name('procesarpaso3');
        Route::get('/paso4', [ReposicionController::class, 'cuartopaso'])->name('paso4');
        Route::post('/confirmar', [ReposicionController::class, 'confirmarOrdenes'])->name('confirmar');
        Route::get('/confirmado', [ReposicionController::class, 'confirmado'])->name('confirmado');
    });
});

require __DIR__.'/auth.php';