<?php

declare(strict_types=1);

namespace WayOfDev\DatabaseSeeder\Contracts;

use Illuminate\Support\Collection;

interface FactoryInterface
{
    public static function new(): static;

    public function entity(): string;

    public function definition(): array;

    public function times(int $amount): self;

    public function create(): Collection;

    public function createOne(): object;

    public function make(): Collection;

    public function makeOne(): object;
}
