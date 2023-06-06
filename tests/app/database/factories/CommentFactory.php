<?php

declare(strict_types=1);

namespace WayOfDev\DatabaseSeeder\Database\Factories;

use DateTimeImmutable;
use WayOfDev\DatabaseSeeder\App\Entities\Comment;
use WayOfDev\DatabaseSeeder\Factories\AbstractFactory;

class CommentFactory extends AbstractFactory
{
    public function entity(): string
    {
        return Comment::class;
    }

    public function makeEntity(array $definition): object
    {
        return new Comment(
            $definition['text']
        );
    }

    public function definition(): array
    {
        return [
            'text' => $this->faker->randomHtml(),
            'author' => UserFactory::new()->makeOne(),
            'postedAt' => DateTimeImmutable::createFromMutable($this->faker->dateTime()),
        ];
    }
}
