<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Seguimiento del proyecto aprobado</title>
    </head>
    <body>
        <p>Estimad@ {{ $inversionista }},</p>

        <p>Nos complace informarle que ha aprobado el proyecto con código "{{ $co_unico_solicitud }}".</p>

        <p>Asimismo, le informamos que su proyecto ha ingresado a la cola con el número <strong>{{ $prioridad }}</strong> para continuar con los siguientes pasos. Le sugerimos consultar con su analista asignado, <strong>{{ $analista }}</strong>, quien está copiado en este correo y disponible para apoyarlo en cada etapa del proceso operativo.</p>

        <p>Recomendamos que se comunique directamente con su analista para coordinar y avanzar con el flujo operativo correspondiente.</p>
        {{-- <br> --}}
        {{-- <a href="https://proyectosparainvertir.ginversiones.pe/detalle/{{ $co_solicitud_prestamo }}" class="btn btn-primary">Ir al proyecto</a> --}}
        <br> <br>
        <p>Saludos cordiales,</p>
        <p>Equipo de Proyectos</p>
    </body>
</html>