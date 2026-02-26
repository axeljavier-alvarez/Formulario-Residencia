<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            size: letter;
            margin: 2.54cm 3cm;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .text-justify { text-align: justify; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-uppercase { text-transform: uppercase; }
        .bold { font-weight: bold; }

        /* Espaciados basados en el documento fuente */
        .correlativo { 
            margin-bottom: 35px; 
        }

        .encabezado { 
            margin-bottom: 25px; 
            line-height: 1.3; 
        }

        .hace-constar { 
            margin: 35px 0; 
            font-size: 13pt; 
        }

        .cuerpo { 
            margin-bottom: 20px; 
        }

        .titulo-profesional {
            margin: 25px 0;
        }

        .firma-container {
            margin-top: 80px;
            line-height: 1.2;
        }

        .linea-firma {
            width: 250px;
            border-top: 1px solid #000;
            margin: 0 auto 10px auto;
        }
    </style>
</head>
<body>

    <div class="text-right bold correlativo">
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
        @if($solicitud->tramite->slug === 'magisterio') 
            -DPI- Código Único de Identificación -CUI- número {{ $solicitud->cui }}
        @else 
            con Código Único de Identificación: {{ $solicitud->cui }}
        @endif 
        extendido por el Registro Nacional de las Personas de la República de Guatemala -RENAP- que identifica a: 
        <span class="bold text-uppercase">{{ $solicitud->nombres }} {{ $solicitud->apellidos }}</span>
        quien solicita su constancia de Residencia, 
        @if($solicitud->tramite->slug === 'magisterio') 
            para realizar trámites ante el Ministerio de Educación.
        @else 
            para {{ $solicitud->razon ?? 'N/A' }}.
        @endif
    </div>

    @if($solicitud->tramite->slug === 'magisterio')
        <div class="text-center bold text-uppercase titulo-profesional">
            TÍTULO: {{ $solicitud->razon }}
        </div>
    @endif

    <div class="text-justify cuerpo">
        @if($solicitud->tramite->slug === 'magisterio') Quien reside @else Reside @endif actualmente en: 
        <span class="bold text-uppercase">{{ $solicitud->domicilio }} {{ $solicitud->zona->nombre ?? '' }}, CIUDAD DE GUATEMALA.</span>
        @php
            $cargas = $solicitud->detalles()->where('tipo', 'like', '%carga%')->get();
        @endphp
        @if($solicitud->tramite->slug === 'magisterio' && $cargas->count() > 0)
            y presenta las siguientes cargas familiares:
            <ul style="margin-top: 10px; margin-bottom: 10px;">
                @foreach($cargas as $carga)
                    <li class="bold">{{ $carga->descripcion }}</li>
                @endforeach
            </ul>
        @endif
        Según Informe Social de visita domiciliar realizada por delegados de Alcaldía Auxiliar correspondiente.
    </div>

    <div class="text-justify cuerpo">
        Para los usos que al (a) interesado (a) corresponda, se extiende la presente <span class="bold">CONSTANCIA DE RESIDENCIA</span>.
        En la ciudad de Guatemala, {{ $dia }} de {{ $mes }} del {{ $anio }}.
    </div>
    <div class="firma-container text-center">
        <span class="bold">Licda. María José Samayoa Aldana</span><br>
        Directora de Desarrollo Social<br>
        Municipalidad de Guatemala
    </div>

</body>
</html>