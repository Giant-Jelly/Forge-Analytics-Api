<?php

namespace App\Entity;

use App\Entity\Traits\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReviewRepository")
 * @ORM\Table(name="review")
 * @ORM\HasLifecycleCallbacks()
 */
class Review
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
     * @var string
     * @ORM\Column(type="string")
     */
    private $shortText;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $reviewer;

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Review
     */
    public function setText(string $text): Review
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getShortText(): string
    {
        return $this->shortText;
    }

    /**
     * @param string $shortText
     * @return Review
     */
    public function setShortText(string $shortText): Review
    {
        $this->shortText = $shortText;

        return $this;
    }

    /**
     * @return string
     */
    public function getReviewer(): string
    {
        return $this->reviewer;
    }

    /**
     * @param string $reviewer
     * @return Review
     */
    public function setReviewer(string $reviewer): Review
    {
        $this->reviewer = $reviewer;

        return $this;
    }
}
