<?php

namespace App\Entity;

use App\Entity\Traits\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OutgoingRepository")
 * @ORM\Table(name="outgoing")
 * @ORM\HasLifecycleCallbacks()
 */
class Outgoing
{
    const TYPE_ONE_OFF = 1;
    const TYPE_RECURRING = 2;

    const TYPES = [
        self::TYPE_ONE_OFF => 'One off',
        self::TYPE_RECURRING => 'Recurring'
    ];

    const INTERVAL_NONE = 0;
    const INTERVAL_WEEKLY = 1;
    const INTERVAL_MONTHLY = 2;
    const INTERVAL_YEARLY = 3;

    const INTERVALS = [
        self::INTERVAL_NONE => 'None',
        self::INTERVAL_WEEKLY => 'Weekly',
        self::INTERVAL_MONTHLY => 'Monthly',
        self::INTERVAL_YEARLY => 'Yearly'
    ];


    use TimestampTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

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
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $details;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $interval;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $date;


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
     * @return Outgoing
     */
    public function setAmount(float $amount): Outgoing
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Outgoing
     */
    public function setName(string $name): Outgoing
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDetails(): string
    {
        return $this->details;
    }

    /**
     * @param string $details
     * @return Outgoing
     */
    public function setDetails(string $details): Outgoing
    {
        $this->details = $details;

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
     * @return Outgoing
     */
    public function setType(int $type): Outgoing
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return int
     */
    public function getInterval(): int
    {
        return $this->interval;
    }

    /**
     * @param int $interval
     * @return Outgoing
     */
    public function setInterval(int $interval): Outgoing
    {
        $this->interval = $interval;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return Outgoing
     */
    public function setDate(\DateTime $date): Outgoing
    {
        $this->date = $date;

        return $this;
    }
}
