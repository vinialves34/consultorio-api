<?php

namespace App\Controller;

use App\Helper\EspecialidadeFactory;
use App\Helper\ExtratorDadosRequest;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;

class EspecialidadesController extends BaseController
{
    public function __construct(EspecialidadeRepository $especialidadeRepository, EntityManagerInterface $entityManager, EspecialidadeFactory $especialidadeFactory, ExtratorDadosRequest $extratorDados)
    {
        parent::__construct($especialidadeRepository, $entityManager, $especialidadeFactory, $extratorDados);
        $this->entityManager = $entityManager;
        $this->factory = $especialidadeFactory;
        $this->repository = $especialidadeRepository;
    }

    /**
     * Buscar uma especialidade especifica
     * @param int $id
     */
    public function buscarEspecialidade(int $id)
    {
        $especialidade = $this->repository->find($id);
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
