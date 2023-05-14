<?php

namespace Geekbrains\LevelTwo\Http\Actions\Post;

use Geekbrains\LevelTwo\Blog\Exceptions\HttpException;
use Geekbrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use Geekbrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use Geekbrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use Geekbrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use Geekbrains\LevelTwo\Blog\UUID;
use Geekbrains\LevelTwo\Http\Actions\ActionInterface;
use Geekbrains\LevelTwo\Http\Request;
use Geekbrains\LevelTwo\Http\Response;
use Geekbrains\LevelTwo\Http\ErrorResponse;
use Geekbrains\LevelTwo\Http\SuccessfulResponse;

class CreatePost implements ActionInterface
{
  public function __construct(
    private PostsRepositoryInterface $postsRepository,
    private UsersRepositoryInterface $usersRepository,
  ) {
  }

  public function handle(Request $request): Response
  {
    // Пытаемся создать UUID пользователя из данных запроса
    try {
      $authorUuid = new UUID($request->jsonBodyField("author_uuid"));
    } catch (HttpException | InvalidArgumentException $e) {
      return new ErrorResponse($e->getMessage());
    }
    // Пытаемся найти пользователя в реeпозитории
    try {
      $user = $this->usersRepository->get($authorUuid);
    } catch (UserNotFoundException $e) {
      return new ErrorResponse($e->getMessage());
    }
    // Генерируем UUID для новой статьи
    $newPostUuid = UUID::random();
    try {
      // Пытаемся создать объект статьи
      // из данных запроса
      $post = new Post(
        $newPostUuid,
        $user,
        $request->jsonBodyField('title'),
        $request->jsonBodyField('text'),
      );
    } catch (HttpException $e) {
      return new ErrorResponse($e->getMessage());
    }
    // Сохраняем новую статью в репозитории
    $this->postsRepository->save($post);
    // Возвращаем успешный ответ,
    // содержащий UUID новой статьи
    return new SuccessfulResponse([
      'uuid' => (string)$newPostUuid,
    ]);
  }
}
