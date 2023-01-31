<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    /**
     * Entité User
     * fullname, email,password,
     *
     * 
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getCustomer"])]
    private ?string $fullname = null;

    #[ORM\OneToMany(mappedBy: 'userr', targetEntity: Product::class)]
    private Collection $product;

    #[ORM\OneToMany(mappedBy: 'userr', targetEntity: UserCustomer::class)]
    private Collection $userCustomers;

    public function __construct()
    {
        $this->product = new ArrayCollection();
        $this->userCustomers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
    public function getUsername(): string {
        return $this->getUserIdentifier();
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->product->contains($product)) {
            $this->product->add($product);
            $product->setUserr($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->product->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getUserr() === $this) {
                $product->setUserr(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserCustomer>
     */
    public function getUserCustomers(): Collection
    {
        return $this->userCustomers;
    }

    public function addUserCustomer(UserCustomer $userCustomer): self
    {
        if (!$this->userCustomers->contains($userCustomer)) {
            $this->userCustomers->add($userCustomer);
            $userCustomer->setUserr($this);
        }

        return $this;
    }

    public function removeUserCustomer(UserCustomer $userCustomer): self
    {
        if ($this->userCustomers->removeElement($userCustomer)) {
            // set the owning side to null (unless already changed)
            if ($userCustomer->getUserr() === $this) {
                $userCustomer->setUserr(null);
            }
        }

        return $this;
    }
}
