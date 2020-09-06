<?php

namespace App\Controller;

use App\Helper\MedicoFactory;
use App\Repository\MedicoRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class MedicosController extends BaseController
{
    public function __construct(EntityManagerInterface $entityManager, MedicoRepository $medicoRepository, MedicoFactory $medicoFactory)
    {
        parent::__construct($medicoRepository, $entityManager, $medicoFactory);
        $this->factory = $medicoFactory;
    }

    public function buscarMedico(int $id)
    {
        $medico = $this->medicoRepository->find($id);    
        return $medico;
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