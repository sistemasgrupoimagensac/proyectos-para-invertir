<?php

namespace App\Services\Persona;

use App\HPersonaNatural;
use App\HPersonaRepresentante;
use App\PPersona;
use Illuminate\Support\Facades\Auth;

class GuardarPersonaNatural
{
    protected $data;

    public function __construct(?array $data = null)
    {
        $this->data = $data;
    }

    public function persona($codigo, $data)
    {
        if ($codigo !== null) {
            $persona = PPersona::where('co_persona', $codigo)->where('in_estado', 1)->first();

            if ($persona) {
                $persona->in_tipo_persona               = 1;
                $persona->co_tipo_documento_identidad   = $data['tipo_documento'];
                $persona->nu_documento_identidad        = $data['numero_documento'];
                $persona->no_completo_persona           = mb_strtoupper($data['apellido_paterno'] . ' ' . $data['apellido_materno'] . ' ' . $data['nombres']);
                $persona->no_direccion_fiscal           = $data['direccion'] ?? null;
                $persona->co_distrito_fiscal            = $data['distrito'] ?? null;
                $persona->nu_celular                    = $data['celular'] ?? null;
                $persona->nu_telefono_contacto          = $data['celular_2'] ?? null;
                $persona->no_correo_electronico         = $data['correo'] ?? null;
                $persona->co_usuario_modifica           = Auth::user()->co_usuario;
                $persona->fe_usuario_modifica           = now();
                $persona->save();
            }

        }else {
            $persona = PPersona::create([
                'in_tipo_persona'               => 1,
                'co_tipo_documento_identidad'   => $data['tipo_documento'],
                'nu_documento_identidad'        => $data['numero_documento'],
                'no_completo_persona'           => mb_strtoupper($data['apellido_paterno'] . ' ' . $data['apellido_materno'] . ' ' . $data['nombres']),
                'fe_inicio_actividades'         => now(),
                'no_direccion_fiscal'           => $data['direccion'] ?? null,
                'co_distrito_fiscal'            => $data['distrito'] ?? null,
                'nu_celular'                    => $data['celular'] ?? null,
                'nu_telefono_contacto'          => $data['celular_2'] ?? null,
                'no_correo_electronico'         => $data['correo'] ?? null,
                'co_usuario_modifica'           => Auth::user()->co_usuario,
                'fe_usuario_modifica'           => now(),
                'in_estado'                     => 1,
            ]);
        }

        if ($persona) {
            $this->detallePersona($persona->co_persona, $data);
        }

        return $persona;
    }

    public function detallePersona($codigo, $data)
    {
        if ($codigo !== null) {
            $persona_natural = HPersonaNatural::where('co_persona', $codigo)->first();

            if ($persona_natural) {
                HPersonaNatural::where('co_persona', $codigo)->update([
                    'co_estado_civil'           => $data['estado_civil'],
                    'no_apellido_paterno'       => mb_strtoupper($data['apellido_paterno']),
                    'no_apellido_materno'       => mb_strtoupper($data['apellido_materno']),
                    'no_nombres'                => mb_strtoupper($data['nombres']),
                    'ocupacion'                 => $data['ocupacion'],
                ]);
            }else {
                HPersonaNatural::create([
                    'co_persona'                => $codigo,
                    'co_estado_civil'           => $data['estado_civil'],
                    'no_apellido_paterno'       => mb_strtoupper($data['apellido_paterno']),
                    'no_apellido_materno'       => mb_strtoupper($data['apellido_materno']),
                    'no_nombres'                => mb_strtoupper($data['nombres']),
                    'ocupacion'                 => $data['ocupacion'],
                ]);
            }
        }
    }

    public function representante(PPersona $persona, PPersona $representante, int $tipo_relacion, $is_apoderado_conyuge, array $data_representante)
    {
        $persona->representantes()->detach($representante->co_persona);
        $persona->representantes()->attach($representante->co_persona, [
            'co_tipo_relacion'  => $tipo_relacion,
            'nu_partida'        => $data_representante['partida'] ?? null,
            'parentesco'        => $data_representante['parentesco'] ?? null,
            'is_apoderado'      => $is_apoderado_conyuge ?? 0,
            'firmara'           => $data_representante['firmara'] ?? null,
            'co_usuario_modifica'   => Auth::user()->co_usuario,
            'fe_usuario_modifica'   => now(),
            'in_estado'         => 1,
        ]);
    }

    public function save()
    {
        if ($this->data === null) {
            return null;
        }

        $persona = $this->persona($this->data['codigo'], $this->data);

        if ($persona) {

            $apoderado = $conyuge = $testigo = null;

            if ($this->data['estado_civil'] === 2 && sizeof($this->data['conyuge']) > 0) {
    
                $conyuge = $this->persona($this->data['conyuge']['codigo'], $this->data['conyuge']);
                if ($conyuge) {
                    $this->representante($persona, $conyuge, 1, $this->data['is_apoderado_conyuge'], $this->data['conyuge']);

                    $apoderado_conyuge = null;

                    if ($this->data['conyuge']['can_apoderado'] === true && sizeof($this->data['conyuge']['apoderado']) > 0) {
                
                        $apoderado_conyuge = $this->persona($this->data['conyuge']['apoderado']['codigo'], $this->data['conyuge']['apoderado']);
                        if ($apoderado_conyuge) {
                            $this->representante($conyuge, $apoderado_conyuge, 4, null, $this->data['conyuge']['apoderado']);
                        }
            
                    }else if ($this->data['conyuge']['can_apoderado'] === true && $this->data['conyuge']['apoderado_is_mutuatario'] === true) {
                        $apoderado_conyuge = $persona;
                        $this->representante($conyuge, $persona, 4, 1, []);
                    }

                    $this->deletePersonas($conyuge->co_persona, 4, optional($apoderado_conyuge)->co_persona, $persona->co_persona);
                }
            }
            
            if ($this->data['can_apoderado'] === true && sizeof($this->data['apoderado']) > 0) {
                
                $apoderado = $this->persona($this->data['apoderado']['codigo'], $this->data['apoderado']);
                if ($apoderado) {
                    $this->representante($persona, $apoderado, 4, null, $this->data['apoderado']);
                }
            }
    
            if ($this->data['can_testigo'] === true && sizeof($this->data['testigo']) > 0) {
    
                $testigo = $this->persona($this->data['testigo']['codigo'], $this->data['testigo']);
                if ($testigo) {
                    $this->representante($persona, $testigo, 6, null, $this->data['testigo']);
                }    
            }
            // conyuge
            $this->deletePersonas($persona->co_persona, 1, optional($conyuge)->co_persona, $persona->co_persona);
            //apoderado
            $this->deletePersonas($persona->co_persona, 4, optional($apoderado)->co_persona, $persona->co_persona);
            // testigo
            $this->deletePersonas($persona->co_persona, 6, optional($testigo)->co_persona, $persona->co_persona);
            // aqui me quede XD            
        }

        return optional($persona)->co_persona;
    }

    public function deletePersonas(int $persona, $tipo_relacion, ?int $representante, $super_persona)
    {
        $delete_personas = HPersonaRepresentante::where('co_persona', $persona)
                                            ->where('co_tipo_relacion', $tipo_relacion)
                                            ->where(function($q) use($representante){
                                                if ($representante) {
                                                    $q->where('co_persona_representante', '<>', $representante);
                                                }
                                            })
                                            ->select('in_estado', 'co_persona_representante', 'co_persona', 'co_tipo_relacion')
                                            ->get();

        foreach ($delete_personas as $delete_con) {

            HPersonaRepresentante::where('co_persona', $persona)
                                ->where('co_tipo_relacion', $tipo_relacion)
                                ->where('co_persona_representante', $delete_con->co_persona_representante)
                                ->update(['in_estado' => 0]);

            if ($delete_con->co_persona_representante !== $super_persona) {
                PPersona::where('co_persona', $delete_con->co_persona_representante)->update(['in_estado' => 0]);
            }

            if ($tipo_relacion === 1) {
                $this->deletePersonas($delete_con->co_persona_representante, 4, null, $super_persona);
            }
        }
    }
}