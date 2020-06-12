<?php

namespace App\Entity;

use App\Entity\Traits\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TipRepository")
 * @ORM\Table(name="`tip`")
 * @ORM\HasLifecycleCallbacks()
 */
class Tip
{
    use TimestampTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @var Order
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="tips")
     */
    private $order;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $transactionId;

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Tip
     */
    public function setAmount(float $amount): Tip
    {
        $this->amount = $amount;

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
     * @return Tip
     */
    public function setOrder(Order $order): Tip
    {
        $this->order = $order;

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
     * @return Tip
     */
    public function setTransactionId(string $transactionId): Tip
    {
        $this->transactionId = $transactionId;

        return $this;
    }
}
