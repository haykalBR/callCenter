<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Membre\Entity;

use App\Core\Enum\GenreEnum;
use Doctrine\ORM\Mapping as ORM;
use App\Core\Enum\RelationShipEnum;
use App\Core\Traits\FileUploadTrait;
use App\Core\Traits\TimestampableTrait;
use App\Domain\Membre\Repository\ProfileRepository;

/**
 * @ORM\Entity(repositoryClass=ProfileRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Profile implements \Serializable
{
    use TimestampableTrait;
    use FileUploadTrait;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $firstName;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $gender;

    /**
     * @var \DateTimeInterface|null
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthday;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    private $address;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mobile;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telephone;
    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $relationShipStatus;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $locale;

    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $codePostal;
    /**
     * @var User|null
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="profile", cascade={"persist", "remove"})
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getGender(): ?int
    {
        return $this->gender;
    }

    public function setGender(?int $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(?string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getRelationShipStatus(): ?int
    {
        return $this->relationShipStatus;
    }

    public function setRelationShipStatus(?int $relationShipStatus): self
    {
        $this->relationShipStatus = $relationShipStatus;

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(?string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->codePostal;
    }

    public function setCodePostal(?int $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Render gender value.
     *
     * @return string
     */
    public function gender()
    {
        switch ($this->gender) {
            case 0:
                return GenreEnum::getTypeName(GenreEnum::GENRE_MR);
            case 1:
                return GenreEnum::getTypeName(GenreEnum::GENRE_MME);
            default:
                return GenreEnum::getTypeName(GenreEnum::GENRE_MLLE);
        }
    }

    public function relationShipS(): string
    {
        switch ($this->relationShipStatus) {
            case 0:
                return RelationShipEnum::getTypeName(RelationShipEnum::SINGLE);
            default:
                return RelationShipEnum::getTypeName(RelationShipEnum::MARRIED);
        }
    }

    public function getUploadDir(): string
    {
        /** @var User $user */
        $user=$this->getUser();

        return 'profile'.'..'.\DIRECTORY_SEPARATOR.'..'.$user->getId();
    }

    public function serialize(): string
    {
        return  '';
    }

    public function unserialize($serialized)
    {
        // TODO: Implement unserialize() method.
    }
}
