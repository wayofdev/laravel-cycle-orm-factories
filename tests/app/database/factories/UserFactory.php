<?php

declare(strict_types=1);

namespace WayOfDev\DatabaseSeeder\Database\Factories;

use DateTimeImmutable;
use WayOfDev\DatabaseSeeder\App\Entities\User;
use WayOfDev\DatabaseSeeder\Factories\AbstractFactory;

final class UserFactory extends AbstractFactory
{
    public function entity(): string
    {
        return User::class;
    }

    public function makeEntity(array $definition): User
    {
        return new User($definition['firstName'], $definition['lastName']);
    }

    public function admin(): self
    {
        return $this->state(fn () => [
            'admin' => true,
        ]);
    }

    public function guest(): self
    {
        return $this->state(fn () => [
            'admin' => false,
        ]);
    }

    public function fromCity(string $city): self
    {
        return $this->state(fn () => [
            'city' => $city,
        ]);
    }

    public function birthday(DateTimeImmutable $date): self
    {
        return $this->entityState(static function (User $user) use ($date) {
            $user->birthday = $date;

            return $user;
        });
    }

    public function definition(): array
    {
        return [
            'firstName' => $this->faker->firstName(),
            'lastName' => $this->faker->lastName(),
            'birthday' => DateTimeImmutable::createFromMutable($this->faker->dateTime()),
            'age' => $this->faker->numberBetween(1, 90),
            'active' => $this->faker->boolean,
            'someFloatVal' => $this->faker->randomFloat(),
        ];
    }
}
