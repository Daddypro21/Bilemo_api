<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\DateTimeTzType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getProduct"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getProduct"])]
    #[Assert\NotBlank(message: "Le nom du produit est obligatoire")]
    #[Assert\Length(min: 1, max: 255, minMessage: "Le nom du produit doit faire au moins {{ limit }} caractères", maxMessage: "Le nom du produit ne peut pas faire plus de {{ limit }} caractères")]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["getProduct"])]
    #[Assert\NotBlank(message: "La description du produit est obligatoire")]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(["getProduct"])]
    #[Assert\NotBlank(message: "Le champ screen est obligatoire")]
    private ?float $screen = null;

    #[ORM\Column]
    #[Groups(["getProduct"])]
    #[Assert\NotBlank(message: "Le champ weight est obligatoire")]
    private ?float $weight = null;

    #[ORM\Column]
    #[Groups(["getProduct"])]
    #[Assert\NotBlank(message: "Le champ length est obligatoire")]
    private ?float $length = null;

    #[ORM\Column]
    #[Groups(["getProduct"])]
    #[Assert\NotBlank(message: "Le champ width est obligatoire")]
    private ?float $width = null;

    #[ORM\Column]
    #[Groups(["getProduct"])]
    #[Assert\NotBlank(message: "Le champ height est obligatoire")]
    private ?float $height = null;

    #[ORM\Column]
    #[Groups(["getProduct"])]
    private ?bool $wifi = false;

    #[ORM\Column]
    #[Groups(["getProduct"])]
    private ?bool $video = false;

    #[ORM\Column]
    #[Groups(["getProduct"])]
    private ?bool $bluetooth = false;

    #[ORM\Column]
    #[Groups(["getProduct"])]
    private ?bool $camera = false;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Configuration::class)]
    #[Groups(["getProduct"])]
    private Collection $configurations;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Image::class)]
    #[Groups(["getProduct"])]
    private Collection $images;

    #[ORM\ManyToOne(inversedBy: 'product')]
    #[Groups(["getProduct"])]
    private ?User $userr = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;


    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
        $this->configurations = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getScreen(): ?float
    {
        return $this->screen;
    }

    public function setScreen(float $screen): self
    {
        $this->screen = $screen;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getLength(): ?float
    {
        return $this->length;
    }

    public function setLength(float $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function setWidth(float $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(float $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getWifi(): ?bool
    {
        return $this->wifi;
    }

    public function setWifi(bool $wifi): self
    {
        $this->wifi = $wifi;

        return $this;
    }

    public function getVideo(): ?bool
    {
        return $this->video;
    }

    public function setVideo(bool $video): self
    {
        $this->video = $video;

        return $this;
    }

    public function getBluetooth(): ?bool
    {
        return $this->bluetooth;
    }

    public function setBluetooth(bool $bluetooth): self
    {
        $this->bluetooth = $bluetooth;

        return $this;
    }

    public function getCamera(): ?bool
    {
        return $this->camera;
    }

    public function setCamera(bool $camera): self
    {
        $this->camera = $camera;

        return $this;
    }

    /**
     * @return Collection<int, Configuration>
     */
    public function getConfigurations(): Collection
    {
        return $this->configurations;
    }

    public function addConfiguration(Configuration $configuration): self
    {
        if (!$this->configurations->contains($configuration)) {
            $this->configurations->add($configuration);
            $configuration->setProduct($this);
        }

        return $this;
    }

    public function removeConfiguration(Configuration $configuration): self
    {
        if ($this->configurations->removeElement($configuration)) {
            // set the owning side to null (unless already changed)
            if ($configuration->getProduct() === $this) {
                $configuration->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setProduct($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProduct() === $this) {
                $image->setProduct(null);
            }
        }

        return $this;
    }

    public function getUserr(): ?User
    {
        return $this->userr;
    }

    public function setUserr(?User $userr): self
    {
        $this->userr = $userr;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


   


}
