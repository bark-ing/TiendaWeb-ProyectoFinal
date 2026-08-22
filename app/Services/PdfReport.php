<?php

namespace App\Services;

if (!defined('FPDF_FONTPATH')) {
    define('FPDF_FONTPATH', app_path('Services/FPDF/font/'));
}

require_once app_path('Services/FPDF/fpdf.php');

use App\Models\Pedido;
use App\Models\User;
use FPDF;

class PdfReport extends FPDF
{
    protected string $reportTitle = '';

    public function __construct(string $reportTitle = 'Reporte', string $orientation = 'P', string $unit = 'mm', string $size = 'A4')
    {
        parent::__construct($orientation, $unit, $size);
        $this->reportTitle = $reportTitle;
        $this->SetAutoPageBreak(true, 25);
        $this->AliasNbPages();
    }

    public function setReportTitle(string $title): void
    {
        $this->reportTitle = $title;
    }

    protected function encode(string $text): string
    {
        $text = str_replace('₡', 'CRC ', $text);
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $text) ?: utf8_decode($text);
    }

    public function Header(): void
    {
        // Top branding bar (Purple theme for Vexians Boutique)
        $this->SetFillColor(109, 40, 217);
        $this->Rect(0, 0, 210, 8, 'F');

        $this->Ln(4);
        // Header store title
        $this->SetFont('Helvetica', 'B', 18);
        $this->SetTextColor(109, 40, 217);
        $this->Cell(120, 10, $this->encode('VEXIANS BOUTIQUE'), 0, 0, 'L');

        // Document date
        $this->SetFont('Helvetica', 'I', 9);
        $this->SetTextColor(100, 116, 139);
        $this->Cell(70, 10, $this->encode('Fecha: ' . date('d/m/Y H:i')), 0, 1, 'R');

        // Document Title
        $this->SetFont('Helvetica', 'B', 12);
        $this->SetTextColor(30, 41, 59);
        $this->Cell(120, 6, $this->encode($this->reportTitle), 0, 0, 'L');

        $this->SetFont('Helvetica', '', 9);
        $this->SetTextColor(100, 116, 139);
        $this->Cell(70, 6, $this->encode('Costa Rica'), 0, 1, 'R');

        // Line separator
        $this->SetDrawColor(226, 232, 240);
        $this->SetLineWidth(0.5);
        $this->Line(10, 32, 200, 32);
        $this->Ln(6);
    }

    public function Footer(): void
    {
        $this->SetY(-20);
        $this->SetDrawColor(226, 232, 240);
        $this->SetLineWidth(0.3);
        $this->Line(10, 277, 200, 277);

        $this->SetFont('Helvetica', '', 8);
        $this->SetTextColor(148, 163, 184);
        $this->Cell(130, 8, $this->encode('Vexians Boutique - Tienda Virtual de Moda | San José, Costa Rica'), 0, 0, 'L');
        $this->Cell(60, 8, $this->encode('Página ' . $this->PageNo() . ' de {nb}'), 0, 0, 'R');
    }

    public function buildFactura(Pedido $pedido): void
    {
        $this->setReportTitle('FACTURA DE COMPRA - ' . $pedido->numero_seguimiento);
        $this->AddPage();

        // Customer & Order Metadata Grid
        $this->SetFillColor(248, 250, 252);
        $this->SetDrawColor(226, 232, 240);
        $this->Rect(10, 36, 190, 36, 'DF');

        $this->SetY(38);
        $this->SetFont('Helvetica', 'B', 10);
        $this->SetTextColor(109, 40, 217);
        $this->Cell(95, 6, $this->encode('INFORMACIÓN DEL CLIENTE'), 0, 0, 'L');
        $this->Cell(95, 6, $this->encode('DETALLES DEL PEDIDO'), 0, 1, 'L');

        $this->SetFont('Helvetica', '', 9);
        $this->SetTextColor(51, 65, 85);

        $nombreCliente = $pedido->usuario ? $pedido->usuario->name : 'Cliente General';
        $emailCliente = $pedido->usuario ? $pedido->usuario->email : 'N/A';

        $this->Cell(95, 5, $this->encode('Cliente: ' . $nombreCliente), 0, 0, 'L');
        $this->Cell(95, 5, $this->encode('No. Seguimiento: ' . $pedido->numero_seguimiento), 0, 1, 'L');

        $this->Cell(95, 5, $this->encode('Email: ' . $emailCliente), 0, 0, 'L');
        $this->Cell(95, 5, $this->encode('Fecha: ' . $pedido->created_at->format('d/m/Y H:i')), 0, 1, 'L');

        $this->Cell(95, 5, $this->encode('Método Pago: ' . ($pedido->metodo_pago === 'paypal' ? 'PayPal' : 'Tarjeta (Simulado)')), 0, 0, 'L');
        $this->Cell(95, 5, $this->encode('Estado Pago: ' . $pedido->estado_pago_label), 0, 1, 'L');

        $this->Cell(190, 5, $this->encode('Dirección de Envío: ' . $pedido->direccion_envio), 0, 1, 'L');

        $this->Ln(6);

        // Products Table Header
        $this->SetFillColor(109, 40, 217);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Helvetica', 'B', 9);

        $this->Cell(75, 8, $this->encode(' Producto'), 1, 0, 'L', true);
        $this->Cell(25, 8, $this->encode('Talla'), 1, 0, 'C', true);
        $this->Cell(25, 8, $this->encode('Color'), 1, 0, 'C', true);
        $this->Cell(20, 8, $this->encode('Cant.'), 1, 0, 'C', true);
        $this->Cell(22, 8, $this->encode('Precio'), 1, 0, 'R', true);
        $this->Cell(23, 8, $this->encode('Subtotal '), 1, 1, 'R', true);

        // Products Table Rows
        $this->SetTextColor(51, 65, 85);
        $this->SetFont('Helvetica', '', 8.5);
        $fill = false;

        foreach ($pedido->items as $item) {
            $this->SetFillColor($fill ? 248 : 255, $fill ? 250 : 255, $fill ? 252 : 255);
            $nombreProd = $item->producto ? $item->producto->nombre : 'Producto #' . $item->producto_id;

            $this->Cell(75, 7, $this->encode(' ' . $nombreProd), 1, 0, 'L', true);
            $this->Cell(25, 7, $this->encode($item->talla ?: '-'), 1, 0, 'C', true);
            $this->Cell(25, 7, $this->encode($item->color ?: '-'), 1, 0, 'C', true);
            $this->Cell(20, 7, $item->cantidad, 1, 0, 'C', true);
            $this->Cell(22, 7, 'CRC ' . number_format($item->precio, 0, ',', '.'), 1, 0, 'R', true);
            $this->Cell(23, 7, 'CRC ' . number_format($item->precio * $item->cantidad, 0, ',', '.') . ' ', 1, 1, 'R', true);

            $fill = !$fill;
        }

        $this->Ln(4);

        // Totals Box
        $this->SetY($this->GetY());
        $this->SetX(110);
        $this->SetFont('Helvetica', '', 9);

        $this->Cell(45, 6, $this->encode('Subtotal:'), 0, 0, 'R');
        $this->Cell(35, 6, 'CRC ' . number_format($pedido->subtotal, 0, ',', '.'), 0, 1, 'R');

        $this->SetX(110);
        $this->Cell(45, 6, $this->encode('IVA (13%):'), 0, 0, 'R');
        $this->Cell(35, 6, 'CRC ' . number_format($pedido->impuesto, 0, ',', '.'), 0, 1, 'R');

        $this->SetX(110);
        $this->Cell(45, 6, $this->encode('Costo Envío:'), 0, 0, 'R');
        $this->Cell(35, 6, $pedido->costo_envio > 0 ? 'CRC ' . number_format($pedido->costo_envio, 0, ',', '.') : $this->encode('Gratis'), 0, 1, 'R');

        $this->SetLineWidth(0.4);
        $this->SetDrawColor(109, 40, 217);
        $this->Line(135, $this->GetY() + 1, 190, $this->GetY() + 1);

        $this->SetY($this->GetY() + 2);
        $this->SetX(110);
        $this->SetFont('Helvetica', 'B', 11);
        $this->SetTextColor(109, 40, 217);
        $this->Cell(45, 7, $this->encode('TOTAL PAGADO:'), 0, 0, 'R');
        $this->Cell(35, 7, 'CRC ' . number_format($pedido->total, 0, ',', '.'), 0, 1, 'R');
    }

    public function buildReporteVentas(int $mes, int $anio, $pedidos, float $totalVendido, float $promedioVenta): void
    {
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        $nombreMes = $meses[$mes] ?? 'Mes ' . $mes;

        $this->setReportTitle('REPORTE DE VENTAS - ' . strtoupper($nombreMes) . ' ' . $anio);
        $this->AddPage();

        // Summary KPI Box
        $this->SetFillColor(243, 232, 255);
        $this->SetDrawColor(216, 180, 254);
        $this->Rect(10, 36, 190, 24, 'DF');

        $this->SetY(40);
        $this->SetFont('Helvetica', 'B', 10);
        $this->SetTextColor(109, 40, 217);

        $this->Cell(63, 5, $this->encode('TOTAL VENTAS'), 0, 0, 'C');
        $this->Cell(63, 5, $this->encode('CANTIDAD DE PEDIDOS'), 0, 0, 'C');
        $this->Cell(64, 5, $this->encode('PROMEDIO POR PEDIDO'), 0, 1, 'C');

        $this->SetFont('Helvetica', 'B', 12);
        $this->SetTextColor(30, 41, 59);

        $this->Cell(63, 8, 'CRC ' . number_format($totalVendido, 0, ',', '.'), 0, 0, 'C');
        $this->Cell(63, 8, count($pedidos), 0, 0, 'C');
        $this->Cell(64, 8, 'CRC ' . number_format($promedioVenta, 0, ',', '.'), 0, 1, 'C');

        $this->Ln(8);

        // Sales List Table Header
        $this->SetFillColor(109, 40, 217);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Helvetica', 'B', 9);

        $this->Cell(30, 8, $this->encode('No. Seguimiento'), 1, 0, 'C', true);
        $this->Cell(50, 8, $this->encode('Cliente'), 1, 0, 'L', true);
        $this->Cell(30, 8, $this->encode('Fecha'), 1, 0, 'C', true);
        $this->Cell(25, 8, $this->encode('Método Pago'), 1, 0, 'C', true);
        $this->Cell(25, 8, $this->encode('Estado Pago'), 1, 0, 'C', true);
        $this->Cell(30, 8, $this->encode('Total '), 1, 1, 'R', true);

        // Table Rows
        $this->SetTextColor(51, 65, 85);
        $this->SetFont('Helvetica', '', 8.5);
        $fill = false;

        foreach ($pedidos as $pedido) {
            $this->SetFillColor($fill ? 248 : 255, $fill ? 250 : 255, $fill ? 252 : 255);
            $clienteNombre = $pedido->usuario ? $pedido->usuario->name : 'N/A';

            $this->Cell(30, 7, $this->encode($pedido->numero_seguimiento), 1, 0, 'C', true);
            $this->Cell(50, 7, $this->encode(substr($clienteNombre, 0, 28)), 1, 0, 'L', true);
            $this->Cell(30, 7, $pedido->created_at->format('d/m/Y H:i'), 1, 0, 'C', true);
            $this->Cell(25, 7, $this->encode($pedido->metodo_pago === 'paypal' ? 'PayPal' : 'Tarjeta'), 1, 0, 'C', true);
            $this->Cell(25, 7, $this->encode($pedido->estado_pago_label), 1, 0, 'C', true);
            $this->Cell(30, 7, 'CRC ' . number_format($pedido->total, 0, ',', '.') . ' ', 1, 1, 'R', true);

            $fill = !$fill;
        }

        if (count($pedidos) === 0) {
            $this->Cell(190, 10, $this->encode('No hay pedidos registrados en el período seleccionado.'), 1, 1, 'C');
        } else {
            $this->SetFont('Helvetica', 'B', 9);
            $this->SetFillColor(241, 245, 249);
            $this->Cell(160, 8, $this->encode('TOTAL GENERAL DEL MES: '), 1, 0, 'R', true);
            $this->Cell(30, 8, 'CRC ' . number_format($totalVendido, 0, ',', '.') . ' ', 1, 1, 'R', true);
        }
    }

    public function buildReporteCliente(User $cliente, $pedidos, float $totalHistorico): void
    {
        $this->setReportTitle('HISTORIAL DE COMPRAS DEL CLIENTE');
        $this->AddPage();

        // Customer Data Header Box
        $this->SetFillColor(248, 250, 252);
        $this->SetDrawColor(226, 232, 240);
        $this->Rect(10, 36, 190, 25, 'DF');

        $this->SetY(39);
        $this->SetFont('Helvetica', 'B', 10);
        $this->SetTextColor(109, 40, 217);
        $this->Cell(190, 5, $this->encode('DATOS DEL CLIENTE'), 0, 1, 'L');

        $this->SetFont('Helvetica', '', 9);
        $this->SetTextColor(51, 65, 85);
        $this->Cell(95, 5, $this->encode('Nombre: ' . $cliente->name), 0, 0, 'L');
        $this->Cell(95, 5, $this->encode('Total Histórico Gastado: CRC ' . number_format($totalHistorico, 0, ',', '.')), 0, 1, 'L');

        $this->Cell(95, 5, $this->encode('Email: ' . $cliente->email), 0, 0, 'L');
        $this->Cell(95, 5, $this->encode('Total de Pedidos Realizados: ' . count($pedidos)), 0, 1, 'L');

        $this->Ln(8);

        // Orders Table Header
        $this->SetFillColor(109, 40, 217);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Helvetica', 'B', 9);

        $this->Cell(35, 8, $this->encode('No. Seguimiento'), 1, 0, 'C', true);
        $this->Cell(35, 8, $this->encode('Fecha Pedido'), 1, 0, 'C', true);
        $this->Cell(30, 8, $this->encode('Estado'), 1, 0, 'C', true);
        $this->Cell(30, 8, $this->encode('Método Pago'), 1, 0, 'C', true);
        $this->Cell(30, 8, $this->encode('Estado Pago'), 1, 0, 'C', true);
        $this->Cell(30, 8, $this->encode('Total '), 1, 1, 'R', true);

        // Table Rows
        $this->SetTextColor(51, 65, 85);
        $this->SetFont('Helvetica', '', 8.5);
        $fill = false;

        foreach ($pedidos as $pedido) {
            $this->SetFillColor($fill ? 248 : 255, $fill ? 250 : 255, $fill ? 252 : 255);

            $this->Cell(35, 7, $this->encode($pedido->numero_seguimiento), 1, 0, 'C', true);
            $this->Cell(35, 7, $pedido->created_at->format('d/m/Y H:i'), 1, 0, 'C', true);
            $this->Cell(30, 7, $this->encode($pedido->estado_label), 1, 0, 'C', true);
            $this->Cell(30, 7, $this->encode($pedido->metodo_pago === 'paypal' ? 'PayPal' : 'Tarjeta'), 1, 0, 'C', true);
            $this->Cell(30, 7, $this->encode($pedido->estado_pago_label), 1, 0, 'C', true);
            $this->Cell(30, 7, 'CRC ' . number_format($pedido->total, 0, ',', '.') . ' ', 1, 1, 'R', true);

            $fill = !$fill;
        }

        if (count($pedidos) === 0) {
            $this->Cell(190, 10, $this->encode('El cliente no ha realizado pedidos aún.'), 1, 1, 'C');
        } else {
            $this->SetFont('Helvetica', 'B', 9);
            $this->SetFillColor(241, 245, 249);
            $this->Cell(160, 8, $this->encode('TOTAL HISTÓRICO ACUMULADO: '), 1, 0, 'R', true);
            $this->Cell(30, 8, 'CRC ' . number_format($totalHistorico, 0, ',', '.') . ' ', 1, 1, 'R', true);
        }
    }
}
