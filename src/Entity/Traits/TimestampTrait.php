<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * Trait DateTrait
 * @package Application\Entity\Traits
 */
trait TimestampTrait
{
    /**
     * @var DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @return DateTime|null
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return self
     * @throws \Exception
     * @ORM\PrePersist()
     */
    public function setCreatedAt(): self
    {
        // Make sure we don't ever overwrite any existing createdAt values...
        if ($this->createdAt === null) {
            $this->createdAt = new DateTime();
        }
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @return self
     * @throws \Exception
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setUpdatedAt(): self
    {
        $this->updatedAt = new DateTime();
        return $this;
    }
}
