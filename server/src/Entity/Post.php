<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post
{
    /**
     * The fields that aren't mass assignable.
     *
     * @var array
     */
    const GUARDED_FIELDS = ['creationDate', 'user'];

    /**
     * The fields that should be hidden.
     *
     * @var array
     */
    const HIDDEN_FIELDS = [];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PostLike", mappedBy="post")
     */
    private $likes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     * @var User
     */
    private $user;

    /**
     * Post constructor.
     */
    public function __construct()
    {
        $this->creationDate = new DateTime();
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreationDate(): string
    {
        return $this->creationDate->format('c');
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getLikes()
    {
        return $this->likes;
    }

    public function getLikeCount()
    {
        $totalLikes = 0;
        if ($this->likes) {
            foreach($this->likes as $like) {
                if ($like->getIsLiked()) {
                    $totalLikes += 1;
                }
            }
        }

        return $totalLikes;
    }

    public function getDislikeCount()
    {
        $totalDislikes = 0;
        if ($this->likes) {
            foreach($this->likes as $like) {
                if (!$like->getIsLiked()) {
                    $totalDislikes += 1;
                }
            }
        }

        return $totalDislikes;
    }
}