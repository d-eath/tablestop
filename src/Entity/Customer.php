<?php
// Fichier : Customer.php
// Date : 2021-04-01
// Auteur : Davis Eath
// But : Représenter un compte-client dans la base de données

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @ORM\Table(name="customers")
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 * @UniqueEntity(fields="username", message="Un compte avec ce nom d'utilisateur existe déjà.")
 */
class Customer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=15, unique=true)
     * @Assert\Length(min=2, minMessage="Le nom d'utilisateur doit être d'au moins 2 caractères.",
     *                max=15, maxMessage="Le nom d'utilisateur doit être d'au plus 15 caractères."))
     * @Assert\Regex(pattern="/^[A-Z0-9_]*$/i", message="Le nom d'utilisateur contient des caractères invalides.")
     * @Assert\NotNull(message="Le nom d'utilisateur ne peut pas être vide.")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\Length(min=2, minMessage="Le prénom doit être d'au moins 2 caractères.",
     *                max=15, maxMessage="Le prénom doit être d'au plus 15 caractères.")
     * @Assert\NotNull(message="Le prénom ne peut pas être vide.")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\Length(min=2, minMessage="Le nom de famille doit être d'au moins 2 caractères.",
     *                max=15, maxMessage="Le nom de famille doit être d'au plus 15 caractères.")
     * @Assert\NotNull(message="Le nom de famille ne peut pas être vide.")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=1)
     * @Assert\Regex(pattern="/^[XFM]$/", message="Le genre est invalide.")
     * @Assert\NotNull(message="Le genre ne peut pas être vide.")
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\Length(min=2, minMessage="L'adresse doit être d'au moins 2 caractères.",
     *                max=15, maxMessage="L'adresse doit être d'au plus 15 caractères.")
     * @Assert\NotNull(message="L'adresse ne peut pas être vide.")
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\Length(min=2, minMessage="La ville doit être d'au moins 2 caractères.",
     *                max=15, maxMessage="La ville doit être d'au plus 15 caractères.")
     * @Assert\NotNull(message="La ville ne peut pas être vide.")
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=2)
     * @Assert\NotNull(message="La province ne peut pas être vide.")
     */
    private $province;

    /**
     * @ORM\Column(type="string", length=6)
     * @Assert\Regex(pattern="/^[A-Z0-9]{6}$/", message="Le format du code postal est invalide.")
     * @Assert\NotNull(message="Le code postal ne peut pas être vide.")
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\Regex(pattern="/^\d{10}$/", message="Le format du numéro de téléphone est invalide.")
     * @Assert\NotNull(message="Le numéro de téléphone ne peut pas être vide.")
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(pattern="/^[^@ ]{3,}@[^@ ]{3,}\.[^@ ]{2,}$/", message="Le format de l'adresse courriel est invalide.")
     * @Assert\NotNull(message="L'adresse courriel ne peut pas être vide.")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(pattern="/^.{2,}$/", message="Le mot de passe doit contenir 2 à 15 caractères.")
     * @Assert\NotNull(message="Le mot de passe ne peut pas être vide.")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="customer")
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
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

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getProvince(): ?string
    {
        return $this->province;
    }

    public function setProvince(?string $province): self
    {
        $this->province = $province;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        if (is_null($this->postalCode))
        {
            return null;
        }

        return preg_replace('/^([0-9A-Z]{3})([0-9A-Z]{3})$/', '$1 $2', $this->postalCode);
    }

    public function setPostalCode(?string $postalCode): self
    {
        $pattern = '/^([A-CEGHJ-NPR-TV-Z]\d[A-CEGHJ-NPR-TV-Z]) ?(\d[A-CEGHJ-NPR-TV-Z]\d)$/i';

        if (preg_match($pattern, $postalCode))
        {
            $this->postalCode = strtoupper(preg_replace($pattern, '$1$2', $postalCode));
        }
        else
        {
            $this->postalCode = '-';
        }

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        if (is_null($this->phoneNumber))
        {
            return null;
        }

        return preg_replace('/^(\d{3})(\d{3})(\d{4})$/', '($1) $2-$3', $this->phoneNumber);
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $pattern = '/^(?:(\d{3})|\((\d{3})\))[ -]?(\d{3})[ -]?(\d{4})$/';

        if (preg_match($pattern, $phoneNumber))
        {
            $this->phoneNumber = preg_replace($pattern, '$1$2$3$4', $phoneNumber);
        }
        else
        {
            $this->phoneNumber = '-';
        }

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function verifyPassword($input): bool
    {
        return password_verify($input, $this->password);
    }

    public function setPassword(?string $password): self
    {
        if (strlen($password) >= 2 && strlen($password) <= 15)
        {
            $this->password = password_hash($password, PASSWORD_DEFAULT);
        }
        else
        {
            $this->password = '-';
        }

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setCustomer($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getCustomer() === $this) {
                $order->setCustomer(null);
            }
        }

        return $this;
    }
}
