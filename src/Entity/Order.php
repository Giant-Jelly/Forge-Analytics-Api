<?php

namespace App\Entity;

use App\Entity\Traits\TimestampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table(name="`order`")
 * @ORM\HasLifecycleCallbacks()
 */
class Order
{
    const SERVICE_FEE = 0.89;

    const GAME_ROCKET_LEAGUE = 1;

    const STATUS_PENDING = 1;
    const STATUS_AVAILABLE = 2;
    const STATUS_TAKEN = 3;
    const STATUS_COMPLETE = 4;
    const STATUS_CANCELLED = 5;
    const STATUS_REFUNDED = 6;

    const STATUSES = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_AVAILABLE => 'Available',
        self::STATUS_TAKEN => 'Assigned',
        self::STATUS_COMPLETE => 'Complete',
        self::STATUS_CANCELLED => 'Cancelled (Awaiting Refund)',
        self::STATUS_REFUNDED => 'Refunded (Cancelled)'
    ];

    const GAMES = [
        self::GAME_ROCKET_LEAGUE => 'Rocket League'
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="orders")
     * @MaxDepth(1)
     */
    private $user;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $game;

    /**
     * @var OrderRocketLeague
     * @ORM\OneToOne(targetEntity="OrderRocketLeague", mappedBy="order")
     * @MaxDepth(1)
     */
    private $rocketLeagueOrder;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="assignedOrders")
     * @MaxDepth(1)
     */
    private $coach;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $transactionId;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $status = self::STATUS_PENDING;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $orderNumber;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $placedAt;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $amount;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $commissionAmount;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $coachesAmount;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $paypal = true;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $notified = false;

    /**
     * @var DiscountCode
     * @ORM\ManyToOne(targetEntity="DiscountCode", inversedBy="order")
     * @MaxDepth(1)
     */
    private $discountCode;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $serviceFee = self::SERVICE_FEE;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Tip", mappedBy="order")
     * @MaxDepth(1)
     */
    private $tips;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="OrderLogItem", mappedBy="order")
     * @MaxDepth(1)
     */
    private $orderLogItems;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="OrderNote", mappedBy="order")
     * @MaxDepth(1)
     */
    private $notes;

    public function __construct()
    {
        $this->tips = new ArrayCollection();
        $this->orderLogItems = new ArrayCollection();
        $this->notes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Order
     */
    public function setUser(User $user): Order
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return int
     */
    public function getGame(): ?int
    {
        return $this->game;
    }

    /**
     * @param int $game
     * @return Order
     */
    public function setGame(int $game): Order
    {
        $this->game = $game;

        return $this;
    }

    /**
     * @return OrderRocketLeague
     */
    public function getRocketLeagueOrder(): ?OrderRocketLeague
    {
        return $this->rocketLeagueOrder;
    }

    /**
     * @param OrderRocketLeague $rocketLeagueOrder
     * @return Order
     */
    public function setRocketLeagueOrder(OrderRocketLeague $rocketLeagueOrder): Order
    {
        $this->rocketLeagueOrder = $rocketLeagueOrder;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return Order
     */
    public function setStatus(int $status): Order
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return User
     */
    public function getCoach(): ?User
    {
        return $this->coach;
    }

    /**
     * @param User $coach
     * @return Order
     */
    public function setCoach(?User $coach): Order
    {
        $this->coach = $coach;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrderNumber(): ?string
    {
        return $this->orderNumber;
    }

    /**
     * @param string $orderNumber
     * @return Order
     */
    public function setOrderNumber(?string $orderNumber): Order
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    /**
     * @param string $transactionId
     * @return Order
     */
    public function setTransactionId(?string $transactionId): Order
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPlacedAt(): ?\DateTime
    {
        return $this->placedAt;
    }

    /**
     * @param \DateTime $placedAt
     * @return Order
     */
    public function setPlacedAt(\DateTime $placedAt): Order
    {
        $this->placedAt = $placedAt;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return Order
     */
    public function setAmount(float $amount): Order
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return float
     */
    public function getCommissionAmount(): ?float
    {
        return $this->commissionAmount;
    }

    /**
     * @param float $commissionAmount
     * @return Order
     */
    public function setCommissionAmount(float $commissionAmount): Order
    {
        $this->commissionAmount = $commissionAmount;

        return $this;
    }

    /**
     * @return float
     */
    public function getCoachesAmount(): ?float
    {
        return $this->coachesAmount;
    }

    /**
     * @param float $coachesAmount
     * @return Order
     */
    public function setCoachesAmount(float $coachesAmount): Order
    {
        $this->coachesAmount = $coachesAmount;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPaypal(): bool
    {
        return $this->paypal;
    }

    /**
     * @param bool $paypal
     * @return Order
     */
    public function setPaypal(bool $paypal): Order
    {
        $this->paypal = $paypal;

        return $this;
    }

    /**
     * @return bool
     */
    public function isNotified(): bool
    {
        return $this->notified;
    }

    /**
     * @param bool $notified
     * @return Order
     */
    public function setNotified(bool $notified): Order
    {
        $this->notified = $notified;

        return $this;
    }

    /**
     * @return DiscountCode
     */
    public function getDiscountCode(): ?DiscountCode
    {
        return $this->discountCode;
    }

    /**
     * @param DiscountCode $discountCode
     * @return Order
     */
    public function setDiscountCode(?DiscountCode $discountCode): Order
    {
        $this->discountCode = $discountCode;

        return $this;
    }

    /**
     * @return float
     */
    public function getServiceFee(): float
    {
        return $this->serviceFee;
    }

    /**
     * @param float $serviceFee
     * @return Order
     */
    public function setServiceFee(float $serviceFee): Order
    {
        $this->serviceFee = $serviceFee;

        return $this;
    }

    /**
     * @return Collection|Tip[]
     */
    public function getTips(): Collection
    {
        return $this->tips;
    }

    /**
     * @param Collection $tips
     * @return Order
     */
    public function setTips(Collection $tips): Order
    {
        $this->tips = $tips;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getOrderLogItems(): Collection
    {
        return $this->orderLogItems;
    }

    /**
     * @param ArrayCollection $orderLogItems
     * @return Order
     */
    public function setOrderLogItems(ArrayCollection $orderLogItems): Order
    {
        $this->orderLogItems = $orderLogItems;
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
     * @return Order
     */
    public function setNotes(ArrayCollection $notes): Order
    {
        $this->notes = $notes;
        return $this;
    }


    /**
     * @return string
     */
    public function getUniqueId(): string
    {
        return $this->orderNumber.$this->id;
    }
}
