<?php

namespace App\Entity;

use App\Entity\Traits\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CoachRepository")
 */
class Coach
{

    const LOCALE_EU = 1;
    const LOCALE_NA = 2;
    const LOCALE_OCE = 3;

    const LOCALE_REGIONS = [
        OrderRocketLeague::REGION_ASE => self::LOCALE_OCE,
        OrderRocketLeague::REGION_OCE => self::LOCALE_OCE,
        OrderRocketLeague::REGION_ASMD => self::LOCALE_OCE,
        OrderRocketLeague::REGION_ASME => self::LOCALE_OCE,
        OrderRocketLeague::REGION_USE => self::LOCALE_NA,
        OrderRocketLeague::REGION_USW => self::LOCALE_NA ,
        OrderRocketLeague::REGION_SAM => self::LOCALE_NA,
        OrderRocketLeague::REGION_EU => self::LOCALE_EU,
        OrderRocketLeague::REGION_ME => self::LOCALE_EU,
        OrderRocketLeague::REGION_SAF => self::LOCALE_EU,
    ];

    use TimestampTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $locale;

    /**
     * @var User
     * @ORM\OneToOne(targetEntity="User", inversedBy="coach")
     */
    private $user;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getLocale(): int
    {
        return $this->locale;
    }

    /**
     * @param int $locale
     * @return Coach
     */
    public function setLocale(int $locale): self
    {
        $this->locale = $locale;
        return $this;
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
     * @return Coach
     */
    public function setUser(User $user): Coach
    {
        $this->user = $user;
        return $this;
    }
}
