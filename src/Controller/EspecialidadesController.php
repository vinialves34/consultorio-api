<?php

namespace App\Controller;

use App\Helper\EspecialidadeFactory;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EspecialidadesController extends BaseController
{
    public function __construct(EntityManagerInterface $entityManager, EspecialidadeFactory $especialidadeFactory, EspecialidadeRepository $repository)
    {
        parent::__construct($repository, $entityManager, $especialidadeFactory);
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
