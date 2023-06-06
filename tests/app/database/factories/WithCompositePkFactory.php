<?php

declare(strict_types=1);

namespace WayOfDev\DatabaseSeeder\Database\Factories;

use WayOfDev\DatabaseSeeder\App\Entities\WithCompositePk;
use WayOfDev\DatabaseSeeder\Factories\AbstractFactory;

class WithCompositePkFactory extends AbstractFactory
{
    public function entity(): string
    {
        return WithCompositePk::class;
    }

    public function definition(): array
    {
        return [
            'id' => $this->faker->randomDigit(),
            'otherId' => $this->faker->randomDigit(),
            'content' => $this->faker->sentence,
        ];
    }

    public function makeEntity(array $definition): object
    {
        return new WithCompositePk();
    }
}
