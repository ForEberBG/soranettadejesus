<?php

namespace App\Services;

use Greenter\See;
use Greenter\Ws\Services\SunatEndpoints;

class SunatService
{
    public function enviar($xmlPath)
    {
        $see = new See();
        $see->setService(SunatEndpoints::FE_BETA);
        $see->setClaveSOL(
            env('SUNAT_RUC'),
            env('SUNAT_USUARIO'),
            env('SUNAT_PASSWORD')
        );
        $see->setCertificate(file_get_contents(storage_path('app/certificado.pem')));

        $xml = file_get_contents($xmlPath);
        $result = $see->sendXmlFile($xml);

        if (!$result->isSuccess()) {
            return [
                'estado' => 'error',
                'mensaje' => $result->getError()->getMessage()
            ];
        }
        $cdr = $result->getCdrResponse();
        return [
            'estado' => 'aceptado',
            'codigo' => $cdr->getCode(),
            'descripcion' => $cdr->getDescription()
        ];
    }
}
