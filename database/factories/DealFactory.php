<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Deal;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Deal>
 */
class DealFactory extends Factory
{
    protected $model = Deal::class;

    public function definition()
    {
        $name = $this->faker->company;
        return [
            'id' => (string) Str::uuid(),
            'contact_id' => Contact::factory(),
            'title' => $this->faker->sentence(4),
            'amount'=> $this->faker->randomFloat(2,1000,1000000),
            'currency' => $this->faker->randomElement(['USD', 'EUR', 'GBP']),
            'status' => $this->faker->randomElement([
                Deal::STATUS_OPEN,
                Deal::STATUS_WON,
                Deal::STATUS_LOST
            ])
        ];
    }

    public function open()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Deal::STATUS_OPEN,
            ];
        });
    }

    public function won()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Deal::STATUS_WON,
            ];
        });
    }

    public function lost()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Deal::STATUS_LOST,
            ];
        });
    }
}
