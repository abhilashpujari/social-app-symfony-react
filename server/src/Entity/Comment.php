<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * The fields that aren't mass assignable.
     *
     * @var array
     */
    const GUARDED_FIELDS = ['creationDate', 'parent', 'post', 'user', 'reply'];

    /**
     * The fields that should be hidden.
     *
     * @var array
     */
    const HIDDEN_FIELDS = ['post', 'parent'];

    /**
     * @ORM\Column(type="text")
     */
    private $comment;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="parent")
     * @var ArrayCollection
     */
    protected $reply;

    /**
     * @ORM\ManyToOne(targetEntity="Comment", inversedBy="reply")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     * @var Comment
     */
    private $parent;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id", onDelete="CASCADE")
     * @var Post
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     * @var Comment
     */
    private $user;

    /**
     * Comment constructor.
     */
    public function __construct()
    {
        $this->creationDate = new DateTime();
        $this->reply= new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return ArrayCollection
     */
    public function getReply()
    {
        return $this->reply;
    }

    /**
     * @param ArrayCollection $reply
     */
    public function setReply(ArrayCollection $reply)
    {
        $this->reply= $reply;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getCreationDate(): string
    {
        return $this->creationDate->format('c');
    }


    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
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
}
