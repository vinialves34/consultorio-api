<?php

namespace App\Controller;

use App\Helper\EntidadeFactory;
use App\Helper\ExtratorDadosRequest;
use App\Helper\ResponseFactory;
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

    /**
     * @var ExtratorDadosRequest
     */
    protected $extratorDados;

    public function __construct(ObjectRepository $repository, EntityManagerInterface $entityManager, EntidadeFactory $factory, ExtratorDadosRequest $extratorDados)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->factory = $factory;
        $this->extratorDados = $extratorDados;
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
        $dadosOrdenacao = $this->extratorDados->buscaDadosOrdenacao($request);
        $dadosFiltro = $this->extratorDados->buscaDadosFiltro($request);
        [$paginaAtual, $itensPorPagina] = $this->extratorDados->buscaDadosPaginacao($request);
        $offset = ($paginaAtual - 1) * $itensPorPagina;
        $entityList = $this->repository->findBy($dadosFiltro, $dadosOrdenacao, $itensPorPagina, $offset);

        $factoryResponse = new ResponseFactory(true, $entityList, Response::HTTP_OK, $paginaAtual, $itensPorPagina);
        return $factoryResponse->getResponse();
    }

    public function buscar(int $id) : Response
    {
        $entidade = $this->repository->find($id);
        $statusResponse = is_null($entidade) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
        $factoryResponse = new ResponseFactory(true, $entidade, $statusResponse);
        return $factoryResponse->getResponse();
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
