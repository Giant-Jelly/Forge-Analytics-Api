<?php

namespace App\Entity;

use App\Entity\Traits\TimestampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AffiliateRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Affiliate
{

    use TimestampTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User
     * @ORM\OneToOne(targetEntity="User", inversedBy="affiliate")
     */
    private $user;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Affiliation", mappedBy="affiliate")
     */
    private $affiliations;

    public function __construct()
    {
        $this->affiliations = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Affiliate
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Affiliate
     */
    public function setUser(User $user): Affiliate
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getAffiliations(): Collection
    {
        return $this->affiliations;
    }

    /**
     * @param Collection $affiliations
     * @return Affiliate
     */
    public function setAffiliations(Collection $affiliations): Affiliate
    {
        $this->affiliations = $affiliations;

        return $this;
    }
}
