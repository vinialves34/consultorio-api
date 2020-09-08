<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseFactory
{
    public function __construct(bool $sucesso, $conteudoResposta, int $statusResponse = Response::HTTP_OK, int $paginaAtual = null, int $itensPorPagina = null)
    {
        $this->sucesso = $sucesso;
        $this->paginaAtual = $paginaAtual;
        $this->statusResponse = $statusResponse;
        $this->itensPorPagina = $itensPorPagina;
        $this->conteudoResposta = $conteudoResposta;
    }

    public function getResponse() : JsonResponse
    {
        $conteudoResposta = [
            'sucesso' => $this->sucesso,
            'paginaAtual' => $this->paginaAtual,
            'itensPorPagina' => $this->itensPorPagina,
            'conteudoResposta' => $this->conteudoResposta
        ];

        if (is_null($this->paginaAtual)) {
            unset($conteudoResposta['paginaAtual']);
            unset($conteudoResposta['itensPorPagina']);
        }

        return new JsonResponse($conteudoResposta, $this->statusResponse);
    }
}