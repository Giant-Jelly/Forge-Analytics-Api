<?php

namespace App\Entity;

use App\Entity\Traits\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderNoteRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class OrderNote
{

    use TimestampTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="notes")
     */
    private $author;

    /**
     * @var Order
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="notes")
     */
    private $order;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return OrderNote
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return OrderNote
     */
    public function setText(string $text): OrderNote
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param User $author
     * @return OrderNote
     */
    public function setAuthor(User $author): OrderNote
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }

    /**
     * @param Order $order
     * @return OrderNote
     */
    public function setOrder(Order $order): OrderNote
    {
        $this->order = $order;
        return $this;
    }
}