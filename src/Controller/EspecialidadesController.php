<?php

namespace App\Controller;

use App\Helper\EspecialidadeFactory;
use App\Helper\ExtratorDadosRequest;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;

class EspecialidadesController extends BaseController
{
    public function __construct(EntityManagerInterface $entityManager, EspecialidadeFactory $especialidadeFactory, EspecialidadeRepository $repository, ExtratorDadosRequest $extratorDados)
    {
        parent::__construct($repository, $entityManager, $especialidadeFactory, $extratorDados);
        $this->entityManager = $entityManager;
        $this->factory = $especialidadeFactory;
    }

    public function buscarEspecialidade(int $id)
    {
        $especialidade = $this->especialidadeRepository->find($id);
        return $especialidade;
    }

    /**
     * @param Especialidade $entidadeExistente
     * @param Especialidade $entidadeEnviada
     */
    public function atualizarEntidadeExistente($entidadeExistente, $entidadeEnviada)
    {
        $entidadeExistente->setDescricao($entidadeEnviada->getDescricao());
    }
}
