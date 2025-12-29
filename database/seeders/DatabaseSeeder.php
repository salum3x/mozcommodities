<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Criar Admin
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@agri.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '+258 84 000 0000',
        ]);

        // Criar Categorias
        $categories = [
            ['name' => 'Cereais', 'slug' => 'cereais', 'description' => 'Milho, trigo, arroz, etc.'],
            ['name' => 'Oleaginosas', 'slug' => 'oleaginosas', 'description' => 'Gergelim, amendoim, girassol, etc.'],
            ['name' => 'Leguminosas', 'slug' => 'leguminosas', 'description' => 'Feijão, grão-de-bico, lentilhas, etc.'],
            ['name' => 'Tubérculos', 'slug' => 'tuberculos', 'description' => 'Mandioca, batata, inhame, etc.'],
        ];

        foreach ($categories as $cat) {
            \App\Models\Category::create($cat);
        }

        // Criar Fornecedores
        for ($i = 1; $i <= 3; $i++) {
            $user = User::create([
                'name' => "Fornecedor $i",
                'email' => "fornecedor$i@agri.com",
                'password' => bcrypt('password'),
                'role' => 'supplier',
                'phone' => "+258 84 000 000$i",
            ]);

            $supplier = \App\Models\Supplier::create([
                'user_id' => $user->id,
                'company_name' => "Empresa Agrícola $i Lda",
                'description' => "Fornecedor de produtos agrícolas de qualidade",
                'status' => 'approved',
            ]);

            // Criar produtos para cada fornecedor
            // Gergelim com preços diferentes
            \App\Models\Product::create([
                'supplier_id' => $supplier->id,
                'category_id' => 2, // Oleaginosas
                'name' => 'Gergelim',
                'slug' => "gergelim-$i",
                'description' => 'Gergelim de alta qualidade',
                'price_per_kg' => 150 + ($i * 50), // Preços: 200, 250, 300
                'unit' => 'kg',
                'is_active' => true,
            ]);

            // Milho
            \App\Models\Product::create([
                'supplier_id' => $supplier->id,
                'category_id' => 1, // Cereais
                'name' => 'Milho',
                'slug' => "milho-$i",
                'description' => 'Milho amarelo de primeira qualidade',
                'price_per_kg' => 30 + ($i * 10),
                'unit' => 'kg',
                'is_active' => true,
            ]);

            // Feijão
            \App\Models\Product::create([
                'supplier_id' => $supplier->id,
                'category_id' => 3, // Leguminosas
                'name' => 'Feijão Manteiga',
                'slug' => "feijao-manteiga-$i",
                'description' => 'Feijão manteiga tipo 1',
                'price_per_kg' => 80 + ($i * 20),
                'unit' => 'kg',
                'is_active' => true,
            ]);
        }

        // Criar Cliente
        User::create([
            'name' => 'Cliente Teste',
            'email' => 'cliente@agri.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'phone' => '+258 84 999 9999',
        ]);
    }
}
