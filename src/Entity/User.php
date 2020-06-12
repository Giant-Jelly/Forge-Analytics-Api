<?php

namespace App\Entity;

use App\Entity\Traits\TimestampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * Place Orders
     */
    const ROLE_USER = 1;

    /**
     * See order list for their games
     * Assign themselves to order for their own games
     * Manage orders for their own games
     * Add coach notes to users
     * Read coach notes
     * See their own Finances
     */
    const ROLE_COACH = 2;

    /**
     * Manage Coaches, Users
     * Answer support enquiries for their game
     * See Finances for all coaches in their game
     * Can see commission totals
     */
    const ROLE_HEAD_COACH = 3;

    /**
     * Can do everything, except manage admins
     */
    const ROLE_ADMIN = 4;

    /**
     * Manage admins
     */
    const ROLE_SUPER_ADMIN = 5;

    use TimestampTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $discordId;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $username;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $token;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $avatar;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Order", mappedBy="user")
     */
    private $orders;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $role = 1;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Order", mappedBy="coach")
     */
    private $assignedOrders;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $discriminator;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="SupportTicket", mappedBy="user")
     */
    private $supportTickets;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="SupportMessage", mappedBy="user")
     */
    private $supportMessages;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $onServer = false;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Payment", mappedBy="user")
     */
    private $coachPayments;

    /**
     * @var Affiliate
     * @ORM\OneToOne(targetEntity="Affiliate", mappedBy="user")
     */
    private $affiliate;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="OrderNote", mappedBy="author")
     */
    private $notes;

    /**
     * @var Coach
     * @ORM\OneToOne(targetEntity="Coach", mappedBy="user")
     */
    private $coach;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->assignedOrders = new ArrayCollection();
        $this->supportTickets = new ArrayCollection();
        $this->supportMessages = new ArrayCollection();
        $this->coachPayments = new ArrayCollection();
        $this->notes = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getDiscordId(): ?int
    {
        return $this->discordId;
    }

    /**
     * @param string $discordId
     * @return User
     */
    public function setDiscordId(string $discordId): User
    {
        $this->discordId = $discordId;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername(?string $username): User
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(?string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return User
     */
    public function setToken(string $token): User
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return string
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     * @return User
     */
    public function setAvatar(?string $avatar): User
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    /**
     * @param ArrayCollection $orders
     * @return User
     */
    public function setOrders(ArrayCollection $orders): User
    {
        $this->orders = $orders;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return int
     */
    public function getRole(): int
    {
        return $this->role;
    }

    /**
     * @param int $role
     * @return User
     */
    public function setRole(int $role): User
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getAssignedOrders(): Collection
    {
        return $this->assignedOrders;
    }

    /**
     * @param Collection $assignedOrders
     * @return User
     */
    public function setAssignedOrders(Collection $assignedOrders): User
    {
        $this->assignedOrders = $assignedOrders;

        return $this;
    }

    /**
     * @return string
     */
    public function getDiscriminator(): ?string
    {
        return $this->discriminator;
    }

    /**
     * @param string $discriminator
     * @return User
     */
    public function setDiscriminator(?string $discriminator): User
    {
        $this->discriminator = $discriminator;

        return $this;
    }

    /**
     * @return string
     */
    public function getFullUsername(): string
    {
        return $this->username.'#'.$this->discriminator;
    }

    /**
     * @return Collection
     */
    public function getSupportTickets(): Collection
    {
        return $this->supportTickets;
    }

    /**
     * @param ArrayCollection $supportTickets
     * @return User
     */
    public function setSupportTickets(ArrayCollection $supportTickets): User
    {
        $this->supportTickets = $supportTickets;

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
     * @param ArrayCollection $supportMessages
     * @return User
     */
    public function setSupportMessages(ArrayCollection $supportMessages): User
    {
        $this->supportMessages = $supportMessages;

        return $this;
    }

    /**
     * @return bool
     */
    public function isOnServer(): bool
    {
        return $this->onServer;
    }

    /**
     * @param bool $onServer
     * @return User
     */
    public function setOnServer(bool $onServer): User
    {
        $this->onServer = $onServer;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getCoachPayments(): Collection
    {
        return $this->coachPayments;
    }

    /**
     * @param Collection $coachPayments
     * @return User
     */
    public function setCoachPayments(Collection $coachPayments): User
    {
        $this->coachPayments = $coachPayments;

        return $this;
    }

    /**
     * @return Affiliate
     */
    public function getAffiliate(): ?Affiliate
    {
        return $this->affiliate;
    }

    /**
     * @param Affiliate $affiliate
     * @return User
     */
    public function setAffiliate(Affiliate $affiliate): User
    {
        $this->affiliate = $affiliate;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    /**
     * @param ArrayCollection $notes
     * @return User
     */
    public function setNotes(ArrayCollection $notes): User
    {
        $this->notes = $notes;
        return $this;
    }

    /**
     * @return Coach
     */
    public function getCoach(): ?Coach
    {
        return $this->coach;
    }

    /**
     * @param Coach $coach
     * @return User
     */
    public function setCoach(Coach $coach): User
    {
        $this->coach = $coach;
        return $this;
    }
}
