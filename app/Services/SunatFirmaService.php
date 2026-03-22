<?php

namespace App\Services;

class SunatFirmaService
{
    public function firmar(string $xml)
    {
        // Firma simulada por ahora (NO SUNAT aún)
        return base64_encode($xml);
    }
}
