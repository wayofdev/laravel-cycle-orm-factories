<?php

declare(strict_types=1);

namespace WayOfDev\DatabaseSeeder\Tests;

use DateTimeImmutable;
use WayOfDev\DatabaseSeeder\App\Entities\Comment;
use WayOfDev\DatabaseSeeder\App\Entities\Post;
use WayOfDev\DatabaseSeeder\App\Entities\User;
use WayOfDev\DatabaseSeeder\Database\Factories\CommentFactory;
use WayOfDev\DatabaseSeeder\Database\Factories\PostFactory;
use WayOfDev\DatabaseSeeder\Database\Factories\UserFactory;
use WayOfDev\DatabaseSeeder\Exceptions\FactoryException;

use function array_key_first;
use function array_key_last;

class FactoryTest extends TestCase
{
    /**
     * @test
     */
    public function create_entity(): void
    {
        $user = UserFactory::new()->makeOne();
        $post = PostFactory::new()->makeOne();
        $comment = CommentFactory::new()->makeOne();

        $this::assertInstanceOf(User::class, $user);
        $this::assertInstanceOf(DateTimeImmutable::class, $user->birthday);
        $this::assertInstanceOf(Post::class, $post);
        $this::assertCount(3, $post->comments);
        $this::assertInstanceOf(Comment::class, $comment);
    }

    /**
     * @test
     */
    public function create_multiple(): void
    {
        $users = UserFactory::new()->times(2)->make();

        $this::assertCount(2, $users);

        // with different data
        $first = $users[array_key_first($users)];
        $second = $users[array_key_last($users)];

        $this::assertNotEquals($first->firstName, $second->firstName);
        $this::assertNotEquals($first->lastName, $second->lastName);
    }

    /**
     * @test
     */
    public function create_nullable_not_filled(): void
    {
        $user = UserFactory::new()->makeOne();

        $this::assertNull($user->city);
    }

    /**
     * @test
     */
    public function after_make_callback(): void
    {
        $post = PostFactory::new()->afterMake(fn (Post $post) => $post->content = 'changed by callback')->makeOne();

        $this::assertSame('changed by callback', $post->content);
    }

    /**
     * @test
     */
    public function create_with_replaces(): void
    {
        $post = PostFactory::new(['content' => 'changed by replaces array'])->makeOne();

        $this::assertSame('changed by replaces array', $post->content);
    }

    /**
     * @test
     */
    public function raw_data(): void
    {
        $post = PostFactory::new()->data;
        $post2 = PostFactory::new()->data;

        // @phpstan-ignore-next-line
        $this::assertIsArray($post);

        // @phpstan-ignore-next-line
        $this::assertIsArray($post2);

        $this::assertNotSame($post['content'], $post2['content']);
    }

    /**
     * @test
     */
    public function undefined_property(): void
    {
        $this->expectException(FactoryException::class);
        // @phpstan-ignore-next-line
        PostFactory::new()->test;
    }

    /**
     * @test
     */
    public function states(): void
    {
        $admin = UserFactory::new()->admin()->makeOne();
        $this::assertTrue($admin->admin);

        $guest = UserFactory::new()->guest()->makeOne();
        $this::assertFalse($guest->admin);

        $userFromNewYork = UserFactory::new()->fromCity('New York')->makeOne();
        $this::assertSame('New York', $userFromNewYork->city);

        $user = UserFactory::new()
            ->birthday($date = new DateTimeImmutable('2010-01-01 00:00:00'))
            ->makeOne();

        $this::assertSame($date, $user->birthday);
    }
}
