<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\LikesRepository;

use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Blog\Exceptions\LikesNotFoundException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Likes;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\UUID;


class SqliteLikesRepository /*implements LikessRepositoryInterface*/
{
  private \PDO $connection;

  public function __construct(\PDO $connection)
  {
    $this->connection = $connection;
  }

  public function save(Likes $Likes): void
  {
    $statement = $this->connection->prepare(
      'INSERT INTO Likes (uuid, author_uuid, title, text) VALUES (:uuid, :author_uuid, :title, :text)'
    );

    $statement->execute([
      ':uuid' => $Likes->uuid(),
      ':author_uuid' => $Likes->getUser()->uuid(),
    ]);
  }


  /**
   * @throws LikesNotFoundException
   * @throws UserNotFoundException
   * @throws InvalidArgumentException
   */
  public function get(UUID $uuid): Likes
  {
    $statement = $this->connection->prepare(
      'SELECT * FROM Likess WHERE uuid = :uuid'
    );
    $statement->execute([
      ':uuid' => (string)$uuid,
    ]);

    return $this->getLikes($statement, $uuid);
  }

  /**
   * @throws LikesNotFoundException
   * @throws InvalidArgumentException|UserNotFoundException
   */
  private function getLikes(\PDOStatement $statement, string $LikesUuId): Likes
  {
    $result = $statement->fetch(\PDO::FETCH_ASSOC);

    if ($result === false) {
      throw new LikesNotFoundException(
        "Cannot find Likes: $LikesUuId"
      );
    }

    $userRepository = new SqliteUsersRepository($this->connection);
    $user = $userRepository->get(new UUID($result['author_uuid']));

    return new Likes(
      new UUID($result['uuid']),
      $user,
      $result['title'],
      $result['text']
    );
  }

  public function delete(UUID $uuid): void
  {
    $statement = $this->connection->prepare(
      'DELETE FROM Likes WHERE Likes.uuid=:uuid;'
    );

    $statement->execute([
      ':uuid' => $uuid,
    ]);
  }
}
