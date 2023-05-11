<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository;

use GeekBrains\LevelTwo\Blog\Comments;
use GeekBrains\LevelTwo\Blog\UUID;

interface CommentsRepositoryInterface
{
  public function save(Comments $post): void;
  public function get(UUID $uuid): Comments;
  public function delete(UUID $uuid): void;
}
