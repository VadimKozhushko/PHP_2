<?php

namespace GeekBrains\LevelTwo\Blog;

class Likes
{

  public function __construct(
    private UUID $uuid,
    private User $user,
    private Post $post,
    private string $like
  ) {
  }

  /**
   * @return UUID
   */
  public function uuid(): UUID
  {
    return $this->uuid;
  }

  /**
   * @return User
   */
  public function getUser(): User
  {
    return $this->user;
  }

  /**
   * @param User $user
   */
  public function setUser(User $user): void
  {
    $this->user = $user;
  }

  /**
   * @return Post
   */
  public function getPost(): Post
  {
    return $this->post;
  }

  /**
   * @param Post $post
   */
  public function setPost(Post $post): void
  {
    $this->post = $post;
  }

  /**
   * @return string
   */
  public function getlike(): string
  {
    return $this->like;
  }

  /**
   * @param string $like
   */
  public function setlike(string $like): void
  {
    $this->like = $like;
  }

  public function __toString()
  {
    return $this->user . " ствит лайк " . $this->like . " этому посту " . $this->post;
  }
}
