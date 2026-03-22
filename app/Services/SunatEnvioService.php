<?php

namespace App\Services;

class SunatEnvioService
{
    public function enviar(string $xmlFirmado)
    {
        // ENVÍO SIMULADO
        return [
            'estado' => 'enviado',
            'mensaje' => 'Comprobante enviado correctamente (simulado)'
        ];
    }
}

