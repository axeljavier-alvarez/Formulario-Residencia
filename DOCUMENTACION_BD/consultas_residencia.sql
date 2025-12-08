SELECT * FROM solicitudes;

SELECT * FROM zonas;

SELECT * FROM estados;

/* SHOW CREATE TABLE solicitudes;
SHOW CREATE TABLE zonas; */


/* solicitudes con estado pendiente */
SELECT s.*
FROM solicitudes s
JOIN estados e ON e.id = s.estado_id
WHERE e.nombre = 'Pendiente';


SELECT 

