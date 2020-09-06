<?php

namespace App\Controller;

use App\Entity\Medico;
use App\Helper\MedicoFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MedicosController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var MedicoFactory
     */
    private $medicoFactory;

    public function __construct(EntityManagerInterface $entityManager, MedicoFactory $medicoFactory)
    {
        $this->medicoFactory = $medicoFactory;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/medicos", methods={"POST"})
     */
    public function novo(Request $req) : Response
    {
        $corpoRequest = $req->getContent();
        $medico = $this->medicoFactory->criarMedico($corpoRequest);

        $this->entityManager->persist($medico);
        $this->entityManager->flush();

        return new JsonResponse($medico);
    }

    /**
     * @Route("/medicos", methods={"GET"})
     */
    public function buscarTodos() : Response
    {
        $repositorioDeMedicos = $this->getDoctrine()->getRepository(Medico::class);
        $medicoList = $repositorioDeMedicos->findAll();

        if (empty($medicoList) || is_null($medicoList)) {
            $medicoList = [
                "StatusCode" => Response::HTTP_NO_CONTENT,
                "Msg" => "Não existe médicos cadastrados!"
            ];
        }

        return new JsonResponse($medicoList);
    }

    /**
     * @Route("/medicos/{id}", methods={"GET"})
     */
    public function buscar(int $id) : Response
    {   
        $medico = $this->buscarMedico($id);
        $statusCode = (!empty($medico) || !is_null($medico)) ? Response::HTTP_OK : Response::HTTP_NO_CONTENT;

        return new JsonResponse($medico, $statusCode);
    }

    /**
     * @Route("/medicos/{id}", methods={"PUT"})
     */
    public function atualizarMedico(int $id, Request $req) : Response
    {
        $corpoRequest = $req->getContent();
        $medicoEnviado = $this->medicoFactory->criarMedico($corpoRequest);

        $medicoExistente = $this->buscarMedico($id);

        if (empty($medicoExistente) || is_null($medicoExistente)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $medicoExistente->setCrm($medicoEnviado->getCrm())
                        ->setNome($medicoEnviado->getNome());

        $this->entityManager->flush();

        return new JsonResponse($medicoExistente);
    }

    /**
     * @Route("medicos/{id}", methods={"DELETE"})
     */
    public function deletarMedico(int $id) : Response
    {
        $medico = $this->buscarMedico($id);
        $this->entityManager->remove($medico);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    public function buscarMedico(int $id)
    {
        $repositorioDeMedicos = $this->getDoctrine()->getRepository(Medico::class);
        $medico = $repositorioDeMedicos->find($id);    

        return $medico;
    }
}