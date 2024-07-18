<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CepController extends Controller
{
    public function searchCeps($ceps)
    {
        $cepArray = explode(',', $ceps);
        $results = [];

        foreach ($cepArray as $cep) {
            $response = Http::withoutVerifying()->get("https://viacep.com.br/ws/{$cep}/json/");
            $data = $response->json();

            if (!isset($data['erro'])) {
                $formattedData = [
                    'cep' => str_replace('-','',$data['cep']),
                    'label' => "{$data['logradouro']}, {$data['localidade']}",
                    'logradouro' => $data['logradouro'],
                    'complemento' => isset($data['complemento']) ? $data['complemento'] : null,
                    'bairro' => $data['bairro'],
                    'localidade' => $data['localidade'],
                    'uf' => $data['uf'],
                    'ibge' => $data['ibge'],
                    'gia' => isset($data['gia']) ? $data['gia'] : null,
                    'ddd' => $data['ddd'],
                    'siafi' => $data['siafi']
                ];

                $results[] = $formattedData;
            }
        }

        return response()->json($results);
    }
}
