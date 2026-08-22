<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\User;
use App\Services\PdfReport;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function confirmacion(Pedido $pedido)
    {
        if ($pedido->usuario_id !== auth()->id()) {
            abort(403);
        }

        $pedido->load('items.producto');

        return view('checkout.confirmation', compact('pedido'));
    }

    public function index()
    {
        $pedidos = Pedido::where('usuario_id', auth()->id())
            ->with('items.producto')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('pedidos'));
    }

    public function ver(Pedido $pedido)
    {
        if ($pedido->usuario_id !== auth()->id()) {
            abort(403);
        }

        $pedido->load('items.producto');

        return view('orders.show', compact('pedido'));
    }

    public function factura(Pedido $pedido)
    {
        if ($pedido->usuario_id !== auth()->id()) {
            abort(403);
        }

        $pedido->load(['items.producto', 'usuario']);

        $pdf = new PdfReport('Factura de Compra');
        $pdf->buildFactura($pedido);

        $pdfContent = $pdf->Output('S');

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Factura_' . $pedido->numero_seguimiento . '.pdf"',
        ]);
    }

    public function reporteVentas(Request $request)
    {
        $mes = (int) $request->input('mes', date('n'));
        $anio = (int) $request->input('anio', date('Y'));

        $pedidos = Pedido::whereYear('created_at', $anio)
            ->whereMonth('created_at', $mes)
            ->with(['usuario', 'items.producto'])
            ->latest()
            ->get();

        $totalVendido = (float) $pedidos->sum('total');
        $cantPedidos = $pedidos->count();
        $promedioVenta = $cantPedidos > 0 ? $totalVendido / $cantPedidos : 0.0;

        $pdf = new PdfReport('Reporte de Ventas');
        $pdf->buildReporteVentas($mes, $anio, $pedidos, $totalVendido, $promedioVenta);

        $pdfContent = $pdf->Output('S');

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Reporte_Ventas_' . sprintf('%02d', $mes) . '_' . $anio . '.pdf"',
        ]);
    }

    public function reporteCliente(?User $usuario = null)
    {
        $targetUser = $usuario ?? auth()->user();

        if ($targetUser->id !== auth()->id()) {
            abort(403);
        }

        $pedidos = Pedido::where('usuario_id', $targetUser->id)
            ->with(['items.producto'])
            ->latest()
            ->get();

        $totalHistorico = (float) $pedidos->sum('total');

        $pdf = new PdfReport('Historial por Cliente');
        $pdf->buildReporteCliente($targetUser, $pedidos, $totalHistorico);

        $pdfContent = $pdf->Output('S');

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Historial_Compras_' . $targetUser->id . '.pdf"',
        ]);
    }
}

