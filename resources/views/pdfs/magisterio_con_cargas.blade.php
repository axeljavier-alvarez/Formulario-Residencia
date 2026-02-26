<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        /* Configuración de la página similar a Word (Tamaño Carta) */
        @page {
            size: letter;
            /* Margen estándar de Word: 2.54cm arriba/abajo y 3cm lados para dar aire */
            margin: 2.54cm 3cm;
        }

        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 11pt; /* Tamaño estándar de Word */
            line-height: 1.6; /* Espaciado entre líneas para legibilidad */
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* Alineaciones */
        .text-justify { text-align: justify; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-uppercase { text-transform: uppercase; }
        .bold { font-weight: bold; }

        /* Espaciado de secciones */
        .correlativo { margin-bottom: 40px; font-weight: bold; }
        .encabezado { margin-bottom: 30px; line-height: 1.2; }
        .hace-constar { margin: 40px 0; font-size: 14pt; }
        .cuerpo { margin-bottom: 20px; text-indent: 0; }
        .firma { margin-top: 80px; line-height: 1.2; }

        /* Estilo para listas de cargas familiares */
        .cargas { margin-left: 40px; margin-top: 10px; margin-bottom: 10px; }
    </style>
</head>
<body>

    <div class="text-right correlativo">
        No. {{ $solicitud->no_solicitud }}
    </div>

    <div class="text-justify bold encabezado text-uppercase">
        DIRECCION DE DESARROLLO SOCIAL, LA INFRASCRITA DIRECTORA DE DESARROLLO SOCIAL DE LA MUNICIPALIDAD DE GUATEMALA.
    </div>

    <div class="text-center bold hace-constar">
        HACE CONSTAR:
    </div>

    <div class="text-justify cuerpo">
        Que tuvo a la vista fotocopia simple del Documento Personal de Identificación 
        @if($solicitud->tramite->slug === 'magisterio') -DPI- Código Único de Identificación -CUI- número @else con Código Único de Identificación: @endif 
        <span class="bold">{{ $solicitud->cui }}</span> extendido por el Registro Nacional de las Personas de la República de Guatemala -RENAP- que identifica a: 
        <span class="bold text-uppercase">{{ $solicitud->nombres }} {{ $solicitud->apellidos }}</span> 
        quien solicita su constancia de Residencia, 
        @if($solicitud->tramite->slug === 'magisterio') para realizar trámites ante el Ministerio de Educación. @else para {{ $solicitud->razon ?? 'N/A' }}. @endif
    </div>

    @if($solicitud->tramite->slug === 'magisterio')
        <div class="text-center bold text-uppercase" style="margin: 20px 0;">
            TÍTULO: {{ $solicitud->razon }}
        </div>
    @endif

    <div class="text-justify cuerpo">
        @if($solicitud->tramite->slug === 'magisterio') Quien reside @else Reside @endif actualmente en: 
        <span class="bold text-uppercase">{{ $solicitud->domicilio }} {{ $solicitud->zona->nombre ?? '' }}, CIUDAD DE GUATEMALA</span>
        @if($solicitud->tramite->slug === 'magisterio' && $solicitud->detalles()->where('tipo', 'like', '%carga%')->exists())
            y presenta las siguientes cargas familiares:
            <ul class="cargas">
                @foreach($solicitud->detalles()->where('tipo', 'like', '%carga%')->get() as $detalle)
                    <li>{{ $detalle->descripcion ?? 'Carga Familiar' }}</li>
                @endforeach
            </ul>
        @else. @endif
        Según Informe Social de visita domiciliar realizada por delegados de Alcaldía Auxiliar correspondiente.
    </div>

    <div class="text-justify cuerpo">
        Para los usos que al (a) interesado (a) corresponda, se extiende la presente <span class="bold">CONSTANCIA DE RESIDENCIA</span>. 
        En la ciudad de Guatemala, {{ $dia }} de {{ $mes }} del {{ $anio }}.
    </div>

    <div class="firma text-center">
        <span class="bold">Licda. María José Samayoa Aldana</span><br>
        Directora de Desarrollo Social<br>
        Municipalidad de Guatemala
    </div>

</body>
</html>