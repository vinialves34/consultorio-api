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
    public function __construct(EntityManagerInterface $entityManager, MedicoRepository $medicoRepository, MedicoFactory $medicoFactory, ExtratorDadosRequest $extratorDados)
    {
        parent::__construct($medicoRepository, $entityManager, $medicoFactory, $extratorDados);
        $this->factory = $medicoFactory;
    }

    /**
     *@Route("/especialidades/{especialidadeId}/medicos", methods={"GET"})
     */
    public function buscaPorEspecialidade(int $especialidadeId) : Response
    {
        $medicos = $this->medicoRepository->findBy(['especialidade' => $especialidadeId]);
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