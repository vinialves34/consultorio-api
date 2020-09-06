<?php

namespace App\Entity;

use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\GeneratedValue;

/**
 * @ORM\Entity(repositoryClass=EspecialidadeRepository::class)
 */
class Especialidade implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $descricao;

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

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'descricao' => $this->getDescricao()
        ];
    }
}
