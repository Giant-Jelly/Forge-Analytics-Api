<?php

namespace App\Entity;

use App\Entity\Traits\TimestampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SupportTicketRepository")
 * @ORM\Table(name="support_ticket")
 * @ORM\HasLifecycleCallbacks()
 */
class SupportTicket
{
    const STATUS_NEW = 1;
    const STATUS_AGENT = 2;
    const STATUS_CUSTOMER = 3;
    const STATUS_CLOSED = 4;

    const TOPIC_ACCOUNT = 1;
    const TOPIC_PAYMENT = 2;
    const TOPIC_REPORT = 3;
    const TOPIC_WEBSITE = 4;
    const TOPIC_OTHER = 5;

    const STATUSES = [
        self::STATUS_NEW => 'New Support Ticket',
        self::STATUS_AGENT => 'Awaiting Agent apiResponse',
        self::STATUS_CUSTOMER => 'Awaiting Customer apiResponse',
        self::STATUS_CLOSED => 'Support Ticket Closed'
    ];

    const TOPICS = [
        self::TOPIC_ACCOUNT => 'A problem with my account',
        self::TOPIC_PAYMENT => 'A problem with payment',
        self::TOPIC_REPORT => 'A problem with a coach or player',
        self::TOPIC_WEBSITE => 'A problem with the website',
        self::TOPIC_OTHER => 'A problem with something else',

    ];

    use TimestampTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="supportTickets")
     */
    private $user;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $title;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $topic;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $ticketNumber;

    /**
     * @var SupportMessage
     * @ORM\OneToMany(targetEntity="SupportMessage", mappedBy="supportTicket")
     */
    private $supportMessages;

    /**
     * Support Ticket constructor.
     */
    public function __construct()
    {
        $this->supportMessages = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
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
     * @return SupportTicket
     */
    public function setUser(User $user): SupportTicket
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return SupportTicket
     */
    public function setStatus(int $status): SupportTicket
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return SupportTicket
     */
    public function setTitle(string $title): SupportTicket
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return int
     */
    public function getTopic(): int
    {
        return $this->topic;
    }

    /**
     * @param int $topic
     * @return SupportTicket
     */
    public function setTopic(int $topic): SupportTicket
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * @return string
     */
    public function getTicketNumber(): string
    {
        return $this->ticketNumber;
    }

    /**
     * @param string $ticketNumber
     * @return SupportTicket
     */
    public function setTicketNumber(string $ticketNumber): SupportTicket
    {
        $this->ticketNumber = $ticketNumber;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getSupportMessages(): Collection
    {
        return $this->supportMessages;
    }

    /**
     * @param Collection $supportMessages
     * @return SupportTicket
     */
    public function setSupportMessages(Collection $supportMessages): SupportTicket
    {
        $this->supportMessages = $supportMessages;

        return $this;
    }

    /**
     * @return string
     */
    public function getUniqueId(): string
    {
        return $this->ticketNumber.$this->id;
    }
}
