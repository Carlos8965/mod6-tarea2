<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

// Configurar la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CONSULTAS SQL ESPECÍFICAS REQUERIDAS ===\n\n";

try {
    // PUNTO 1: Verificar que tenemos al menos 5 registros
    echo "PUNTO 1: Verificar datos insertados\n";
    echo "---------------------------------------\n";
    $totalUsuarios = User::count();
    $totalPedidos = Order::count();
    echo "✅ Total usuarios: $totalUsuarios\n";
    echo "✅ Total pedidos: $totalPedidos\n\n";

    // PUNTO 2: Recupera todos los pedidos asociados al usuario con ID 2
    echo "PUNTO 2: Pedidos del usuario con ID 2\n";
    echo "-------------------------------------\n";
    $pedidosUsuario2 = Order::where('id_usuario', 2)->get();
    echo "📋 Pedidos encontrados: " . $pedidosUsuario2->count() . "\n";
    foreach ($pedidosUsuario2 as $pedido) {
        echo "   - {$pedido->producto}: {$pedido->cantidad} unidades, $" . number_format($pedido->total, 2) . "\n";
    }
    echo "\n";

    // PUNTO 3: Información detallada de pedidos con nombre y correo de usuarios
    echo "PUNTO 3: Pedidos con información detallada de usuarios\n";
    echo "-----------------------------------------------------\n";
    $pedidosDetallados = DB::table('pedidos')
        ->join('users', 'pedidos.id_usuario', '=', 'users.id')
        ->select('pedidos.*', 'users.nombre as usuario_nombre', 'users.correo as usuario_correo')
        ->get();
    echo "📋 Total pedidos con información de usuario: " . $pedidosDetallados->count() . "\n";
    foreach ($pedidosDetallados->take(3) as $pedido) {
        echo "   - {$pedido->producto} | Usuario: {$pedido->usuario_nombre} ({$pedido->usuario_correo}) | Total: $" . number_format($pedido->total, 2) . "\n";
    }
    echo "   ... (mostrando solo los primeros 3)\n\n";

    // PUNTO 4: Pedidos con total entre $100 y $250
    echo "PUNTO 4: Pedidos con total entre $100 y $250\n";
    echo "--------------------------------------------\n";
    $pedidosRango = Order::whereBetween('total', [100, 250])->get();
    echo "📋 Pedidos en rango $100-$250: " . $pedidosRango->count() . "\n";
    foreach ($pedidosRango as $pedido) {
        echo "   - {$pedido->producto}: $" . number_format($pedido->total, 2) . "\n";
    }
    echo "\n";

    // PUNTO 5: Usuarios cuyos nombres comienzan con "R"
    echo "PUNTO 5: Usuarios con nombres que empiezan con 'R'\n";
    echo "--------------------------------------------------\n";
    $usuariosR = User::where('nombre', 'LIKE', 'R%')->get();
    echo "👤 Usuarios encontrados: " . $usuariosR->count() . "\n";
    foreach ($usuariosR as $usuario) {
        echo "   - {$usuario->nombre} ({$usuario->correo})\n";
    }
    echo "\n";

    // PUNTO 6: Total de registros de pedidos para usuario ID 5
    echo "PUNTO 6: Total de pedidos para usuario con ID 5\n";
    echo "-----------------------------------------------\n";
    $totalPedidosUsuario5 = Order::where('id_usuario', 5)->count();
    echo "📊 Total de pedidos del usuario ID 5: $totalPedidosUsuario5\n\n";

    // PUNTO 7: Pedidos con usuarios ordenados por total descendente
    echo "PUNTO 7: Pedidos ordenados por total (descendente)\n";
    echo "--------------------------------------------------\n";
    $pedidosOrdenados = DB::table('pedidos')
        ->join('users', 'pedidos.id_usuario', '=', 'users.id')
        ->select('pedidos.*', 'users.nombre as usuario_nombre')
        ->orderBy('pedidos.total', 'desc')
        ->get();
    echo "📋 Pedidos ordenados por total: " . $pedidosOrdenados->count() . "\n";
    foreach ($pedidosOrdenados->take(5) as $pedido) {
        echo "   - {$pedido->producto} | {$pedido->usuario_nombre} | $" . number_format($pedido->total, 2) . "\n";
    }
    echo "   ... (mostrando solo los primeros 5)\n\n";

    // PUNTO 8: Suma total del campo "total" en pedidos
    echo "PUNTO 8: Suma total de todos los pedidos\n";
    echo "---------------------------------------\n";
    $sumaTotal = Order::sum('total');
    echo "💰 Suma total de todos los pedidos: $" . number_format($sumaTotal, 2) . "\n\n";

    // PUNTO 9: Pedido más económico con nombre del usuario
    echo "PUNTO 9: Pedido más económico\n";
    echo "-----------------------------\n";
    $pedidoEconomico = DB::table('pedidos')
        ->join('users', 'pedidos.id_usuario', '=', 'users.id')
        ->select('pedidos.*', 'users.nombre as usuario_nombre')
        ->orderBy('pedidos.total', 'asc')
        ->first();
    echo "🏷️ Pedido más económico:\n";
    echo "   - Producto: {$pedidoEconomico->producto}\n";
    echo "   - Usuario: {$pedidoEconomico->usuario_nombre}\n";
    echo "   - Total: $" . number_format($pedidoEconomico->total, 2) . "\n\n";

    // PUNTO 10: Producto, cantidad y total agrupados por usuario
    echo "PUNTO 10: Pedidos agrupados por usuario\n";
    echo "--------------------------------------\n";
    $usuariosConPedidos = User::with('pedidos')->get();
    foreach ($usuariosConPedidos as $usuario) {
        echo "👤 Usuario: {$usuario->nombre}\n";
        if ($usuario->pedidos->count() > 0) {
            foreach ($usuario->pedidos as $pedido) {
                echo "   - {$pedido->producto}: {$pedido->cantidad} unidades, $" . number_format($pedido->total, 2) . "\n";
            }
        } else {
            echo "   - Sin pedidos\n";
        }
        echo "\n";
    }

    echo "=== ✅ TODAS LAS CONSULTAS ESPECÍFICAS EJECUTADAS CORRECTAMENTE ===\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}