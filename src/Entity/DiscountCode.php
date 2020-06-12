<?php

namespace App\Entity;

use App\Entity\Traits\TimestampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DiscountCodeRepository")
 * @ORM\Table(name="`discount_code`")
 * @ORM\HasLifecycleCallbacks()
 */
class DiscountCode
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
     * @ORM\Column(type="string")
     */
    private $code;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $discountCommission = 0;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $discountCoaches = 0;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $totalDiscount = 0;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $expiryDate;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $maxUsages = 0;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $usages = 0;

    /**
     * @var Affiliation
     * @ORM\OneToOne(targetEntity="Affiliation", inversedBy="discountCode")
     */
    private $affiliation;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Order", mappedBy="discountCode")
     */
    private $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return DiscountCode
     */
    public function setCode(string $code): DiscountCode
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return float
     */
    public function getDiscountCommission(): float
    {
        return $this->discountCommission;
    }

    /**
     * @param float $discountCommission
     * @return DiscountCode
     */
    public function setDiscountCommission(float $discountCommission): DiscountCode
    {
        $this->discountCommission = $discountCommission;

        return $this;
    }

    /**
     * @return float
     */
    public function getDiscountCoaches(): float
    {
        return $this->discountCoaches;
    }

    /**
     * @param float $discountCoaches
     * @return DiscountCode
     */
    public function setDiscountCoaches(float $discountCoaches): DiscountCode
    {
        $this->discountCoaches = $discountCoaches;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalDiscount(): float
    {
        return $this->totalDiscount;
    }

    /**
     * @param float $totalDiscount
     * @return DiscountCode
     */
    public function setTotalDiscount(float $totalDiscount): DiscountCode
    {
        $this->totalDiscount = $totalDiscount;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpiryDate(): ?\DateTime
    {
        return $this->expiryDate;
    }

    /**
     * @param \DateTime $expiryDate
     * @return DiscountCode
     */
    public function setExpiryDate(?\DateTime $expiryDate): DiscountCode
    {
        $this->expiryDate = $expiryDate;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxUsages(): int
    {
        return $this->maxUsages;
    }

    /**
     * @param int $maxUsages
     * @return DiscountCode
     */
    public function setMaxUsages(int $maxUsages): DiscountCode
    {
        $this->maxUsages = $maxUsages;

        return $this;
    }

    /**
     * @return int
     */
    public function getUsages(): int
    {
        return $this->usages;
    }

    /**
     * @param int $usages
     * @return DiscountCode
     */
    public function setUsages(int $usages): DiscountCode
    {
        $this->usages = $usages;

        return $this;
    }

    /**
     * @return Affiliation
     */
    public function getAffiliation(): ?Affiliation
    {
        return $this->affiliation;
    }

    /**
     * @param Affiliation $affiliation
     * @return DiscountCode
     */
    public function setAffiliation(Affiliation $affiliation): DiscountCode
    {
        $this->affiliation = $affiliation;

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
     * @return DiscountCode
     */
    public function setOrders(ArrayCollection $orders): DiscountCode
    {
        $this->orders = $orders;

        return $this;
    }
}
