<?php

namespace App\Entity;

use App\Entity\Traits\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderLogItemRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class OrderLogItem
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
    private $message;

    /**
     * @var Order
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="orderLogItems")
     */
    private $order;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return OrderLogItem
     */
    public function setMessage(string $message): OrderLogItem
    {
        $this->message = $message;

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
     * @return OrderLogItem
     */
    public function setOrder(Order $order): OrderLogItem
    {
        $this->order = $order;

        return $this;
    }
}