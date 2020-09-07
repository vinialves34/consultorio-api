<?php

namespace App\Controller;

use App\Helper\EntidadeFactory;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseController extends AbstractController
{
    /**
     * @var ObjectRepository
     */
    protected $repository;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var EntidadeFactory
     */
    protected $factory;

    public function __construct(ObjectRepository $repository, EntityManagerInterface $entityManager, EntidadeFactory $factory)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->factory = $factory;
    }

    public function novo(Request $req) : Response
    {
        $dadosRequest = $req->getContent();
        $entidade = $this->factory->criarEntidade($dadosRequest);

        $this->entityManager->persist($entidade);
        $this->entityManager->flush();

        return new JsonResponse($entidade);
    }

    public function atualizar(int $id, Request $req) : Response
    {
        $dadosRequest = $req->getContent();
        $entidadeEnviada = $this->factory->criarEntidade($dadosRequest);
        $entidadeExistente = $this->repository->find($id);

        if (empty($entidadeExistente) || is_null($entidadeExistente)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $this->atualizarEntidadeExistente($entidadeExistente, $entidadeEnviada);
        $this->entityManager->flush();

        return new JsonResponse($entidadeExistente);
    }

    public function buscarTodos(Request $request) : Response
    {
        $dadosOrdenacao = $request->query->get('sort');
        $entityList = $this->repository->findBy([], $dadosOrdenacao);

        return new JsonResponse($entityList);
    }

    public function buscar(int $id) : Response
    {
        return new JsonResponse($this->repository->find($id));
    }

    public function deletar(int $id) : Response
    {
        $entidade = $this->repository->find($id);
        $this->entityManager->remove($entidade);
        $this->entityManager->flush();
        
        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }

    abstract public function atualizarEntidadeExistente($entidadeExistente, $entidadeEnviada);
}
