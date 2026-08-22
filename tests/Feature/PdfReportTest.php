<?php

namespace Tests\Feature;

use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PdfReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_puede_descargar_factura_pdf()
    {
        $user = User::factory()->create();
        $pedido = Pedido::create([
            'usuario_id' => $user->id,
            'numero_seguimiento' => 'VB-TEST01',
            'estado' => 'delivered',
            'subtotal' => 25000,
            'impuesto' => 3250,
            'costo_envio' => 0,
            'total' => 28250,
            'metodo_pago' => 'card',
            'estado_pago' => 'paid',
            'direccion_envio' => 'San Jose, Costa Rica'
        ]);

        $response = $this->actingAs($user)->get(route('pedido.factura', $pedido));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_usuario_puede_generar_reporte_ventas_pdf()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('reportes.ventas', ['mes' => date('n'), 'anio' => date('Y')]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_usuario_puede_generar_reporte_cliente_pdf()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('reportes.cliente'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
