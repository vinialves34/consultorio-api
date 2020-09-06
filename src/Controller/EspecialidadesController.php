<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Helper\EspecialidadeFactory;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EspecialidadesController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var EspecialidadeFactory
     */
    private $especialidadeFactory;

    /**
     * @var EspecialidadeRepository
     */
    private $especialidadeRepository;

    public function __construct(EntityManagerInterface $entityManager, EspecialidadeFactory $especialidadeFactory, EspecialidadeRepository $especialidadeRepository)
    {
        $this->entityManager = $entityManager;
        $this->especialidadeFactory = $especialidadeFactory;
        $this->especialidadeRepository = $especialidadeRepository;
    }

    /**
     * @Route("/especialidades", methods={"POST"})
     */
    public function novo(Request $req) : Response
    {
        $contentReq = $req->getContent();
        $especialidade = $this->especialidadeFactory->criarEspecialidade($contentReq);

        $this->entityManager->persist($especialidade);
        $this->entityManager->flush();

        return new JsonResponse($especialidade);
    }

    /**
     * @Route("/especialidades", methods={"GET"})
     */
    public function buscarTodas() : Response
    {
        $especialidadesRetorno = $this->especialidadeRepository->findAll();

        if (empty($especialidadesRetorno) || is_null($especialidadesRetorno)) {
            $especialidadesRetorno = [
                "StatusCode" => Response::HTTP_NO_CONTENT,
                "Msg" => "Não contém especialidades cadastradas"
            ];
        }

        return new JsonResponse($especialidadesRetorno);
    }

    /**
     *@Route("/especialidades/{id}", methods={"GET"})
     */
    public function buscar(int $id) : Response
    {
        $especialidade = $this->buscarEspecialidade($id);
        $statusCode = (!empty($especialidade) || !is_null($especialidade)) ? Response::HTTP_OK : Response::HTTP_NOT_FOUND;

        return new JsonResponse($especialidade, $statusCode);
    }

    /**
     *@Route("/especialidades/{id}", methods={"PUT"})
     */
    public function atualizarEspecialidade(int $id, Request $req) : Response
    {
        $contentReq = $req->getContent();
        $especialidade = $this->especialidadeFactory->criarEspecialidade($contentReq);
        $especialidadeExistente = $this->buscarEspecialidade($id);

        if (empty($especialidadeExistente) || is_null($especialidadeExistente)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $especialidadeExistente->setDescricao($especialidade->getDescricao());
        $this->entityManager->flush();

        return new JsonResponse($especialidadeExistente);
    }

    /**
     *@Route("/especialidades/{id}", methods={"DELETE"})
     */
    public function deletar(int $id) : Response
    {
        $especialidade = $this->buscarEspecialidade($id);
        $this->entityManager->remove($especialidade);
        $this->entityManager->flush();
        
        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }

    public function buscarEspecialidade(int $id)
    {
        $especialidade = $this->especialidadeRepository->find($id);

        return $especialidade;
    }
}
