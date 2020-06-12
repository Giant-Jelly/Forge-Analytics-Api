<?php

namespace App\Entity;

use App\Entity\Traits\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaymentRepository")
 * @ORM\Table(name="payment")
 * @ORM\HasLifecycleCallbacks()
 */
class Payment
{
    const TYPE_COACH = 1;
    const TYPE_AFFILIATE = 2;

    use TimestampTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="coachPayments")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $transactionId;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $payer;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $type = 1;

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
     * @return Payment
     */
    public function setUser(User $user): Payment
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    /**
     * @param string $transactionId
     * @return Payment
     */
    public function setTransactionId(string $transactionId): Payment
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return Payment
     */
    public function setAmount(float $amount): Payment
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string
     */
    public function getPayer(): string
    {
        return $this->payer;
    }

    /**
     * @param string $payer
     * @return Payment
     */
    public function setPayer(string $payer): Payment
    {
        $this->payer = $payer;

        return $this;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return Payment
     */
    public function setType(int $type): Payment
    {
        $this->type = $type;

        return $this;
    }
}
