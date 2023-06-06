<?php

declare(strict_types=1);

namespace WayOfDev\DatabaseSeeder\Database\Factories;

use DateTimeImmutable;
use WayOfDev\DatabaseSeeder\App\Entities\Post;
use WayOfDev\DatabaseSeeder\Factories\AbstractFactory;

class PostFactory extends AbstractFactory
{
    public function entity(): string
    {
        return Post::class;
    }

    public function makeEntity(array $definition): Post
    {
        return new Post(
            $definition['content']
        );
    }

    public function definition(): array
    {
        return [
            'content' => $this->faker->randomHtml(),
            'author' => UserFactory::new()->makeOne(),
            'publishedAt' => DateTimeImmutable::createFromMutable($this->faker->dateTime()),
            'comments' => CommentFactory::new()->times(3)->make(),
        ];
    }
}
