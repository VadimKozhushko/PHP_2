<?php
require_once __DIR__ .  "/vendor/autoload.php";

use Geekbrains\LevelTwo\Blog\Commands\Arguments;
use Geekbrains\LevelTwo\Blog\Commands\CreateCommentCommand;
use Geekbrains\LevelTwo\Blog\Commands\CreatePostCommand;
use Geekbrains\LevelTwo\Blog\Commands\CreateUserCommand;
use Geekbrains\LevelTwo\Blog\Commands\OtherArguments;
use Geekbrains\LevelTwo\Blog\Comment;
use Geekbrains\LevelTwo\Blog\Post;
use Geekbrains\LevelTwo\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use Geekbrains\LevelTwo\Blog\Repositories\PostRepositories\SqlitePostsRepository;
use Geekbrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use \Geekbrains\LevelTwo\Blog\Exceptions\CommandException;
use Geekbrains\LevelTwo\Blog\UUID;
use \Geekbrains\LevelTwo\Blog\Exceptions\ArgumentsException;
use Geekbrains\LevelTwo\Blog\User;
use Geekbrains\LevelTwo\Person\Name;

$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');
$usersRepository = new SqliteUsersRepository($connection);


try {

    $postRepository->delete(new UUID('02000775-c963-43a3-b644-36bf95caf7c4'));
    $command = new CreateUserCommand($usersRepository);
    $command->handle(Arguments::fromArgv($argv));

    $user = new User(new UUID(UUID::random()), new Name('Lev', 'Petrushin'), 'lev2022');
    $post = $postCommand->get(new UUID('acbc10fe-78a2-4c47-8833-8ebbc34a9bcb'));
    var_dump($post);
    $user = new User(new UUID(UUID::random()), new Name('Lev', 'Petrushin'), 'lev2022');

    $post = $postCommand->getPost(new UUID('acbc10fe-78a2-4c47-8833-8ebbc34a9bcb'));
} catch (CommandException $ex) {
    echo $ex->getMessage();
} catch (ArgumentsException $e) {
    echo $e->getMessage();
}
