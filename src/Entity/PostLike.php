<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Like
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\LikeRepository")
 */
class PostLike
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    /**
     * @var Post
     * @ORM\ManyToOne(targetEntity="App\Entity\Post", inversedBy="likes")
     */
    private $post;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return PostLike
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
     * @return PostLike
     */
    public function setUser(User $user): PostLike
    {
        $this->user = $user;
        return $this;
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
     * @return PostLike
     */
    public function setPost(Post $post): PostLike
    {
        $this->post = $post;
        return $this;
    }
}
