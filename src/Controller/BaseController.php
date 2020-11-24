<?php

namespace App\Controller;

use App\Helper\EntidadeFactory;
use App\Helper\ExtratorDadosRequest;
use App\Helper\ResponseFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseController extends AbstractController
{
    /** @var ObjectRepository */
    protected $repository;

    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var EntidadeFactory */
    protected $factory;

    /** @var ExtratorDadosRequest */
    protected $extratorDados;

    public function __construct(ObjectRepository $repository, EntityManagerInterface $entityManager, EntidadeFactory $factory, ExtratorDadosRequest $extratorDados)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->factory = $factory;
        $this->extratorDados = $extratorDados;
    }

    /**
     * Responsável em cadastrar novo registro
     * @param Request $req
     * @return JsonResponse
     */
    public function novo(Request $req): JsonResponse
    {
        $dadosRequest = $req->getContent();
        $entidade = $this->factory->criarEntidade($dadosRequest);

        $this->entityManager->persist($entidade);
        $this->entityManager->flush();

        return new JsonResponse($entidade);
    }

    /**
     * Responsável em atualizar um registro
     * @param int $id
     * @param Request $req
     * @return JsonResponse
     */
    public function atualizar(int $id, Request $req): JsonResponse
    {
        $dadosRequest = $req->getContent();
        $entidadeEnviada = $this->factory->criarEntidade($dadosRequest);
        $entidadeExistente = $this->repository->find($id);

        if (empty($entidadeExistente) || is_null($entidadeExistente)) {
            return new JsonResponse('', JsonResponse::HTTP_NOT_FOUND);
        }

        $this->atualizarEntidadeExistente($entidadeExistente, $entidadeEnviada);
        $this->entityManager->flush();

        return new JsonResponse($entidadeExistente);
    }

    /**
     * Responsável me buscar todos os registros
     * @param Request $request
     * @return JsonResponse
     */
    public function buscarTodos(Request $request): JsonResponse
    {
        $dadosOrdenacao = $this->extratorDados->buscaDadosOrdenacao($request);
        $dadosFiltro = $this->extratorDados->buscaDadosFiltro($request);
        [$paginaAtual, $itensPorPagina] = $this->extratorDados->buscaDadosPaginacao($request);
        $offset = ($paginaAtual - 1) * $itensPorPagina;
        $entityList = $this->repository->findBy($dadosFiltro, $dadosOrdenacao, $itensPorPagina, $offset);

        $factoryResponse = new ResponseFactory(true, $entityList, JsonResponse::HTTP_OK, $paginaAtual, $itensPorPagina);
        return $factoryResponse->getResponse();
    }

    /**
     * Responsável em buscar um registro especifico
     * @param int $id
     * @return JsonResponse
     */
    public function buscar(int $id): JsonResponse
    {
        $entidade = $this->repository->find($id);
        $statusResponse = is_null($entidade) ? JsonResponse::HTTP_NO_CONTENT : JsonResponse::HTTP_OK;
        $factoryResponse = new ResponseFactory(true, $entidade, $statusResponse);
        return $factoryResponse->getResponse();
    }

    /**
     * Responsável em excluir um registro
     * @param int $id
     * @return JsonResponse
     */
    public function deletar(int $id): JsonResponse
    {
        $entidade = $this->repository->find($id);
        $this->entityManager->remove($entidade);
        $this->entityManager->flush();
        
        return new JsonResponse('', JsonResponse::HTTP_NO_CONTENT);
    }

    abstract public function atualizarEntidadeExistente($entidadeExistente, $entidadeEnviada);
}
