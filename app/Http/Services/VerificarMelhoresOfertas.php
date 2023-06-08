<?php

namespace App\Http\Services;

class VerificarMelhoresOfertas
{
    static function handler(array $ofertasPorInstituicoes)
    {
        $posicaoOferta = 0;
        $melhorOfertaPorJuros = $ofertasPorInstituicoes[0];

        foreach ($ofertasPorInstituicoes as $key =>  $oferta) {
            if ($oferta['jurosMes'] < $melhorOfertaPorJuros['jurosMes']) {
                $melhorOfertaPorJuros = $oferta;
                $posicaoOferta = $key;
            }
        }

        array_splice($ofertasPorInstituicoes, $posicaoOferta, 1);
        $melhorOfertaPorValorMaximo = $ofertasPorInstituicoes[0];
        $posicaoOferta = 0;

        foreach ($ofertasPorInstituicoes as $key =>  $oferta) {
            if ($oferta['valorMax'] > $melhorOfertaPorValorMaximo['valorMax']) {
                $melhorOfertaPorValorMaximo = $oferta;
                $posicaoOferta = $key;
            }
        }

        array_splice($ofertasPorInstituicoes, $posicaoOferta, 1);
        $melhorOfertaPorPrazoMaximo = $ofertasPorInstituicoes[0];

        foreach ($ofertasPorInstituicoes as $key =>  $oferta) {
            if ($oferta['QntParcelaMax'] > $melhorOfertaPorPrazoMaximo['QntParcelaMax']) {
                $melhorOfertaPorPrazoMaximo = $oferta;
                $posicaoOferta = $key;
            }
        }

        return [
            $melhorOfertaPorJuros,
            $melhorOfertaPorValorMaximo,
            $melhorOfertaPorPrazoMaximo
        ];
    }
}
