<?php

declare(strict_types=1);

namespace WayOfDev\DatabaseSeeder;

use WayOfDev\DatabaseSeeder\Contracts\ConfigRepository;
use WayOfDev\DatabaseSeeder\Exceptions\MissingRequiredAttributes;

use function array_diff;
use function array_keys;
use function implode;

final class Config implements ConfigRepository
{
    public const DEFAULT_SEEDERS_DIR = 'database' . DIRECTORY_SEPARATOR . 'Seeders';

    public const DEFAULT_SEEDERS_NAMESPACE = 'Database\\Seeders';

    public const DEFAULT_FACTORIES_DIR = 'database' . DIRECTORY_SEPARATOR . 'Factories';

    public const DEFAULT_FACTORIES_NAMESPACE = 'Database\\Factories';

    private const REQUIRED_FIELDS = [
        'seeders_directory',
        'seeders_namespace',
        'factories_directory',
        'factories_namespace',
    ];

    public function __construct(
        private readonly string $seedersDirectory,
        private readonly string $seedersNamespace,
        private readonly string $factoriesDirectory,
        private readonly string $factoriesNamespace
    ) {
    }

    public static function fromArray(array $config): self
    {
        $missingAttributes = array_diff(self::REQUIRED_FIELDS, array_keys($config));

        if ($missingAttributes !== []) {
            throw MissingRequiredAttributes::fromArray(
                implode(',', $missingAttributes)
            );
        }

        return new self(
            $config['seeders_directory'],
            $config['seeders_namespace'],
            $config['factories_directory'],
            $config['factories_namespace']
        );
    }

    public function seedersDirectory(): string
    {
        return $this->seedersDirectory;
    }

    public function seedersNamespace(): string
    {
        return $this->seedersNamespace;
    }

    public function factoriesDirectory(): string
    {
        return $this->factoriesDirectory;
    }

    public function factoriesNamespace(): string
    {
        return $this->factoriesNamespace;
    }
}
