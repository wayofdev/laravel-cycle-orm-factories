<?php

declare(strict_types=1);

namespace WayOfDev\DatabaseSeeder\Contracts;

interface FactoryInterface
{
    public static function new(): static;

    public function entity(): string;

    public function definition(): array;

    public function times(int $amount): self;

    public function create(): array;

    public function createOne(): object;

    public function make(): array;

    public function makeOne(): object;
}
