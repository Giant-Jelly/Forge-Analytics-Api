<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRocketLeagueRepository")
 */
class OrderRocketLeague
{
    const COACHING_TYPE_COACHING = 1;
    const COACHING_TYPE_TEAMMATE = 2;

    const GAME_MODE_1v1 = 1;
    const GAME_MODE_2v2 = 2;
    const GAME_MODE_3v3 = 3;
    const GAME_MODE_OTHER = 4;
    const GAME_MODE_ANY = 5;

    const REGION_USE = 1;
    const REGION_USW = 2;
    const REGION_EU = 3;
    const REGION_ASE = 4;
    const REGION_ASMD = 5;
    const REGION_ASME = 6;
    const REGION_ME = 7;
    const REGION_OCE = 8;
    const REGION_SAF = 9;
    const REGION_SAM = 10;

    const COACHING_TYPES = [
        self::COACHING_TYPE_COACHING => 'Standard Coaching',
        self::COACHING_TYPE_TEAMMATE => 'Live Coaching'
    ];

    const GAME_MODES = [
        self::GAME_MODE_1v1 => '1v1',
        self::GAME_MODE_2v2 => '2v2',
        self::GAME_MODE_3v3 => '3v3',
        self::GAME_MODE_OTHER => 'Extra Mode',
        self::GAME_MODE_ANY => 'Not Specific'
    ];

    const REGIONS = [
        self::REGION_USE => 'US-East',
        self::REGION_USW => 'US-West',
        self::REGION_EU => 'Europe',
        self::REGION_ASE => 'Asia East',
        self::REGION_ASMD => 'Asia SE-Mainland',
        self::REGION_ASME => 'Asia SE-Maritime',
        self::REGION_ME => 'Middle East',
        self::REGION_OCE => 'Oceania',
        self::REGION_SAF => 'South Africa',
        self::REGION_SAM => 'South America',
    ];

    const DURATIONS = [
        '0.5' => '30 Minutes ($6)',
        1 => '1 hour ($12)',
        '1.5' => '1.5 hours ($18)',
        2 => '2 hours ($24)',
        '2.5' => '2.5 hours ($30)',
        3 => '3 hours ($36)',
        '3.5' => '3.5 hours ($42)',
        4 => '4 hours ($48)',
    ];

    const RANK_BRONZE = 1;
    const RANK_SILVER = 2;
    const RANK_GOLD = 3;
    const RANK_PLATINUM = 4;
    const RANK_DIAMOND = 5;
    const RANK_CHAMP_1 = 6;
    const RANK_CHAMP_2 = 7;
    const RANK_CHAMP_3 = 8;
    const RANK_LOW_GC = 9;
    const RANK_HIGH_GC = 10;

    const RANKS = [
        self::RANK_BRONZE => 'Bronze',
        self::RANK_SILVER => 'Silver',
        self::RANK_GOLD => 'Gold',
        self::RANK_PLATINUM => 'Platinum',
        self::RANK_DIAMOND => 'Diamond',
        self::RANK_CHAMP_1 => 'Champion 1',
        self::RANK_CHAMP_2 => 'Champion 2',
        self::RANK_CHAMP_3 => 'Champion 3',
        self::RANK_LOW_GC => 'Grand Champion',
        self::RANK_HIGH_GC => 'Grand Champion 1700+ mmr',
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Order
     * @ORM\OneToOne(targetEntity="Order", inversedBy="rocketLeagueOrder")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     */
    private $order;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $coachingType;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $gameMode;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $region;

    /**
     * @var string
     * @ORM\Column(type="string", length=500)
     */
    private $availability;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $duration;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $rank;

    /**
     * @var string
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $preference;

    /**
     * @var string
     * @ORM\Column(type="string", length=500)
     */
    private $extraInfo;

    public function getId(): ?int
    {
        return $this->id;
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
     * @return OrderRocketLeague
     */
    public function setOrder(?Order $order): OrderRocketLeague
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return int
     */
    public function getCoachingType(): ?int
    {
        return $this->coachingType;
    }

    /**
     * @param int $coachingType
     * @return OrderRocketLeague
     */
    public function setCoachingType(int $coachingType): OrderRocketLeague
    {
        $this->coachingType = $coachingType;

        return $this;
    }

    /**
     * @return int
     */
    public function getGameMode(): ?int
    {
        return $this->gameMode;
    }

    /**
     * @param int $gameMode
     * @return OrderRocketLeague
     */
    public function setGameMode(int $gameMode): OrderRocketLeague
    {
        $this->gameMode = $gameMode;

        return $this;
    }

    /**
     * @return int
     */
    public function getRegion(): ?int
    {
        return $this->region;
    }

    /**
     * @param int $region
     * @return OrderRocketLeague
     */
    public function setRegion(int $region): OrderRocketLeague
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return string
     */
    public function getAvailability(): ?string
    {
        return $this->availability;
    }

    /**
     * @param string $availability
     * @return OrderRocketLeague
     */
    public function setAvailability(string $availability): OrderRocketLeague
    {
        $this->availability = $availability;

        return $this;
    }

    /**
     * @return string
     */
    public function getDuration(): ?string
    {
        return $this->duration;
    }

    /**
     * @param string $duration
     * @return OrderRocketLeague
     */
    public function setDuration(string $duration): OrderRocketLeague
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return int
     */
    public function getRank(): ?int
    {
        return $this->rank;
    }

    /**
     * @param int $rank
     * @return OrderRocketLeague
     */
    public function setRank(int $rank): OrderRocketLeague
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * @return string
     */
    public function getPreference(): ?string
    {
        return $this->preference;
    }

    /**
     * @param string $preference
     * @return OrderRocketLeague
     */
    public function setPreference(?string $preference): OrderRocketLeague
    {
        $this->preference = $preference;

        return $this;
    }

    /**
     * @return string
     */
    public function getExtraInfo(): ?string
    {
        return $this->extraInfo;
    }

    /**
     * @param string $extraInfo
     * @return OrderRocketLeague
     */
    public function setExtraInfo(?string $extraInfo): OrderRocketLeague
    {
        $this->extraInfo = $extraInfo;

        return $this;
    }
}
