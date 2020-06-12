<?php

namespace App\Entity;

use App\Entity\Traits\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SupportMessageRepository")
 * @ORM\Table(name="support_message")
 * @ORM\HasLifecycleCallbacks()
 */
class SupportMessage
{
    use TimestampTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="supportMessages")
     */
    private $user;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @var SupportTicket
     * @ORM\ManyToOne(targetEntity="SupportTicket", inversedBy="supportMessages")
     */
    private $supportTicket;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * @return SupportMessage
     */
    public function setUser(User $user): SupportMessage
    {
        $this->user = $user;

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
     * @return SupportMessage
     */
    public function setText(string $text): SupportMessage
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return SupportTicket
     */
    public function getSupportTicket(): SupportTicket
    {
        return $this->supportTicket;
    }

    /**
     * @param SupportTicket $supportTicket
     * @return SupportMessage
     */
    public function setSupportTicket(SupportTicket $supportTicket): SupportMessage
    {
        $this->supportTicket = $supportTicket;

        return $this;
    }
}
