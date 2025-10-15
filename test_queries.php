<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

// Configurar la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== PRUEBAS DE CONSULTAS SQL ===\n\n";

try {
    // 1. Contar usuarios
    $totalUsuarios = User::count();
    echo "1. Total de usuarios: $totalUsuarios\n\n";

    // 2. Contar pedidos
    $totalPedidos = Order::count();
    echo "2. Total de pedidos: $totalPedidos\n\n";

    // 3. Usuarios con sus pedidos
    echo "3. Usuarios con sus pedidos:\n";
    $usuarios = User::with('pedidos')->get();
    foreach ($usuarios as $usuario) {
        echo "   - {$usuario->nombre} ({$usuario->correo}): {$usuario->pedidos->count()} pedidos\n";
    }
    echo "\n";

    // 4. Total de ventas por usuario
    echo "4. Total de ventas por usuario:\n";
    $ventas = DB::table('pedidos')
        ->join('users', 'pedidos.id_usuario', '=', 'users.id')
        ->select(
            'users.nombre',
            DB::raw('SUM(pedidos.total) as total_ventas'),
            DB::raw('COUNT(pedidos.id) as total_pedidos')
        )
        ->groupBy('users.id', 'users.nombre')
        ->orderBy('total_ventas', 'desc')
        ->get();

    foreach ($ventas as $venta) {
        echo "   - {$venta->nombre}: $" . number_format($venta->total_ventas, 2) . " ({$venta->total_pedidos} pedidos)\n";
    }
    echo "\n";

    // 5. Productos más vendidos
    echo "5. Productos más vendidos:\n";
    $productos = DB::table('pedidos')
        ->select(
            'producto',
            DB::raw('SUM(cantidad) as cantidad_total'),
            DB::raw('SUM(total) as ventas_total')
        )
        ->groupBy('producto')
        ->orderBy('cantidad_total', 'desc')
        ->get();

    foreach ($productos as $producto) {
        echo "   - {$producto->producto}: {$producto->cantidad_total} unidades, $" . number_format($producto->ventas_total, 2) . "\n";
    }

    echo "\n=== TODAS LAS CONSULTAS FUNCIONAN CORRECTAMENTE ===\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}