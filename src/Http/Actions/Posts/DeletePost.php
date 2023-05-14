<?php

namespace Geekbrains\LevelTwo\Http\Actions\Post;

use Geekbrains\LevelTwo\Blog\Exceptions\AppException;
use Geekbrains\LevelTwo\Blog\Exceptions\CommandException;
use Geekbrains\LevelTwo\Blog\Exceptions\HttpException;
use Geekbrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use Geekbrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use Geekbrains\LevelTwo\Blog\UUID;
use Geekbrains\LevelTwo\Http\Actions\ActionInterface;
use Geekbrains\LevelTwo\Http\ErrorResponse;
use Geekbrains\LevelTwo\Http\Request;
use Geekbrains\LevelTwo\Http\Response;
use Geekbrains\LevelTwo\Http\SuccessfulResponse;

class DeletePost implements ActionInterface
{


  public function __construct(
    private PostsRepositoryInterface $postsRepository
  ) {
  }

  /**
   * @throws HttpException
   * @throws InvalidArgumentException
   */
  public function handle(Request $request): Response
  {
    try {
      $uuid = $request->jsonBodyField('uuid');
    } catch (HttpException $e) {
      return new ErrorResponse($e->getMessage());
    }


    $this->postsRepository->delete(new UUID($uuid));

    return new SuccessfulResponse([
      'delete' => 'true',
      'uuid' => (string)$uuid
    ]);
  }
}
