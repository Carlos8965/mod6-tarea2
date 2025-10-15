<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pedidos = [
            ['producto' => 'Laptop Dell', 'cantidad' => 1, 'total' => 1200.00, 'id_usuario' => 1],
            ['producto' => 'Mouse Logitech', 'cantidad' => 2, 'total' => 50.00, 'id_usuario' => 1],
            ['producto' => 'Teclado Mecánico', 'cantidad' => 1, 'total' => 150.00, 'id_usuario' => 2],
            ['producto' => 'Monitor Samsung', 'cantidad' => 1, 'total' => 300.00, 'id_usuario' => 2],
            ['producto' => 'Smartphone iPhone', 'cantidad' => 1, 'total' => 999.00, 'id_usuario' => 3],
            ['producto' => 'Auriculares Sony', 'cantidad' => 1, 'total' => 200.00, 'id_usuario' => 3],
            ['producto' => 'Tablet iPad', 'cantidad' => 1, 'total' => 600.00, 'id_usuario' => 4],
            ['producto' => 'Cargador USB-C', 'cantidad' => 3, 'total' => 75.00, 'id_usuario' => 4],
            ['producto' => 'Cámara Canon', 'cantidad' => 1, 'total' => 800.00, 'id_usuario' => 5],
            ['producto' => 'Memoria USB', 'cantidad' => 5, 'total' => 100.00, 'id_usuario' => 5],
            ['producto' => 'Laptop Dell', 'cantidad' => 1, 'total' => 1200.00, 'id_usuario' => 2],
            ['producto' => 'Mouse Logitech', 'cantidad' => 1, 'total' => 25.00, 'id_usuario' => 3],
        ];

        foreach ($pedidos as $pedido) {
            Order::create($pedido);
        }
    }
}
