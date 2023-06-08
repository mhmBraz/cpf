<?php

namespace App\Http\Services;

class ConsultarCpfGoSat
{
    public function getCpf(string $cpf)
    {
        return $this->curlGeneric('https://dev.gosat.org/api/v1/simulacao/credito', '{
            "cpf" : ' . $cpf . '
        }');
    }

    public function consultarCondicoesInstituicoes(array $array, string $cpf)
    {


        $arrayResultados = [];
        foreach ($array['instituicoes'] as $key => $instituicao) {
            foreach ($instituicao['modalidades'] as $keyModalidade => $modalidade) {
                $response =
                    $this->curlGeneric('https://dev.gosat.org/api/v1/simulacao/oferta', '{
                    "cpf" : ' . $cpf . ',
                    "instituicao_id": ' . $instituicao['id'] . ',
                    "codModalidade":' . '"' . $modalidade['cod'] . '"' .  '
                }');

                array_push($arrayResultados, [
                    'nomeInstituicao' => $instituicao['nome'],
                    'codModalidade' => $modalidade['cod'],
                    'nome' => $modalidade['nome'],
                    "valorMin" => $response['valorMin'] ?? 'Sem informação',
                    "valorMax" => $response['valorMax'] ?? 'Sem informação',
                    "jurosMes" => $response['jurosMes'] ?? 'Sem informação',
                    "QntParcelaMin" => $response['QntParcelaMin'] ?? 'Sem informação',
                    "QntParcelaMax" => $response['QntParcelaMax'] ?? 'Sem informação',
                ]);
            }
        }

        return $arrayResultados;
    }

    private function curlGeneric($url, $params)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response, true);
    }
}
