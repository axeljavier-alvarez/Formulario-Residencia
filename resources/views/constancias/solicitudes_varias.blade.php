<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Constancia</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
    </style>
</head>
<body>

<h2>solicitudes varias</h2>

<p>
    SOLICITUDES VARIAS
</p>

<p>
    Se hace constar que {{ strtoupper($solicitud->nombres . ' ' . $solicitud->apellidos) }}
    con CUI {{ $solicitud->cui }}
</p>

</body>
</html>