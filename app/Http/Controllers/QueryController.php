<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class QueryController extends Controller
{
    /**
     * Obtener todos los usuarios con sus pedidos
     */
    public function usuariosConPedidos()
    {
        // Consulta usando Eloquent con relaciones
        $usuarios = User::with('pedidos')->get();
        
        return response()->json([
            'message' => 'Usuarios con sus pedidos',
            'data' => $usuarios
        ]);
    }

    /**
     * Obtener pedidos de un usuario específico
     */
    public function pedidosPorUsuario($id_usuario)
    {
        // Consulta usando Query Builder
        $pedidos = DB::table('pedidos')
            ->join('users', 'pedidos.id_usuario', '=', 'users.id')
            ->where('users.id', $id_usuario)
            ->select('pedidos.*', 'users.nombre as nombre_usuario')
            ->get();

        return response()->json([
            'message' => "Pedidos del usuario ID: $id_usuario",
            'data' => $pedidos
        ]);
    }

    /**
     * Obtener el total de ventas por usuario
     */
    public function totalVentasPorUsuario()
    {
        // Consulta con agregación
        $totales = DB::table('pedidos')
            ->join('users', 'pedidos.id_usuario', '=', 'users.id')
            ->select(
                'users.id',
                'users.nombre',
                DB::raw('SUM(pedidos.total) as total_ventas'),
                DB::raw('COUNT(pedidos.id) as total_pedidos')
            )
            ->groupBy('users.id', 'users.nombre')
            ->orderBy('total_ventas', 'desc')
            ->get();

        return response()->json([
            'message' => 'Total de ventas por usuario',
            'data' => $totales
        ]);
    }

    /**
     * Obtener productos más vendidos
     */
    public function productosMasVendidos()
    {
        $productos = DB::table('pedidos')
            ->select(
                'producto',
                DB::raw('SUM(cantidad) as cantidad_total'),
                DB::raw('SUM(total) as ventas_total'),
                DB::raw('COUNT(*) as numero_pedidos')
            )
            ->groupBy('producto')
            ->orderBy('cantidad_total', 'desc')
            ->get();

        return response()->json([
            'message' => 'Productos más vendidos',
            'data' => $productos
        ]);
    }

    /**
     * Buscar usuarios por nombre o correo
     */
    public function buscarUsuarios(Request $request)
    {
        $termino = $request->get('termino', '');
        
        $usuarios = User::where('nombre', 'LIKE', "%$termino%")
            ->orWhere('correo', 'LIKE', "%$termino%")
            ->with('pedidos')
            ->get();

        return response()->json([
            'message' => "Usuarios encontrados para: $termino",
            'data' => $usuarios
        ]);
    }

    /**
     * Obtener estadísticas generales
     */
    public function estadisticasGenerales()
    {
        $stats = [
            'total_usuarios' => User::count(),
            'total_pedidos' => Order::count(),
            'total_ventas' => Order::sum('total'),
            'promedio_venta' => Order::avg('total'),
            'usuario_mas_activo' => DB::table('pedidos')
                ->join('users', 'pedidos.id_usuario', '=', 'users.id')
                ->select('users.nombre', DB::raw('COUNT(pedidos.id) as total_pedidos'))
                ->groupBy('users.id', 'users.nombre')
                ->orderBy('total_pedidos', 'desc')
                ->first()
        ];

        return response()->json([
            'message' => 'Estadísticas generales del sistema',
            'data' => $stats
        ]);
    }

    // ===== CONSULTAS ESPECÍFICAS REQUERIDAS =====

    /**
     * PUNTO 2: Recupera todos los pedidos asociados al usuario con ID 2
     */
    public function pedidosUsuarioId2()
    {
        // Consulta usando Eloquent
        $pedidos = Order::where('id_usuario', 2)->get();
        
        // Alternativa usando Query Builder
        $pedidosQB = DB::table('pedidos')
            ->where('id_usuario', 2)
            ->get();

        return response()->json([
            'message' => 'Pedidos del usuario con ID 2',
            'eloquent' => $pedidos,
            'query_builder' => $pedidosQB
        ]);
    }

    /**
     * PUNTO 3: Obtén la información detallada de los pedidos, 
     * incluyendo el nombre y correo electrónico de los usuarios
     */
    public function pedidosConInfoUsuarios()
    {
        // Usando Eloquent con relaciones
        $pedidosEloquent = Order::with('usuario:id,nombre,correo')->get();
        
        // Usando Query Builder con JOIN
        $pedidosJoin = DB::table('pedidos')
            ->join('users', 'pedidos.id_usuario', '=', 'users.id')
            ->select(
                'pedidos.*',
                'users.nombre as usuario_nombre',
                'users.correo as usuario_correo'
            )
            ->get();

        return response()->json([
            'message' => 'Información detallada de pedidos con datos de usuarios',
            'eloquent_con_relaciones' => $pedidosEloquent,
            'query_builder_con_join' => $pedidosJoin
        ]);
    }

    /**
     * PUNTO 4: Recupera todos los pedidos cuyo total esté en el rango de $100 a $250
     */
    public function pedidosRango100a250()
    {
        // Usando Eloquent
        $pedidosEloquent = Order::whereBetween('total', [100, 250])->get();
        
        // Usando Query Builder
        $pedidosQB = DB::table('pedidos')
            ->whereBetween('total', [100, 250])
            ->get();

        return response()->json([
            'message' => 'Pedidos con total entre $100 y $250',
            'eloquent' => $pedidosEloquent,
            'query_builder' => $pedidosQB
        ]);
    }

    /**
     * PUNTO 5: Encuentra todos los usuarios cuyos nombres comiencen con la letra "R"
     */
    public function usuariosConR()
    {
        // Usando Eloquent
        $usuariosEloquent = User::where('nombre', 'LIKE', 'R%')->get();
        
        // Usando Query Builder
        $usuariosQB = DB::table('users')
            ->where('nombre', 'LIKE', 'R%')
            ->get();

        return response()->json([
            'message' => 'Usuarios cuyos nombres comienzan con "R"',
            'eloquent' => $usuariosEloquent,
            'query_builder' => $usuariosQB
        ]);
    }

    /**
     * PUNTO 6: Calcula el total de registros en la tabla de pedidos para el usuario con ID 5
     */
    public function totalPedidosUsuarioId5()
    {
        // Usando Eloquent
        $totalEloquent = Order::where('id_usuario', 5)->count();
        
        // Usando Query Builder
        $totalQB = DB::table('pedidos')
            ->where('id_usuario', 5)
            ->count();

        return response()->json([
            'message' => 'Total de pedidos para usuario con ID 5',
            'total_eloquent' => $totalEloquent,
            'total_query_builder' => $totalQB
        ]);
    }

    /**
     * PUNTO 7: Recupera todos los pedidos junto con la información de los usuarios,
     * ordenándolos de forma descendente según el total del pedido
     */
    public function pedidosOrdenadosPorTotal()
    {
        // Usando Eloquent con relaciones
        $pedidosEloquent = Order::with('usuario:id,nombre,correo')
            ->orderBy('total', 'desc')
            ->get();
        
        // Usando Query Builder con JOIN
        $pedidosJoin = DB::table('pedidos')
            ->join('users', 'pedidos.id_usuario', '=', 'users.id')
            ->select(
                'pedidos.*',
                'users.nombre as usuario_nombre',
                'users.correo as usuario_correo'
            )
            ->orderBy('pedidos.total', 'desc')
            ->get();

        return response()->json([
            'message' => 'Pedidos con información de usuarios ordenados por total (descendente)',
            'eloquent_con_relaciones' => $pedidosEloquent,
            'query_builder_con_join' => $pedidosJoin
        ]);
    }

    /**
     * PUNTO 8: Obtén la suma total del campo "total" en la tabla de pedidos
     */
    public function sumaTotalPedidos()
    {
        // Usando Eloquent
        $sumaEloquent = Order::sum('total');
        
        // Usando Query Builder
        $sumaQB = DB::table('pedidos')->sum('total');

        return response()->json([
            'message' => 'Suma total del campo "total" en la tabla de pedidos',
            'suma_eloquent' => $sumaEloquent,
            'suma_query_builder' => $sumaQB
        ]);
    }

    /**
     * PUNTO 9: Encuentra el pedido más económico, junto con el nombre del usuario asociado
     */
    public function pedidoMasEconomico()
    {
        // Usando Eloquent con relación
        $pedidoEloquent = Order::with('usuario:id,nombre')
            ->orderBy('total', 'asc')
            ->first();
        
        // Usando Query Builder con JOIN
        $pedidoJoin = DB::table('pedidos')
            ->join('users', 'pedidos.id_usuario', '=', 'users.id')
            ->select(
                'pedidos.*',
                'users.nombre as usuario_nombre'
            )
            ->orderBy('pedidos.total', 'asc')
            ->first();

        return response()->json([
            'message' => 'Pedido más económico con nombre del usuario',
            'eloquent_con_relacion' => $pedidoEloquent,
            'query_builder_con_join' => $pedidoJoin
        ]);
    }

    /**
     * PUNTO 10: Obtén el producto, la cantidad y el total de cada pedido, 
     * agrupándolos por usuario
     */
    public function pedidosAgrupadosPorUsuario()
    {
        // Usando Eloquent con relaciones agrupadas
        $usuariosConPedidos = User::with(['pedidos:id_usuario,producto,cantidad,total'])
            ->get()
            ->map(function ($usuario) {
                return [
                    'usuario_id' => $usuario->id,
                    'usuario_nombre' => $usuario->nombre,
                    'pedidos' => $usuario->pedidos->map(function ($pedido) {
                        return [
                            'producto' => $pedido->producto,
                            'cantidad' => $pedido->cantidad,
                            'total' => $pedido->total
                        ];
                    })
                ];
            });
        
        // Usando Query Builder con JOIN y agrupación
        $pedidosAgrupados = DB::table('pedidos')
            ->join('users', 'pedidos.id_usuario', '=', 'users.id')
            ->select(
                'users.id as usuario_id',
                'users.nombre as usuario_nombre',
                'pedidos.producto',
                'pedidos.cantidad',
                'pedidos.total'
            )
            ->orderBy('users.id')
            ->orderBy('pedidos.producto')
            ->get()
            ->groupBy('usuario_id');

        return response()->json([
            'message' => 'Producto, cantidad y total de pedidos agrupados por usuario',
            'eloquent_agrupado' => $usuariosConPedidos,
            'query_builder_agrupado' => $pedidosAgrupados
        ]);
    }
}