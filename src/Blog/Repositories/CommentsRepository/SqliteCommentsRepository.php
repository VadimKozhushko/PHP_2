<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository;

use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Blog\Exceptions\CommentsNotFoundException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Comments;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\UUID;


class SqliteCommentsRepository /*implements CommentsRepositoryInterface*/
{
  private \PDO $connection;

  public function __construct(\PDO $connection)
  {
    $this->connection = $connection;
  }

  public function save(Comments $Comments): void
  {
    $statement = $this->connection->prepare(
      'INSERT INTO comments (uuid, author_uuid, title, text) VALUES (:uuid, :author_uuid, :title, :text)'
    );

    $statement->execute([
      ':uuid' => $Comments->uuid(),
      ':author_uuid' => $Comments->getUser()->uuid(),
      ':text' => $Comments->getText()
    ]);
  }


  /**
   * @throws CommentsNotFoundException
   * @throws UserNotFoundException
   * @throws InvalidArgumentException
   */
  public function get(UUID $uuid): Comments
  {
    $statement = $this->connection->prepare(
      'SELECT * FROM Commentss WHERE uuid = :uuid'
    );
    $statement->execute([
      ':uuid' => (string)$uuid,
    ]);

    return $this->getComments($statement, $uuid);
  }

  /**
   * @throws CommentsNotFoundException
   * @throws InvalidArgumentException|UserNotFoundException
   */
  private function getComments(\PDOStatement $statement, string $CommentsUuId): Comments
  {
    $result = $statement->fetch(\PDO::FETCH_ASSOC);

    if ($result === false) {
      throw new CommentsNotFoundException(
        "Cannot find Comments: $CommentsUuId"
      );
    }

    $userRepository = new SqliteUsersRepository($this->connection);
    $user = $userRepository->get(new UUID($result['author_uuid']));

    return new Comments(
      new UUID($result['uuid']),
      $user,
      $result['title'],
      $result['text']
    );
  }

  public function delete(UUID $uuid): void
  {
    $statement = $this->connection->prepare(
      'DELETE FROM comments WHERE Comments.uuid=:uuid;'
    );

    $statement->execute([
      ':uuid' => $uuid,
    ]);
  }
}
