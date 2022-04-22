<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bill>
 */
class BillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'type_id'=> 1,
            'need' => random_int(0, 1),
            'title' => 'Test bill',
            'details' => 'test detail',
            'contacts'=>'tg: @contact',
            'city_id'=>1,
            'user_id'=>1,
        ];
    }
}
