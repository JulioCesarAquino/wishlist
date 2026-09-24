<?php

namespace Database\Seeders;

use App\Models\Catalog\ProductTemplate;
use Illuminate\Database\Seeder;

class ProductTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            ['category' => 'Cozinha', 'name' => 'Jogo de panelas', 'suggested_price' => 450.00, 'description' => 'Jogo de panelas antiaderentes, 5 peças.'],
            ['category' => 'Cozinha', 'name' => 'Liquidificador', 'suggested_price' => 220.00, 'description' => 'Liquidificador de alta potência com copo de vidro.'],
            ['category' => 'Cozinha', 'name' => 'Jogo de facas', 'suggested_price' => 180.00, 'description' => 'Jogo de facas em aço inoxidável com suporte.'],
            ['category' => 'Cozinha', 'name' => 'Air fryer', 'suggested_price' => 350.00, 'description' => 'Fritadeira elétrica sem óleo.'],
            ['category' => 'Cozinha', 'name' => 'Jogo de panelas de pressão', 'suggested_price' => 300.00, 'description' => 'Panela de pressão elétrica multifuncional.'],
            ['category' => 'Cozinha', 'name' => 'Jogo de taças', 'suggested_price' => 120.00, 'description' => 'Jogo de taças de cristal para vinho, 6 peças.'],
            ['category' => 'Casa', 'name' => 'Jogo de cama casal', 'suggested_price' => 280.00, 'description' => 'Jogo de cama casal 4 peças, 100% algodão.'],
            ['category' => 'Casa', 'name' => 'Edredom casal', 'suggested_price' => 320.00, 'description' => 'Edredom casal dupla face.'],
            ['category' => 'Casa', 'name' => 'Jogo de toalhas', 'suggested_price' => 150.00, 'description' => 'Jogo de toalhas de banho, 4 peças.'],
            ['category' => 'Casa', 'name' => 'Aspirador de pó robô', 'suggested_price' => 900.00, 'description' => 'Robô aspirador com mapeamento automático.'],
            ['category' => 'Casa', 'name' => 'Jogo de panelas para churrasco', 'suggested_price' => 200.00, 'description' => 'Kit completo de utensílios para churrasco.'],
            ['category' => 'Eletrônicos', 'name' => 'Smart TV', 'suggested_price' => 1800.00, 'description' => 'Smart TV 50 polegadas 4K.'],
            ['category' => 'Eletrônicos', 'name' => 'Caixa de som Bluetooth', 'suggested_price' => 250.00, 'description' => 'Caixa de som portátil à prova d\'água.'],
            ['category' => 'Enxoval', 'name' => 'Kit enxoval bebê', 'suggested_price' => 400.00, 'description' => 'Kit com body, mantas e toalhas para o bebê.'],
            ['category' => 'Enxoval', 'name' => 'Berço', 'suggested_price' => 700.00, 'description' => 'Berço de madeira com colchão incluso.'],
            ['category' => 'Contribuição', 'name' => 'Cota - Lua de mel', 'suggested_price' => 100.00, 'description' => 'Contribuição para a viagem de lua de mel.'],
            ['category' => 'Contribuição', 'name' => 'Cota - Reforma da casa', 'suggested_price' => 100.00, 'description' => 'Contribuição para reformas e móveis da nova casa.'],
            ['category' => 'Contribuição', 'name' => 'Cota - Enxoval do bebê', 'suggested_price' => 50.00, 'description' => 'Contribuição para o enxoval do bebê.'],
            ['category' => 'Decoração', 'name' => 'Jogo de quadros decorativos', 'suggested_price' => 90.00, 'description' => 'Conjunto de quadros decorativos para sala.'],
            ['category' => 'Decoração', 'name' => 'Luminária de mesa', 'suggested_price' => 110.00, 'description' => 'Luminária de mesa moderna.'],
        ];

        foreach ($templates as $template) {
            ProductTemplate::query()->updateOrCreate(
                ['name' => $template['name']],
                $template,
            );
        }
    }
}
