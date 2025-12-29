<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert about page default values
        DB::table('settings')->insert([
            [
                'key' => 'about_hero_title',
                'value' => 'Sobre a MozCommodities',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'about_hero_subtitle',
                'value' => 'Conectando produtores e compradores de commodities agrícolas em Moçambique',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'about_intro_text',
                'value' => 'A MozCommodities é uma empresa moçambicana líder na comercialização de produtos agrícolas. Fundada com a missão de revolucionar o mercado agrícola em Moçambique, conectamos produtores locais a compradores, garantindo qualidade, transparência e os melhores preços.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'about_mission_title',
                'value' => 'Nossa Missão',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'about_mission_text',
                'value' => 'Facilitar o acesso a produtos agrícolas de qualidade, conectando produtores e compradores através de uma plataforma transparente, eficiente e acessível, contribuindo para o desenvolvimento do setor agrícola moçambicano.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'about_vision_title',
                'value' => 'Nossa Visão',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'about_vision_text',
                'value' => 'Ser a principal plataforma de comercialização de commodities agrícolas em Moçambique e na região da África Austral, reconhecida pela excelência, inovação e impacto positivo nas comunidades agrícolas.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'about_values_title',
                'value' => 'Nossos Valores',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'about_value_1_title',
                'value' => 'Qualidade',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'about_value_1_text',
                'value' => 'Garantimos produtos de alta qualidade, certificados e inspecionados para a satisfação dos nossos clientes.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'about_value_2_title',
                'value' => 'Transparência',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'about_value_2_text',
                'value' => 'Operamos com total transparência em preços, origem dos produtos e processos de comercialização.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'about_value_3_title',
                'value' => 'Compromisso',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'about_value_3_text',
                'value' => 'Estamos comprometidos com o sucesso dos nossos parceiros, fornecedores e clientes.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'about_value_4_title',
                'value' => 'Inovação',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'about_value_4_text',
                'value' => 'Buscamos constantemente novas formas de melhorar nossos serviços e a experiência dos utilizadores.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'about_team_title',
                'value' => 'Nossa Equipa',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'about_team_text',
                'value' => 'Contamos com uma equipa dedicada de profissionais apaixonados pelo setor agrícola e comprometidos em oferecer o melhor serviço aos nossos clientes e parceiros.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'about_stats_products',
                'value' => '500+',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'about_stats_suppliers',
                'value' => '100+',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'about_stats_clients',
                'value' => '1000+',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'about_stats_years',
                'value' => '5+',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'about_hero_title',
            'about_hero_subtitle',
            'about_intro_text',
            'about_mission_title',
            'about_mission_text',
            'about_vision_title',
            'about_vision_text',
            'about_values_title',
            'about_value_1_title',
            'about_value_1_text',
            'about_value_2_title',
            'about_value_2_text',
            'about_value_3_title',
            'about_value_3_text',
            'about_value_4_title',
            'about_value_4_text',
            'about_team_title',
            'about_team_text',
            'about_stats_products',
            'about_stats_suppliers',
            'about_stats_clients',
            'about_stats_years',
        ])->delete();
    }
};
