<?php

declare(strict_types=1);

namespace WayOfDev\DatabaseSeeder\Contracts;

interface ConfigRepository
{
    public function seedersDirectory(): string;

    public function seedersNamespace(): string;

    public function factoriesDirectory(): string;

    public function factoriesNamespace(): string;
}
