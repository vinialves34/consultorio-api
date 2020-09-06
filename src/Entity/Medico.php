<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\GeneratedValue;

/**
 * @ORM\Entity()
 */
class Medico
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="integer")
     */
    public $crm;

    /**
     * @ORM\Column(type="string")
     */
    public $nome;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Especialidade")
     * @ORM\JoinColumn(nullable=false)
     */
    private $especialidade;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCrm(): ?int
    {
        return $this->crm;
    }

    public function setCrm(int $crm): self
    {
        $this->crm = $crm;

        return $this;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    public function getEspecialidade() : ?Especialidade
    {
        return $this->especialidade;        
    }

    public function setEspecialidade(?Especialidade $especialidade) : self
    {
        $this->especialidade = $especialidade;

        return $this;
    }
}