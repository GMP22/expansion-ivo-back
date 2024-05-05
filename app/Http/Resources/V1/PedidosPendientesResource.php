<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Pedidos;
use App\Models\Proveedor;
class PedidosPendientesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {  
        $nombre_proveedor = $this->proveedores[0]->nombre;
        $numero_productos = $this->articulos->count();
        $proveedor = Proveedor::find($this->proveedores[0]->id_proveedor);
        $coste = $proveedor -> articulos -> where("pivot.cantidad_por_lote", 20);
        
        return [
            'id_pedido' => $this->id_pedido,
            'proveedor' => $nombre_proveedor,
            'numero_productos' => $numero_productos,
            'fecha_inicio' => $this->fecha_inicial,
            'valor_total' => $this->articulos->get(),
        ];
    }
}
