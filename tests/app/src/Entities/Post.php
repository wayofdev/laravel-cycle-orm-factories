<?php

declare(strict_types=1);

namespace WayOfDev\DatabaseSeeder\App\Entities;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\Annotated\Annotation\Relation\HasMany;
use DateTimeImmutable;
use Illuminate\Support\Collection;

#[Entity(table: 'posts')]
class Post
{
    #[Column(type: 'primary')]
    public int $id;

    #[BelongsTo(target: User::class, fkCreate: false)]
    public User $author;

    #[Column(type: 'datetime')]
    public DateTimeImmutable $publishedAt;

    #[HasMany(target: Comment::class, innerKey: 'id', outerKey: 'post_id', fkCreate: false)]
    public Collection $comments;

    public function __construct(
        #[Column(type: 'text')] public string $content
    ) {
    }
}
