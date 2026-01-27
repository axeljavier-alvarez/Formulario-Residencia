/* prueba para actualizar el estado de las solicitudes */
/* UPDATE solicitudes
SET estado_id = 1; */
/* 
UPDATE solicitudes
SET estado_id = 3
WHERE id = 32; */

/* ver los archivos por no_solicitud de dependientes 
*/

SELECT * FROM estados;
SELECT * FROM dependientes;
SELECT * FROM tramites;
SELECT * FROM solicitudes;

SELECT
    s.no_solicitud,
    dsol.id               AS detalle_id,
    dsol.path             AS archivo,
    dep.id                AS dependiente_id,
    dep.nombres,
    dep.apellidos
FROM solicitudes s
INNER JOIN detalle_solicitud dsol
    ON dsol.solicitud_id = s.id
INNER JOIN dependientes dep
    ON dep.detalle_solicitud_id = dsol.id
WHERE s.no_solicitud = '1-2026';

SELECT * FROM requisitos;
SELECT * FROM solicitudes;
SELECT * FROM detalle_solicitud;
SELECT * FROM dependientes;
DESCRIBE detalle_solicitud;
SELECT 
s.no_solicitud,
-- bitacora

b.id AS bitacora_id,
b.evento,
b.descripcion,
b.created_at AS fecha_evento,


-- usuario de bitacora
ub.id AS usuario_bitacora_id,
ub.name AS usuario_bitacora,

-- detalle solicitud
ds.id AS detalle_id,
ds.path,

-- usuario que subio el el detalle
ud.id AS usuario_detalle_id,
ud.name AS usuario_detalle

FROM solicitudes s

LEFT JOIN bitacoras b
ON b.solicitud_id = s.id

LEFT JOIN users ub
ON ub.id = b.user_id

LEFT JOIN detalle_solicitud ds
ON ds.solicitud_id = s.id

LEFT JOIN users ud
ON ud.id = ds.user_id

WHERE s.no_solicitud IN('20-2025', '21-2025', '22-2025')

ORDER BY s.no_solicitud, b.created_at DESC;



DESCRIBE detalle_solicitud;

SELECT * FROM solicitudes;

SELECT * FROM bitacoras;


SELECT * FROM zonas;

SELECT * FROM estados;


SELECT * FROM tramites;

SELECT * FROM requisitos;

SELECT * FROM requisito_tramite;

SELECT * FROM solicitudes_has_requisitos_tramites;

SELECT * FROM detalle_solicitud;





/* SHOW CREATE TABLE solicitudes;
SHOW CREATE TABLE zonas; */


/* solicitudes con estado pendiente */
SELECT s.*, e.nombre AS estado
FROM solicitudes s
JOIN estados e ON e.id = s.estado_id
WHERE e.nombre = 'Pendiente';

/* requisitos de cada tramite */

/* SELECT r.id, r.nombre
FROM requisitos r
INNER JOIN requisito_tramite rt ON r.id = rt.requisito_id
WHERE rt.tramite_id = 1; */


/* requisitos por nombre */
/* SELECT
t.id AS tramite_id,
t.nombre AS tramite,
r.id AS requisito_id,
r.nombre AS requisito
FROM requisito_tramite rt
INNER JOIN tramites t ON t.id = rt.tramite_id
INNER JOIN requisitos r ON r.id = rt.requisito_id
WHERE lower(t.nombre) = lower('magisterio'); */
/* ver los requisitos de cada tramite */
SELECT
t.id AS tramite_id,
t.nombre AS tramite,
r.id AS requisito_id,
r.nombre AS requisito
FROM requisito_tramite rt
INNER JOIN tramites t ON t.id = rt.tramite_id
INNER JOIN requisitos r ON r.id = rt.requisito_id
/* WHERE rt.tramite_id = 1; */
ORDER BY t.id;


/* 

DELETE FROM solicitudes_has_requisitos_tramites

DELETE FROM solicitudes */
