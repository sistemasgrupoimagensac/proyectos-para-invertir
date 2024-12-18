<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                    => $this->co_solicitud_prestamo,
            'imagen_principal'      => $this->when(optional($this)->imagen_principal, optional($this)->imagen_principal),
            'imagenes'              => $this->when(optional($this)->imagenes, optional($this)->imagenes),
            'motivo_prestamo'       => $this->when(optional($this)->motivo_prestamo, optional($this)->motivo_prestamo),
            'provincia'             => $this->no_provincia,
            'distrito'              => $this->no_distrito,
            'codigo_proyecto'       => $this->co_unico_solicitud,
            'tipo_garantia'         => $this->no_tipo_garantia,
            'tasa'                  => (float) $this->nu_tasa_interes_mensual . ' %',
            'plazo_financiamiento'  => $this->no_tiempo_pago,
            'tipo_financiamiento'   => $this->no_forma_pago,
            'valor_comercial'       => ($this->tipo_moneda_dato_prestamo ?? 'S/ ') . number_format($this->valor_comercial_inmueble ?? 0, 2),
            'monto'                 => ($this->nc_tipo_moneda ?? 'S/ ') . number_format($this->nu_total_solicitado ?? 0, 2),
            'likes'                 => $this->likes,
            'aprobados'             => $this->total_aprobados_proyecto,
            'latitud'               => $this->when(optional($this)->latitud, (double) optional($this)->latitud),
            'longitud'              => $this->when(optional($this)->longitud, (double) optional($this)->longitud),
        ];
    }
}
