<?php

namespace App\Entity;

use App\Repository\UserCustomerRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "app_customer_detail",
 *          parameters = { "id" = "expr(object.getId())" }
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups="getCustomer")
 * )
 *
  * @Hateoas\Relation(
 *      "delete",
 *      href = @Hateoas\Route(
 *          "app_delete_customer",
 *          parameters = { "id" = "expr(object.getId())" },
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups="getCustomer", excludeIf = "expr(not is_granted('ROLE_ADMIN'))"),
 * )
 *
 *
 */
#[ORM\Entity(repositoryClass: UserCustomerRepository::class)]
class UserCustomer
{

    /**
     * 
     *
     * Entité UserCustomer,
     * 
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getCustomer"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getCustomer"])]
    #[Assert\NotBlank(message: "Ce champ est obligatoire")]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getCustomer"])]
    #[Assert\NotBlank(message: "Ce champ est obligatoire")]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getCustomer"])]
    #[Assert\NotBlank(message: "Ce champ est obligatoire")]
    private ?string $email = null;

    
    #[ORM\ManyToOne(inversedBy: 'userCustomers')]

    #[Groups(["getCustomer"])]
    private ?User $userr = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
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

    public function getUserr(): ?User
    {
        return $this->userr;
    }

    public function setUserr(?User $userr): self
    {
        $this->userr = $userr;

        return $this;
    }
}
