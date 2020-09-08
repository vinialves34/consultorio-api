<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\Request;

class ExtratorDadosRequest
{
    public function buscaDadosRequest(Request $request)
    {
        $dadosRequest = $request->query->all();
        $dadosOrdenacao = array_key_exists('sort', $dadosRequest) ? $dadosRequest['sort'] : null;
        unset($dadosRequest['sort']);

        $paginaAtual = array_key_exists('page', $dadosRequest) ? $dadosRequest['page'] : 1;
        unset($dadosRequest['page']);

        $itensPorPagina = array_key_exists('itensPorPagina', $dadosRequest) ? $dadosRequest['itensPorPagina'] : 5;
        unset($dadosRequest['itensPorPagina']);

        return [$dadosOrdenacao, $dadosRequest, $paginaAtual, $itensPorPagina];
    }

    public function buscaDadosOrdenacao(Request $request)
    {
        [$dadosOrdenacao, ] = $this->buscaDadosRequest($request);

        return $dadosOrdenacao;
    }

    public function buscaDadosFiltro(Request $request)
    {
        [, $dadosFiltro] = $this->buscaDadosRequest($request);

        return $dadosFiltro;
    }

    public function buscaDadosPaginacao(Request $request)
    {
        [, , $paginaAtual, $itensPorPagina] = $this->buscaDadosRequest($request);

        return [$paginaAtual, $itensPorPagina];
    }
}