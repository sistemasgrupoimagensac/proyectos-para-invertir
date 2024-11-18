<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Proyecto Aprobado</title>
    </head>
    <body>
        <p>Estimad@ {{ $inversionista }},</p>

        <p>Nos complace informarle que ha aprobado el proyecto con código "{{ $co_unico_solicitud }}".</p>
        
        <p>Su analista {{ $analista}} copiado en este correo ya está al tanto de esta aprobación y está disponible para apoyarlo en los próximos pasos del proceso operativo.</p>

        <p>Le recomendamos ponerse en contacto con su analista de una forma mas directa para coordinar y avanzar con el flujo operativo correspondiente.</p>
        
        {{-- <br> --}}
        {{-- <a href="https://proyectosparainvertir.ginversiones.pe/detalle/{{ $co_solicitud_prestamo }}" class="btn btn-primary">Ir al proyecto</a> --}}
        <br>
        <br>
        <p>Saludos,</p>
        <p>Equipo de Proyectos</p>
    </body>
</html>