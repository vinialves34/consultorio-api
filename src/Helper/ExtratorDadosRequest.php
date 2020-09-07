<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\Request;

class ExtratorDadosRequest
{
    public function buscaDadosRequest(Request $request)
    {
        $dadosOrdenacao = $request->query->get('sort');
        $dadosFiltro = $request->query->all();
        unset($dadosFiltro['sort']);

        return [$dadosOrdenacao, $dadosFiltro];
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
}