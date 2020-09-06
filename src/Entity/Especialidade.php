<?php

namespace App\Entity;

use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\GeneratedValue;

/**
 * @ORM\Entity(repositoryClass=EspecialidadeRepository::class)
 */
class Especialidade
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string")
     */
    public $descricao;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescricao() : ?string
    {
        return $this->descricao;        
    }

    public function setDescricao(string $descricao): self
    {
        $this->descricao = $descricao;

        return $this;
    }
}
