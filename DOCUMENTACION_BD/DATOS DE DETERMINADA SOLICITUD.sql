SELECT * FROM solicitudes;
SELECT * FROM requisitos;
SELECT * FROM solicitudes_has_requisitos_tramites;

/* ver el no_solicitud con su requisito_nombre y tramite_nombre */
SELECT 
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
    s.no_solicitud = '2-2025';



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
    s.no_solicitud = '2-2025'
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

WHERE s.no_solicitud = '2-2025'

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
    t.id, t.nombre;


