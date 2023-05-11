<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\LikesRepository;

use GeekBrains\LevelTwo\Blog\Likes;
use GeekBrains\LevelTwo\Blog\UUID;

interface LikesRepositoryInterface
{
  public function save(Likes $post): void;
  public function get(UUID $uuid): Likes;
  public function delete(UUID $uuid): void;
  public function getByPostUuid(Likes $post): Likes;
}
