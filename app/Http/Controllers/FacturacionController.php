<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class FacturacionController extends Controller
{
    /**
     * Genera PDF y XML de la venta, solo si no existen aún.
     */
    public function generarFactura(Venta $venta)
    {
        $venta->load('cliente', 'mesa', 'usuario', 'detalles.producto');

        $pdfPath = "public/facturas/{$venta->tipo}/venta_{$venta->id}.pdf";
        $xmlPath = "public/facturas/{$venta->tipo}/venta_{$venta->id}.xml";

        // Verificar si ya existe
        $yaExiste = Storage::exists($pdfPath) && Storage::exists($xmlPath);

        if (!$yaExiste) {
            // Generar PDF
            $pdf = Pdf::loadView('facturas.plantilla', [
                'venta' => $venta
            ]);
            Storage::put($pdfPath, $pdf->output());

            // Generar XML
            $xmlContent = view('facturas.xml', [
                'venta' => $venta
            ])->render();
            Storage::put($xmlPath, $xmlContent);
        }

        return back()->with('mensaje', $yaExiste ? 'Factura ya existente.' : 'Factura generada correctamente.')
                     ->with('pdf_url', Storage::url($pdfPath))
                     ->with('xml_url', Storage::url($xmlPath));
    }
}
