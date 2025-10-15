<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuarios = [
            [
                'nombre' => 'Juan Pérez',
                'correo' => 'juan@gmail.com',
                'telefono' => '555-1234'
            ],
            [
                'nombre' => 'María García',
                'correo' => 'maria@gmail.com',
                'telefono' => '555-5678'
            ],
            [
                'nombre' => 'Carlos López',
                'correo' => 'carlos@gmail.com',
                'telefono' => '555-9012'
            ],
            [
                'nombre' => 'Ana Martínez',
                'correo' => 'ana@gmail.com',
                'telefono' => '555-3456'
            ],
            [
                'nombre' => 'Roberto Fernández',
                'correo' => 'roberto@gmail.com',
                'telefono' => '555-7890'
            ]
        ];

        foreach ($usuarios as $usuario) {
            User::create($usuario);
        }
    }
}
