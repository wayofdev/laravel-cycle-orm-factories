<?php

declare(strict_types=1);

namespace WayOfDev\DatabaseSeeder\Factories;

use Butschster\EntityFaker\EntityFactory\ClosureStrategy;
use Butschster\EntityFaker\EntityFactory\InstanceWithoutConstructorStrategy;
use Butschster\EntityFaker\Factory;
use Butschster\EntityFaker\LaminasEntityFactory;
use Closure;
use Cycle\ORM\EntityManagerInterface;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Illuminate\Container\Container;
use Laminas\Hydrator\ReflectionHydrator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use WayOfDev\DatabaseSeeder\Contracts\FactoryInterface;
use WayOfDev\DatabaseSeeder\Exceptions\FactoryException;

use function array_is_list;
use function array_map;
use function array_shift;
use function is_array;

/**
 * @property array $data
 */
abstract class AbstractFactory implements FactoryInterface
{
    protected Generator $faker;

    /**
     * @internal
     */
    private Factory $entityFactory;

    /**
     * @psalm-var positive-int
     */
    private int $amount = 1;

    /**
     * @var array<Closure|callable>
     */
    private array $afterCreate = [];

    /**
     * @var array<Closure|callable>
     */
    private array $afterMake = [];

    /**
     * @var array<Closure>
     */
    private array $states = [];

    /**
     * @var array<Closure>
     */
    private array $entityStates = [];

    public static function new(array $replace = []): static
    {
        return new static($replace);
    }

    abstract public function makeEntity(array $definition): object;

    /** @psalm-return class-string */
    abstract public function entity(): string;

    abstract public function definition(): array;

    public function times(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function state(Closure $state): static
    {
        $this->states[] = $state;

        return $this;
    }

    public function entityState(Closure $state): static
    {
        $this->entityStates[] = $state;

        return $this;
    }

    public function afterCreate(callable $afterCreate): static
    {
        $this->afterCreate[] = $afterCreate;

        return $this;
    }

    public function afterMake(callable $afterMake): static
    {
        $this->afterMake[] = $afterMake;

        return $this;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function create(): array
    {
        $entities = $this->object(fn () => $this->definition());
        if (! is_array($entities)) {
            $entities = [$entities];
        }

        $this->storeEntities($entities);

        $this->callAfterCreating($entities);

        return $entities;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function createOne(): object
    {
        $entity = $this->object(fn () => $this->definition());
        if (is_array($entity)) {
            $entity = array_shift($entity);
        }

        $this->storeEntities([$entity]);

        $this->callAfterCreating([$entity]);

        return $entity;
    }

    public function make(): array
    {
        $entities = $this->object(fn () => $this->definition());
        if (! is_array($entities)) {
            $entities = [$entities];
        }

        return $entities;
    }

    public function makeOne(): object
    {
        $entity = $this->object(fn () => $this->definition());
        if (is_array($entity)) {
            $entity = array_shift($entity);
        }

        return $entity;
    }

    public function raw(Closure $definition): array
    {
        $this->entityFactory->define($this->entity(), $definition);

        $data = $this->entityFactory->of($this->entity())->times($this->amount)->raw($this->replaces);

        return array_is_list($data) ? $data[0] : $data;
    }

    public function __get(string $name): array
    {
        return match ($name) {
            'data' => $this->raw(fn () => $this->definition()),
            default => throw new FactoryException('Undefined magic property.')
        };
    }

    final private function __construct(
        private readonly array $replaces = []
    ) {
        $this->faker = FakerFactory::create();

        $this->entityFactory = new Factory(
            new LaminasEntityFactory(
                new ReflectionHydrator(),
                new InstanceWithoutConstructorStrategy()
            ),
            $this->faker
        );
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function storeEntities(array $entities): void
    {
        $container = Container::getInstance();

        /** @var EntityManagerInterface $em */
        $em = $container->get(EntityManagerInterface::class);

        foreach ($entities as $entity) {
            $em->persist($entity);
        }
        $em->run();
    }

    /** @internal */
    private function object(Closure $definition): object|array
    {
        $this->entityFactory
            ->creationStrategy(
                $this->entity(),
                new ClosureStrategy(fn (string $class, array $data) => $this->makeEntity($data))
            )
            ->define($this->entity(), $definition)
            ->states($this->entity(), $this->states);

        foreach ($this->afterMake as $afterMakeCallable) {
            $this->entityFactory->afterMaking($this->entity(), $afterMakeCallable);
        }

        $result = $this->entityFactory->of($this->entity())->times($this->amount)->make($this->replaces);

        if (is_array($result)) {
            return array_map(function (object $entity) {
                return $this->applyEntityState($entity);
            }, $result);
        }

        return $this->applyEntityState($result);
    }

    /** @internal */
    private function applyEntityState(object $entity): object
    {
        foreach ($this->entityStates as $state) {
            $entity = $state($entity);
        }

        return $entity;
    }

    /** @internal */
    private function callAfterCreating(array $entities): void
    {
        foreach ($entities as $entity) {
            array_map(static fn (callable $callable) => $callable($entity), $this->afterCreate);
        }
    }
}
