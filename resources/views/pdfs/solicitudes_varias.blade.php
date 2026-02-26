<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        /* Configuración de página tipo carta con márgenes de Word */
        @page {
            size: letter;
            margin: 2.54cm 3cm;
        }

        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* Alineaciones y estilos de texto */
        .text-justify { text-align: justify; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-uppercase { text-transform: uppercase; }
        .bold { font-weight: bold; }

        /* Estructura de secciones basada en el original */
        .correlativo { margin-bottom: 40px; }
        .encabezado { margin-bottom: 30px; line-height: 1.2; }
        .titulo-constar { margin: 40px 0; font-size: 13pt; }
        .parrafo { margin-bottom: 25px; }
        
        /* Área de firma al final */
        .seccion-firma { margin-top: 80px; line-height: 1.2; }
        .linea-firma { width: 250px; border-top: 1px solid black; margin: 0 auto 10px auto; }
    </style>
</head>
<body>

    <div class="text-right bold correlativo">
        No. {{ $solicitud->no_solicitud }}
    </div>

    <div class="text-justify bold encabezado text-uppercase">
        DIRECCION DE DESARROLLO SOCIAL, LA INFRASCRITA DIRECTORA DE DESARROLLO SOCIAL DE LA MUNICIPALIDAD DE GUATEMALA.
    </div>

    <div class="text-center bold titulo-constar">
        HACE CONSTAR:
    </div>

    <div class="text-justify parrafo">
        Que tuvo a la vista fotocopia simple del Documento Personal de Identificación con Código Único de Identificación: 
        <span class="bold">{{ $solicitud->cui }}</span> extendido por el Registro Nacional de las Personas de la República de Guatemala -RENAP- que identifica a 
        <span class="bold text-uppercase">{{ $solicitud->nombres }} {{ $solicitud->apellidos }}</span> 
        quien solicita constancia de residencia para <span class="bold">{{ $solicitud->razon ?? 'N/A' }}</span>.
    </div>

    <div class="text-justify parrafo">
        Reside actualmente en: <span class="bold text-uppercase">{{ $solicitud->domicilio }} {{ $solicitud->zona->nombre ?? '' }} CIUDAD DE GUATEMALA</span>. 
        Según Informe Social de visita domiciliar realizada por delegados de Alcaldía Auxiliar correspondiente.
    </div>

    <div class="text-justify parrafo">
        Para los usos que al (a) interesado (a) corresponda, se extiende la presente <span class="bold">CONSTANCIA DE RESIDENCIA</span>. 
        En la ciudad de Guatemala, {{ $dia }} de {{ $mes }} de {{ $anio }}.
    </div>

    <div class="seccion-firma text-center">
        <span class="bold">Licda. María José Samayoa Aldana</span><br>
        Directora de Desarrollo Social<br>
        Municipalidad de Guatemala
    </div>

</body>
</html>