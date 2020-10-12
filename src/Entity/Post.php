<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Post
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Post
{

    /**
     * Post constructor.
     */
    public function __construct()
    {
        $this->likes = new ArrayCollection();
    }

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
     * @var string
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var PersistentCollection
     * @ORM\OneToMany(targetEntity="App\Entity\PostLike", mappedBy="post", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $likes;

    /**
     * @return PersistentCollection|PostLike[]
     */
    public function getLikes(): PersistentCollection
    {
        return $this->likes;
    }

    /**
     * @param PostLike $like
     * @return $this
     */
    public function addLike(PostLike $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setPost($this);
        }
        return $this;
    }

    /**
     * @param PostLike $like
     * @return $this
     */
    public function removeLike(PostLike $like): self
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Post
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
     * @return Post
     */
    public function setUser(User $user): Post
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return Post
     */
    public function setContent(string $content): Post
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     * @return Post
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTime();
    }

}
