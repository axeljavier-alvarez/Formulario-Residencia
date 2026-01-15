/* SELECT * FROM solicitudes;
SELECT * FROM estados;
SELECT * FROM tramites;
SELECT * FROM requisitos;
SELECT * FROM dependientes;
SELECT * FROM requisito_tramite;
SELECT * FROM solicitudes_has_requisitos_tramites;
SELECT * FROM detalle_solicitud; */


/* consulta de dependientes, solicitud y detalle */

SELECT ds.*, u.name
FROM detalle_solicitud ds
LEFT JOIN users u ON u.id = ds.user_id;

SELECT * FROM detalle_solicitud;
SELECT * FROM bitacoras;
SELECT * FROM solicitudes;
SELECT * FROM estados;

SELECT * FROM requisitos;
SELECT 
    s.id AS solicitud_id,
    s.no_solicitud,
    s.nombres,
    s.apellidos,
    s.email,
    s.telefono,
    s.cui,
    s.domicilio,
    s.observaciones,
    z.nombre AS zona,
    e.nombre AS estado,

    r.nombre AS requisito,
    t.nombre AS tramite,

    -- Archivos SOLO de cargas familiares
    CASE 
        WHEN r.nombre = 'Cargas familiares' 
        THEN ds.path
        ELSE NULL
    END AS archivo_path,

    -- Flag
    CASE 
        WHEN r.nombre = 'Cargas familiares' 
        THEN 'SI'
        ELSE 'NO'
    END AS es_carga_familiar,

    -- Dependientes SOLO si es cargas familiares
    CASE
        WHEN r.nombre = 'Cargas familiares' THEN
            GROUP_CONCAT(
                DISTINCT CONCAT(d.nombres, ' ', d.apellidos)
                SEPARATOR ' | '
            )
        ELSE NULL
    END AS dependientes

FROM solicitudes s
JOIN zonas z ON z.id = s.zona_id
JOIN estados e ON e.id = s.estado_id

JOIN detalle_solicitud ds 
    ON ds.solicitud_id = s.id

JOIN requisito_tramite rt 
    ON rt.id = ds.requisito_tramite_id

JOIN requisitos r 
    ON r.id = rt.requisito_id

JOIN tramites t 
    ON t.id = rt.tramite_id

LEFT JOIN dependientes d 
    ON d.solicitud_id = s.id

WHERE s.no_solicitud = '33-2026'
  AND r.nombre = 'Cargas familiares'

GROUP BY
    s.id,
    s.no_solicitud,
    s.nombres,
    s.apellidos,
    s.email,
    s.telefono,
    s.cui,
    s.domicilio,
    s.observaciones,
    z.nombre,
    e.nombre,
    r.nombre,
    t.nombre,
    ds.path;

   /*axel */
/* VER TODOS LOS DATOS POR SOLICITUD */
SELECT 
    s.id AS solicitud_id,
    s.no_solicitud,
    s.nombres,
    s.apellidos,
    s.email,
    s.telefono,
    s.cui,
    s.domicilio,
    s.observaciones,
    z.nombre AS zona,
    e.nombre AS estado,

    rt.id AS requisito_tramite_id,
    r.nombre AS requisito,
    t.nombre AS tramite,
    ds.path AS archivo_path,

    CASE 
        WHEN r.nombre = 'Cargas familiares' 
        THEN 'SI'
        ELSE 'NO'
    END AS es_carga_familiar,

    CASE
        WHEN r.nombre = 'Cargas familiares' THEN
            GROUP_CONCAT(
                DISTINCT CONCAT(d.nombres, ' ', d.apellidos)
                SEPARATOR ' | '
            )
        ELSE NULL
    END AS dependientes

FROM solicitudes s
JOIN zonas z ON z.id = s.zona_id
JOIN estados e ON e.id = s.estado_id

LEFT JOIN detalle_solicitud ds 
       ON ds.solicitud_id = s.id

LEFT JOIN requisito_tramite rt 
       ON rt.id = ds.requisito_tramite_id

LEFT JOIN requisitos r 
       ON r.id = rt.requisito_id

LEFT JOIN tramites t 
       ON t.id = rt.tramite_id

LEFT JOIN dependientes d 
       ON d.solicitud_id = s.id

/* WHERE s.no_solicitud = '2-2025' */

/* 
WHERE s.no_solicitud IN ('1-2025','2-2025','3-2025') */


GROUP BY 
    s.id, s.no_solicitud, s.nombres, s.apellidos, s.email,
    s.telefono, s.cui, s.domicilio, s.observaciones,
    z.nombre, e.nombre,
    rt.id, r.nombre, t.nombre, ds.path;




/* VER EN UNA SOLA LINEA */
SELECT 
    s.id AS solicitud_id,
    s.no_solicitud,
    s.nombres,
    s.apellidos,
    s.email,
    s.telefono,
    s.cui,
    s.domicilio,
    s.observaciones,
    z.nombre AS zona,
    e.nombre AS estado,

    -- Requisitos (ordenados)
    GROUP_CONCAT(
        DISTINCT r.nombre 
        ORDER BY r.nombre 
        SEPARATOR ', '
    ) AS requisitos,

    -- Trámites
    GROUP_CONCAT(
        DISTINCT t.nombre 
        ORDER BY t.nombre 
        SEPARATOR ', '
    ) AS tramites,

    -- Archivos por requisito
    GROUP_CONCAT(
        DISTINCT CONCAT(r.nombre, ': ', ds.path)
        ORDER BY r.nombre
        SEPARATOR ' || '
    ) AS archivos,

    -- ¿Tiene cargas familiares?
    MAX(
        CASE 
            WHEN r.nombre = 'Cargas familiares' THEN 1
            ELSE 0
        END
    ) AS tiene_cargas_familiares,

    -- Dependientes (solo si hay cargas)
    GROUP_CONCAT(
        DISTINCT
        CASE 
            WHEN r.nombre = 'Cargas familiares' 
            THEN CONCAT(d.nombres, ' ', d.apellidos)
            ELSE NULL
        END
        ORDER BY d.nombres
        SEPARATOR ' | '
    ) AS dependientes

FROM solicitudes s
JOIN zonas z ON z.id = s.zona_id
JOIN estados e ON e.id = s.estado_id

LEFT JOIN detalle_solicitud ds 
       ON ds.solicitud_id = s.id

LEFT JOIN requisito_tramite rt 
       ON rt.id = ds.requisito_tramite_id

LEFT JOIN requisitos r 
       ON r.id = rt.requisito_id

LEFT JOIN tramites t 
       ON t.id = rt.tramite_id

LEFT JOIN dependientes d 
       ON d.solicitud_id = s.id

/* 
WHERE s.no_solicitud = '2-2025' */

GROUP BY 
    s.id, s.no_solicitud, s.nombres, s.apellidos,
    s.email, s.telefono, s.cui, s.domicilio,
    s.observaciones, z.nombre, e.nombre;

/* SOLICITUDES CON DEPENDIENTES Y REQUISITO_TRAMITE */

SELECT 
    s.id AS solicitud_id,
    s.no_solicitud,

    'SI' AS es_carga_familiar,

    -- Archivo asociado al requisito "Cargas familiares"
    ds.path AS archivo_path,

    -- Dependientes de la solicitud
    GROUP_CONCAT(
        DISTINCT CONCAT(d.nombres, ' ', d.apellidos)
        SEPARATOR ' | '
    ) AS dependientes

FROM solicitudes s

JOIN detalle_solicitud ds 
       ON ds.solicitud_id = s.id

JOIN requisito_tramite rt 
       ON rt.id = ds.requisito_tramite_id

JOIN requisitos r 
       ON r.id = rt.requisito_id

LEFT JOIN dependientes d 
       ON d.solicitud_id = s.id


WHERE s.no_solicitud = '2-2025'
  AND r.nombre = 'Cargas familiares'

GROUP BY 
    s.id, s.no_solicitud, ds.path;
    
    
    
/* ver el no_solicitud con su requisito_nombre y tramite_nombre */
/* SELECT 
    s.no_solicitud,
    r.nombre AS requisito_nombre,
    t.nombre AS tramite_nombre
FROM 
    solicitudes s
JOIN 
    solicitudes_has_requisitos_tramites sr ON sr.solicitud_id = s.id
JOIN 
    requisito_tramite rt ON rt.id = sr.requisito_tramite_id
JOIN 
    requisitos r ON r.id = rt.requisito_id
JOIN 
    tramites t ON t.id = rt.tramite_id
WHERE 
    s.no_solicitud = '6-2025'; */


/* 
SELECT 
    s.no_solicitud,
    s.nombres,
    s.apellidos,
    s.email,
    s.telefono,
    s.cui,
    s.domicilio,
    z.nombre AS zona,
    e.nombre AS estado,
    r.nombre AS requisito,
    t.nombre AS tramite
FROM 
    solicitudes s
JOIN zonas z ON z.id = s.zona_id
JOIN estados e ON e.id = s.estado_id
LEFT JOIN solicitudes_has_requisitos_tramites sr 
    ON sr.solicitud_id = s.id
LEFT JOIN requisito_tramite rt 
    ON rt.id = sr.requisito_tramite_id
LEFT JOIN requisitos r 
    ON r.id = rt.requisito_id
LEFT JOIN tramites t 
    ON t.id = rt.tramite_id
WHERE 
    s.no_solicitud = '6-2025'
ORDER BY 
    tramite, requisito;



SELECT 
    s.no_solicitud,
    s.nombres,
    s.apellidos,
    s.email,
    s.telefono,
    s.cui,
    s.domicilio,
    s.observaciones,
    z.nombre AS zona,
    e.nombre AS estado,

    GROUP_CONCAT(DISTINCT r.nombre SEPARATOR ', ') AS requisitos,
    GROUP_CONCAT(DISTINCT t.nombre SEPARATOR ', ') AS tramites

FROM solicitudes s
JOIN zonas z ON z.id = s.zona_id
JOIN estados e ON e.id = s.estado_id
LEFT JOIN solicitudes_has_requisitos_tramites sr ON sr.solicitud_id = s.id
LEFT JOIN requisito_tramite rt ON rt.id = sr.requisito_tramite_id
LEFT JOIN requisitos r ON r.id = rt.requisito_id
LEFT JOIN tramites t ON t.id = rt.tramite_id

WHERE s.no_solicitud = '6-2025'

GROUP BY 
    s.id, s.no_solicitud, s.nombres, s.apellidos, s.email, 
    s.telefono, s.cui, s.domicilio, z.nombre, e.nombre;



SELECT 
    t.nombre AS tramite,
    GROUP_CONCAT(DISTINCT r.nombre ORDER BY r.nombre SEPARATOR ', ') AS requisitos
FROM 
    tramites t
JOIN 
    requisito_tramite rt ON rt.tramite_id = t.id
JOIN 
    requisitos r ON r.id = rt.requisito_id
GROUP BY 
    t.id, t.nombre; */
    
    
 




