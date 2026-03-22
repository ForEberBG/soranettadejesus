<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Services\SunatService;
use App\Models\Mesa;
use App\Models\Cliente;
use App\Models\DetalleVenta;
use App\Models\Plato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\FacturacionService;
use Illuminate\Support\Facades\Storage;
use App\Services\SunatFirmaService;
use App\Services\SunatEnvioService;

class VentaController extends Controller
{
    // Vista de creación de venta
    public function create()
    {
        $mesas = Mesa::all(); // Obtener mesas
        $clientes = Cliente::all(); // Obtener clientes
        $platos = Plato::all(); // Obtener platos
        return view('admin.ventas.create', compact('mesas', 'clientes', 'platos'));
    }

    // Almacenar la venta
    public function store(Request $request)
    {
        // Validar la entrada del formulario
        $request->validate([
            'mesa_id' => 'nullable|exists:mesas,id',
            'cliente_id' => 'required|exists:clientes,id',
            'tipo' => 'required|in:mesa,llevar,delivery',
            'metodo_pago' => 'required|in:efectivo,yape,plin,tarjeta,qr',
            'estado' => 'required|in:pendiente,pagado',
            'fecha' => 'required|date',
            'plato_id' => 'required|array',
            'plato_id.*' => 'exists:platos,id',
            'cantidad' => 'required|array',
            'cantidad.*' => 'numeric|min:1',
            'precio_unitario' => 'required|array',
            'precio_unitario.*' => 'numeric|min:0',
            'subtotal' => 'required|array',
            'subtotal.*' => 'numeric|min:0',
        ]);

        // Iniciar una transacción para asegurar la integridad de los datos
        DB::beginTransaction();

        try {
            // 1. Crear la venta
            $venta = Venta::create([
                'mesa_id' => $request->mesa_id,
                'cliente_id' => $request->cliente_id,
                'usuario_id' => auth()->user()->id,
                'tipo' => $request->tipo,
                'metodo_pago' => $request->metodo_pago,
                'estado' => $request->estado,
                'fecha' => $request->fecha,
                'total' => array_sum($request->subtotal), // Sumar los subtotales
            ]);

            // 2. Registrar los detalles de la venta
            foreach ($request->plato_id as $index => $platoId) {
                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'plato_id' => $platoId,
                    'cantidad' => $request->cantidad[$index],
                    'precio_unitario' => $request->precio_unitario[$index],
                    'subtotal' => $request->subtotal[$index],
                ]);
            }

            // 3. Confirmar la transacción
            DB::commit();
            return redirect()->route('admin.ventas.index')->with('success', 'Venta registrada correctamente');
        } catch (\Exception $e) {
            // Si hay algún error, revertimos la transacción
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al registrar la venta: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $ventas = Venta::with(['cliente', 'mesa', 'detalleVenta.plato','pagos'])
            ->orderByDesc('id')
            ->get();

        return view('admin.ventas.index', compact('ventas'));
    }

    // Editar una venta
    public function edit(Venta $venta)
    {
        $mesas = Mesa::all(); // Obtener mesas
        $clientes = Cliente::all(); // Obtener clientes
        $platos = Plato::all(); // Obtener platos
        return view('admin.ventas.edit', compact('venta', 'mesas', 'clientes', 'platos'));
    }

    // Actualizar la venta
    public function update(Request $request, Venta $venta)
    {
        // Validar la entrada del formulario
        $request->validate([
            'mesa_id' => 'nullable|exists:mesas,id',
            'cliente_id' => 'required|exists:clientes,id',
            'tipo' => 'required|in:mesa,llevar,delivery',
            'metodo_pago' => 'required|in:efectivo,yape,plin,tarjeta,qr',
            'estado' => 'required|in:pendiente,pagado',
            'fecha' => 'required|date',
            'plato_id' => 'required|array',
            'plato_id.*' => 'exists:platos,id',
            'cantidad' => 'required|array',
            'cantidad.*' => 'numeric|min:1',
            'precio_unitario' => 'required|array',
            'precio_unitario.*' => 'numeric|min:0',
            'subtotal' => 'required|array',
            'subtotal.*' => 'numeric|min:0',
        ]);

        // Iniciar una transacción para asegurar la integridad de los datos
        DB::beginTransaction();

        try {
            // 2. Actualizar la venta
            $venta->update([
                'mesa_id' => $request->mesa_id,
                'cliente_id' => $request->cliente_id,
                'usuario_id' => auth()->user()->id,
                'tipo' => $request->tipo,
                'metodo_pago' => $request->metodo_pago,
                'estado' => $request->estado,
                'fecha' => $request->fecha,
                'total' => array_sum($request->subtotal),
            ]);

            // Eliminar los detalles anteriores
            $venta->detalleVenta()->delete();

            // 3. Registrar los nuevos detalles de la venta
            foreach ($request->plato_id as $index => $platoId) {
                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'plato_id' => $platoId,
                    'cantidad' => $request->cantidad[$index],
                    'precio_unitario' => $request->precio_unitario[$index],
                    'subtotal' => $request->subtotal[$index],
                ]);
            }

            // 4. Confirmar la transacción
            DB::commit();
            return redirect()->route('admin.ventas.index')->with('success', 'Venta actualizada correctamente');
        } catch (\Exception $e) {
            // Si hay algún error, revertimos la transacción
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al actualizar la venta: ' . $e->getMessage());
        }
    }
    public function show($id)
    {
        $venta = Venta::findOrFail($id);
        return view('admin.ventas.show', compact('venta'));
    }
    public function generarFactura(Venta $venta)
    {
        // Cargar la vista con los detalles de la venta
        $pdf = PDF::loadView('admin.ventas.factura', compact('venta'));

        // Generar el PDF y devolverlo para su descarga
        return $pdf->stream('factura_venta_' . $venta->id . '.pdf');
    }

    // Eliminar una venta
    public function destroy(Venta $venta)
    {
        // Iniciar una transacción para asegurar la integridad de los datos
        DB::beginTransaction();

        try {
            // Eliminar los detalles de la venta
            $venta->detalleVenta()->delete();

            // Finalmente, eliminar la venta
            $venta->delete();

            DB::commit();
            return redirect()->route('admin.ventas.index')->with('success', 'Venta eliminada correctamente');
        } catch (\Exception $e) {
            // Si hay algún error, revertimos la transacción
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al eliminar la venta: ' . $e->getMessage());
        }
    }

    public function factura(Venta $venta)
    {
        $venta->load(['mesa', 'cliente', 'usuario', 'detalleVenta.plato', 'pagos']);

        $tipoComp = $venta->tipo_comprobante;
        $tipoLabel = match ($tipoComp) {
            'factura'    => 'FACTURA ELECTRÓNICA',
            'boleta'     => 'BOLETA DE VENTA',
            'nota_venta' => 'NOTA DE VENTA',
            default      => 'COMPROBANTE',
        };
        $nombreArchivo = match ($tipoComp) {
            'factura'    => 'Factura',
            'boleta'     => 'Boleta',
            default      => 'NotaVenta',
        };

        $serie       = $venta->serie ?? 'NV01';
        $correlativo = $venta->correlativo ?? str_pad($venta->id, 8, '0', STR_PAD_LEFT);
        $doc         = $venta->cliente->documento ?? '';

        // Sin IGV (exonerado)
        $totalBase = $venta->total;
        $totalIgv  = 0;

        $config = \App\Models\Configuracion::first();

        $pdf = Pdf::loadView('admin.ventas.factura_pdf', compact(
            'venta',
            'tipoLabel',
            'serie',
            'correlativo',
            'doc',
            'totalBase',
            'totalIgv',
            'config'
        ))->setPaper([0, 0, 226.77, 600], 'portrait');

        return $pdf->download("{$nombreArchivo}_{$venta->id}.pdf");
    }

    public function generarXmlLocal(Venta $venta)
    {
        $venta->load(['cliente', 'detalleVenta.plato']);

        if ($venta->tipo_comprobante == 'nota_venta') {
            return $this->notaVentaPdf($venta);
        }

        $config = \App\Models\Configuracion::first();

        $see = new \Greenter\See();
        $see->setCertificate(file_get_contents(storage_path('app/certificado.pem')));
        $see->setService(\Greenter\Ws\Services\SunatEndpoints::FE_PRODUCCION);
        $see->setClaveSOL(env('SUNAT_RUC'), env('SUNAT_USUARIO'), env('SUNAT_PASSWORD'));

        $company = (new \Greenter\Model\Company\Company())
            ->setRuc(env('SUNAT_RUC'))
            ->setRazonSocial($config->nombre)
            ->setAddress((new \Greenter\Model\Company\Address())
                ->setUbigueo('150101')
                ->setDepartamento('LIMA')
                ->setProvincia('LIMA')
                ->setDistrito('LIMA')
                ->setUrbanizacion('NONE')
                ->setDireccion($config->direccion));

        // ── Detectar tipo de documento del cliente ──
        $docCliente = $venta->cliente->documento ?? '';
        if (strlen($docCliente) == 11) {
            $tipoDocCliente = '6'; // RUC
            $tipoComprobante = 'factura';
            $tipoDoc = '01';
            $serie = 'F001';
        } elseif (strlen($docCliente) == 8) {
            $tipoDocCliente = '1'; // DNI
            $tipoComprobante = 'boleta';
            $tipoDoc = '03';
            $serie = 'B001';
        } else {
            // Sin documento → Nota de Venta, no genera XML
            $venta->update([
                'tipo_comprobante' => 'nota_venta',
                'serie'            => 'NV01',
                'correlativo'      => str_pad($venta->id, 8, '0', STR_PAD_LEFT),
            ]);
            return response()->json([
                'success' => true,
                'mensaje' => 'Nota de Venta generada correctamente'
            ]);
        }

        $details = [];
        foreach ($venta->detalleVenta as $d) {
            $baseIgv = round($d->subtotal / 1.18, 2);
            $igv     = round($d->subtotal - $baseIgv, 2);
            $details[] = (new \Greenter\Model\Sale\SaleDetail())
                ->setCantidad($d->cantidad)
                ->setUnidad('NIU')
                ->setDescripcion($d->plato->nombre)
                ->setMtoBaseIgv($baseIgv)
                ->setPorcentajeIgv(18.0)
                ->setIgv($igv)
                ->setTipAfeIgv('10')
                ->setMtoValorVenta($baseIgv)
                ->setMtoPrecioUnitario($d->precio_unitario)
                ->setMtoValorUnitario(round($d->precio_unitario / 1.18, 2))
                ->setTotalImpuestos($igv);
        }

        $totalBase = round($venta->total / 1.18, 2);
        $totalIgv  = round($venta->total - $totalBase, 2);
        $correlativo = str_pad($venta->id, 8, '0', STR_PAD_LEFT);

        $invoice = (new \Greenter\Model\Sale\Invoice())
            ->setUblVersion('2.1')
            ->setTipoOperacion('0101')
            ->setTipoDoc($tipoDoc)
            ->setSerie($serie)
            ->setCorrelativo($correlativo)
            ->setFechaEmision(new \DateTime($venta->fecha))
            ->setTipoMoneda('PEN')
            ->setCompany($company)
            ->setFormaPago(new \Greenter\Model\Sale\FormaPagos\FormaPagoContado())
            ->setClient((new \Greenter\Model\Client\Client())
                ->setTipoDoc($tipoDocCliente)
                ->setNumDoc($docCliente)
                ->setRznSocial($venta->cliente->nombre ?? 'Consumidor Final'))
            ->setMtoOperGravadas($totalBase)
            ->setMtoIGV($totalIgv)
            ->setTotalImpuestos($totalIgv)
            ->setValorVenta($totalBase)
            ->setSubTotal($venta->total)
            ->setMtoImpVenta($venta->total)
            ->setDetails($details);

        $xml = $see->getXmlSigned($invoice);

        // ── Guardar XML con nombre según tipo ──
        $nombreArchivo = strtoupper($tipoComprobante) . "_{$venta->id}.xml";
        $ruta = "public/xml/$nombreArchivo";
        Storage::put($ruta, $xml);

        // ── Guardar tipo, serie y correlativo en BD ──
        $venta->update([
            'xml_path'         => $ruta,
            'tipo_comprobante' => $tipoComprobante,
            'serie'            => $serie,
            'correlativo'      => $correlativo,
        ]);

        return Storage::download($ruta);
    }

    public function enviarSunat($id)
    {
        $venta = Venta::findOrFail($id);
        $xmlPath = storage_path("app/" . $venta->xml_path);

        if (!file_exists($xmlPath)) {
            return response()->json([
                'success' => false,
                'mensaje' => 'XML no encontrado. Genera el XML primero.'
            ]);
        }

        $sunat = new SunatService();
        $respuesta = $sunat->enviar($xmlPath);

        if ($respuesta['estado'] == 'aceptado') {
            $venta->estado_sunat = 'aceptado';
            $venta->save();
            return response()->json([
                'success' => true,
                'mensaje' => $respuesta['descripcion']
            ]);
        }

        return response()->json([
            'success' => false,
            'mensaje' => $respuesta['mensaje']
        ]);
    }

    public function notaVentaPdf(Venta $venta)
    {
        $venta->load(['mesa', 'cliente', 'detalleVenta.plato', 'pagos']);
        $pdf = Pdf::loadView('admin.ventas.nota_venta_pdf', compact('venta'))
            ->setPaper([0, 0, 226.77, 600], 'portrait');
        return $pdf->download("NotaVenta_{$venta->id}.pdf");
    }
}
