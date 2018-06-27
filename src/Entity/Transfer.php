<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransferRepository")
 */
class Transfer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $emailExpediteur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $emailDestinataire;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $emailCopie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    public function getId()
    {
        return $this->id;
    }

    public function getEmailExpediteur(): ?string
    {
        return $this->emailExpediteur;
    }

    public function setEmailExpediteur(string $emailExpediteur): self
    {
        $this->emailExpediteur = $emailExpediteur;

        return $this;
    }

    public function getEmailDestinataire(): ?string
    {
        return $this->emailDestinataire;
    }

    public function setEmailDestinataire(string $emailDestinataire): self
    {
        $this->emailDestinataire = $emailDestinataire;

        return $this;
    }

    public function getEmailCopie(): ?string
    {
        return $this->emailCopie;
    }

    public function setEmailCopie(?string $emailCopie): self
    {
        $this->emailCopie = $emailCopie;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }
}
