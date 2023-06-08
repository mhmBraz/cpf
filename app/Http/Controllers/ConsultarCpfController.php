<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConsultarCpfRequest;
use App\Http\Services\ConsultarCpfGoSat;
use App\Http\Services\VerificarMelhoresOfertas;
use Illuminate\Support\Facades\Response;

class ConsultarCpfController extends Controller
{

    public function handler(ConsultarCpfRequest $request)
    {
        try {

            $requestGoSat = new ConsultarCpfGoSat();

            $responseInstituicoes = $requestGoSat->getcpf($request->get('cpf'));

            if (!is_array($responseInstituicoes)) {
                return Response::json([
                    'success' => false,
                    'message' => $responseInstituicoes,
                ], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
            }

            $ofertasPorInstituicoes = $requestGoSat->consultarCondicoesInstituicoes($responseInstituicoes, $request->get('cpf'));

            $melhoresOfertas = VerificarMelhoresOfertas::handler($ofertasPorInstituicoes);


            return Response::json([
                'success' => true,
                'message' => '',
                'data'    => $melhoresOfertas,
            ], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return Response::json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
        }
    }
}
