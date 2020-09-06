<?php

namespace App\Helper;

use App\Entity\Especialidade;

class EspecialidadeFactory 
{
    public function criarEspecialidade(string $json) : Especialidade
    {
        $dadosJson = json_decode($json);

        $especialidade = new Especialidade();
        $especialidade->setDescricao($dadosJson->descricao);

        return $especialidade;
    }
}