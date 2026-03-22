<?php

namespace App\Exports;

use App\Models\Venta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VentasExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $desde;
    protected $hasta;
    protected $mozo_id;

    public function __construct($desde, $hasta, $mozo_id = null)
    {
        $this->desde   = $desde;
        $this->hasta   = $hasta;
        $this->mozo_id = $mozo_id;
    }

    public function collection()
    {
        $query = Venta::with(['cliente', 'usuario'])
            ->where('estado', 'pagado')
            ->whereDate('created_at', '>=', $this->desde)
            ->whereDate('created_at', '<=', $this->hasta);

        if ($this->mozo_id) {
            $query->where('usuario_id', $this->mozo_id);
        }

        return $query->orderByDesc('id')->get()->map(fn($v) => [
            'ID'           => $v->id,
            'Fecha'        => $v->created_at->format('d/m/Y H:i'),
            'Cliente'      => $v->cliente->nombre ?? 'Consumidor Final',
            'Documento'    => $v->cliente->documento ?? '—',
            'Comprobante'  => strtoupper($v->tipo_comprobante ?? '—') . ' ' . ($v->serie ?? '') . '-' . ($v->correlativo ?? ''),
            'Método Pago'  => ucfirst($v->metodo_pago),
            'Mozo'         => $v->usuario->name ?? '—',
            'Total'        => $v->total,
        ]);
    }

    public function headings(): array
    {
        return ['#', 'Fecha', 'Cliente', 'Documento', 'Comprobante', 'Método Pago', 'Mozo', 'Total (S/)'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF1A2E5A']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }

    public function title(): string
    {
        return 'Ventas ' . $this->desde . ' al ' . $this->hasta;
    }
}
