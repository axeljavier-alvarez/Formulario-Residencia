<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica'; font-size: 12pt; line-height: 1.5; }
        .header { text-align: right; font-weight: bold; margin-bottom: 30px; }
        .title { text-align: center; font-weight: bold; margin: 20px 0; }
        .content { text-align: justify; }
        .bold { font-weight: bold; }
        .footer { margin-top: 50px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        No. {{ $solicitud->no_solicitud }}
    </div>

    <p class="bold">DIRECCIÓN DE DESARROLLO SOCIAL, LA INFRASCRITA DIRECTORA...</p>

    <div class="title">HACE CONSTAR:</div>

    <div class="content">
        Que tuvo a la vista fotocopia simple... CUI: <span class="bold">{{ $solicitud->cui }}</span> 
        que identifica a <span class="bold">{{ $solicitud->nombres }} {{ $solicitud->apellidos }}</span>...
        para <span class="bold">{{ $solicitud->razon }}</span>.
    </div>
    
    </body>
</html><!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica'; font-size: 12pt; line-height: 1.5; }
        .header { text-align: right; font-weight: bold; margin-bottom: 30px; }
        .title { text-align: center; font-weight: bold; margin: 20px 0; }
        .content { text-align: justify; }
        .bold { font-weight: bold; }
        .footer { margin-top: 50px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        No. {{ $solicitud->no_solicitud }}
    </div>

    <p class="bold">DIRECCIÓN DE DESARROLLO SOCIAL, LA INFRASCRITA DIRECTORA...</p>

    <div class="title">HACE CONSTAR:</div>

    <div class="content">
        Que tuvo a la vista fotocopia simple... CUI: <span class="bold">{{ $solicitud->cui }}</span> 
        que identifica a <span class="bold">{{ $solicitud->nombres }} {{ $solicitud->apellidos }}</span>...
        para <span class="bold">{{ $solicitud->razon }}</span>.
    </div>
    
    </body>
</html>