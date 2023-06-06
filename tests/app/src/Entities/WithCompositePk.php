<?php

declare(strict_types=1);

namespace WayOfDev\DatabaseSeeder\App\Entities;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Table\PrimaryKey;

#[Entity(table: 'composite_pk')]
#[PrimaryKey(['id', 'other_id'])]
class WithCompositePk
{
    #[Column(type: 'integer')]
    public int $id;

    #[Column(type: 'integer', name: 'other_id')]
    public int $otherId;

    #[Column(type: 'text')]
    public string $content;
}
