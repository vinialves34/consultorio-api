<?php

namespace App\Controller;

use App\Helper\ExtratorDadosRequest;
use App\Helper\MedicoFactory;
use App\Repository\MedicoRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class MedicosController extends BaseController
{
    public function __construct(MedicoRepository $medicoRepository, EntityManagerInterface $entityManager, MedicoFactory $medicoFactory, ExtratorDadosRequest $extratorDados)
    {
        parent::__construct($medicoRepository, $entityManager, $medicoFactory, $extratorDados);
        $this->factory = $medicoFactory;
        $this->repository = $medicoRepository;
    }

    /**
     * Busca medicos por especialidade
     * @Route("/especialidades/{especialidadeId}/medicos", methods={"GET"})
     * @param int $especialidadeId
     * @return JsonResponse
     */
    public function buscaPorEspecialidade(int $especialidadeId): JsonResponse
    {
        $medicos = $this->repository->findBy(['especialidade' => $especialidadeId]);
        return new JsonResponse($medicos);
    }

    /**
     * @param Medico $entidadeExistente
     * @param Medico $entidadeEnviada
     */
    public function atualizarEntidadeExistente($entidadeExistente, $entidadeEnviada)
    {
        $entidadeExistente->setCrm($entidadeEnviada->getCrm())
            ->setNome($entidadeEnviada->getNome())
            ->setEspecialidade($entidadeEnviada->getEspecialidade());
    }
}