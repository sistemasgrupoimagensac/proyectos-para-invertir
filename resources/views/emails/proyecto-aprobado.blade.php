<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Proyecto aprobado: Código {{ $co_unico_solicitud }}</title>
    </head>
    <body>
        <p>Estimad@ {{ $inversionista }},</p>

        <p>Nos complace informarle que el proyecto con el código <strong>{{ $co_unico_solicitud }}</strong> ha sido aprobado.</p>

        <p>Su analista asignado, <strong>{{ $analista }}</strong>, quien está copiado en este correo, ya está al tanto de la aprobación y está disponible para apoyarlo en los próximos pasos del proceso operativo.</p>

        <p>Le recomendamos comunicarse directamente con su analista para coordinar y avanzar en el flujo operativo correspondiente.</p>
        
        {{-- <br> --}}
        {{-- <a href="https://proyectosparainvertir.ginversiones.pe/detalle/{{ $co_solicitud_prestamo }}" class="btn btn-primary">Ir al proyecto</a> --}}
        <br>
        <br>
        <p>Saludos,</p>
        <p>Equipo de Proyectos</p>
    </body>
</html>