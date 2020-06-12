<?php

namespace App\Entity;

use App\Entity\Traits\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AffiliationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Affiliation
{

    const COOKIE_NAME = 'affiliation';

    const OWNER_UNITED_ROGUE = 1;
    const OWNER_TEAM_METEOR = 2;
    const OWNER_GEMINI_JUPITER = 3;
    const OWNER_MLE = 4;

    const TYPE_DISCOUNT_CODE = 1;
    const TYPE_LINK = 2;

    const TYPES = [
        self::TYPE_DISCOUNT_CODE => 'Discount Code',
        self::TYPE_LINK => 'Affiliate Link'
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
     * @ORM\Column(type="float")
     */
    private $earned;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $commission;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $bonus = 0;

    /**
     * The amount of quantity to receive 1 bonus
     *
     * @var int
     * @ORM\Column(type="integer")
     */
    private $bonusInterval = 0;

    /**
     * @var Affiliate
     * @ORM\ManyToOne(targetEntity="Affiliate", inversedBy="affiliations")
     */
    private $affiliate;

    /**
     * @var DiscountCode
     * @ORM\OneToOne(targetEntity="DiscountCode", mappedBy="affiliation")
     */
    private $discountCode;

    /**
     * This relates to the constants above so that we can set an affiliation link for the affiliate
     *
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $owner;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $type = 2;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Affiliation
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return float
     */
    public function getEarned(): float
    {
        return $this->earned;
    }

    /**
     * @param float $earned
     * @return Affiliation
     */
    public function setEarned(float $earned): Affiliation
    {
        $this->earned = $earned;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return Affiliation
     */
    public function setQuantity(int $quantity): Affiliation
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return float
     */
    public function getCommission(): float
    {
        return $this->commission;
    }

    /**
     * @param float $commission
     * @return Affiliation
     */
    public function setCommission(float $commission): Affiliation
    {
        $this->commission = $commission;

        return $this;
    }

    /**
     * @return float
     */
    public function getBonus(): float
    {
        return $this->bonus;
    }

    /**
     * @param float $bonus
     * @return Affiliation
     */
    public function setBonus(float $bonus): Affiliation
    {
        $this->bonus = $bonus;

        return $this;
    }

    /**
     * @return int
     */
    public function getBonusInterval(): int
    {
        return $this->bonusInterval;
    }

    /**
     * @param int $bonusInterval
     * @return Affiliation
     */
    public function setBonusInterval(int $bonusInterval): Affiliation
    {
        $this->bonusInterval = $bonusInterval;

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
     * @return Affiliation
     */
    public function setAffiliate(Affiliate $affiliate): Affiliation
    {
        $this->affiliate = $affiliate;

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
     * @return Affiliation
     */
    public function setDiscountCode(?DiscountCode $discountCode): Affiliation
    {
        $this->discountCode = $discountCode;

        return $this;
    }

    /**
     * @return int
     */
    public function getOwner(): ?int
    {
        return $this->owner;
    }

    /**
     * @param int $owner
     * @return Affiliation
     */
    public function setOwner(int $owner): Affiliation
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * @return int
     */
    public function getType(): ?int
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return Affiliation
     */
    public function setType(int $type): Affiliation
    {
        $this->type = $type;
        return $this;
    }
}
