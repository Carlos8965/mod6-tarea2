<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QueryController;

Route::get('/', function () {
    return view('welcome');
});

// Rutas para las consultas SQL
Route::prefix('api')->group(function () {
    Route::get('/usuarios-con-pedidos', [QueryController::class, 'usuariosConPedidos']);
    Route::get('/pedidos-usuario/{id}', [QueryController::class, 'pedidosPorUsuario']);
    Route::get('/total-ventas-usuario', [QueryController::class, 'totalVentasPorUsuario']);
    Route::get('/productos-mas-vendidos', [QueryController::class, 'productosMasVendidos']);
    Route::get('/buscar-usuarios', [QueryController::class, 'buscarUsuarios']);
    Route::get('/estadisticas', [QueryController::class, 'estadisticasGenerales']);

    // === CONSULTAS ESPECÍFICAS REQUERIDAS ===
    Route::get('/punto2-pedidos-usuario-id2', [QueryController::class, 'pedidosUsuarioId2']);
    Route::get('/punto3-pedidos-con-info-usuarios', [QueryController::class, 'pedidosConInfoUsuarios']);
    Route::get('/punto4-pedidos-rango-100-250', [QueryController::class, 'pedidosRango100a250']);
    Route::get('/punto5-usuarios-con-r', [QueryController::class, 'usuariosConR']);
    Route::get('/punto6-total-pedidos-usuario-id5', [QueryController::class, 'totalPedidosUsuarioId5']);
    Route::get('/punto7-pedidos-ordenados-por-total', [QueryController::class, 'pedidosOrdenadosPorTotal']);
    Route::get('/punto8-suma-total-pedidos', [QueryController::class, 'sumaTotalPedidos']);
    Route::get('/punto9-pedido-mas-economico', [QueryController::class, 'pedidoMasEconomico']);
    Route::get('/punto10-pedidos-agrupados-por-usuario', [QueryController::class, 'pedidosAgrupadosPorUsuario']);
});
