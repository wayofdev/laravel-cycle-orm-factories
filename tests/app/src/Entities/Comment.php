<?php

declare(strict_types=1);

namespace WayOfDev\DatabaseSeeder\App\Entities;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use DateTimeImmutable;

#[Entity(table: 'comments')]
class Comment
{
    #[Column(type: 'primary')]
    public int $id;

    #[BelongsTo(target: User::class, fkCreate: false)]
    public User $author;

    #[BelongsTo(target: Post::class, fkCreate: false)]
    public Post $post;

    #[Column(type: 'datetime')]
    public DateTimeImmutable $postedAt;

    public function __construct(
        #[Column(type: 'text')] public string $text
    ) {
    }
}
