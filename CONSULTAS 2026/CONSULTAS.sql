/* SELECT * FROM dependientes;

SELECT * FROM estados;

SELECT * FROM solicitudes;

SELECT * FROM users;

SELECT * FROM bitacoras;

*/

SELECT * FROM solicitudes;

SHOW TRIGGERS;


SELECT * FROM bitacoras;


SELECT * FROM estados;

/* 
DELIMITER //

CREATE TRIGGER tr_solicitud_creada_bitacora
AFTER INSERT ON solicitudes
FOR EACH ROW
BEGIN
   INSERT INTO bitacoras (solicitud_id, user_id, evento, descripcion, created_at, updated_at)
   VALUES(NEW.id, NULL, 'CREACION', 'Registro inicial de la solicitud desde el formulario', NOW(), NOW());
END; //


DELIMITER ;  */
/* 

DELIMITER //

CREATE TRIGGER tr_bitacora_cambio_estado
AFTER UPDATE ON solicitudes
FOR EACH ROW
BEGIN
    -- Verificar que el estado cambio
    IF OLD.estado_id <> NEW.estado_id THEN
        
        -- Variables de descripcion
        DECLARE nombre_estado VARCHAR(100);
        DECLARE descripcion_texto TEXT;

        -- Buscar nombre del estado
        SELECT nombre INTO nombre_estado 
        FROM estados 
        WHERE id = NEW.estado_id;

        -- descripcion
        IF nombre_estado = 'Cancelado' THEN
            SET descripcion_texto = 'La solicitud ha sido rechazada por el analista';
        ELSEIF nombre_estado = 'En proceso' THEN
            SET descripcion_texto = 'La solicitud ha sido aprobada para análisis';
        ELSE
            SET descripcion_texto = CONCAT('El estado de la solicitud cambió a: ', nombre_estado);
        END IF;

        -- Insercion en tabla bitacoras
        INSERT INTO bitacoras (
            solicitud_id, 
            user_id, 
            evento, 
            descripcion, 
            created_at, 
            updated_at
        )
        VALUES (
            NEW.id, 
            NULL, 
            CONCAT('CAMBIO DE ESTADO: ', nombre_estado), 
            descripcion_texto, 
            NOW(), 
            NOW()
        );
        
    END IF;
END //

DELIMITER ; */


/* 
drop trigger if exists tr_solicitud_creada_bitacora */

/* MOSTRAR TODOS LOS DATOS DE LA SOLICITUD */

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

    /* ================= REQUISITOS ================= */
    GROUP_CONCAT(
        DISTINCT r.nombre 
        ORDER BY r.nombre 
        SEPARATOR ', '
    ) AS requisitos,

    GROUP_CONCAT(
        DISTINCT t.nombre 
        ORDER BY t.nombre 
        SEPARATOR ', '
    ) AS tramites,

    GROUP_CONCAT(
        DISTINCT CONCAT(r.nombre, ': ', ds.path)
        ORDER BY r.nombre
        SEPARATOR ' || '
    ) AS archivos,

    MAX(
        CASE 
            WHEN r.nombre = 'Cargas familiares' THEN 1
            ELSE 0
        END
    ) AS tiene_cargas_familiares,

    GROUP_CONCAT(
        DISTINCT
        CASE 
            WHEN r.nombre = 'Cargas familiares' 
            THEN CONCAT(d.nombres, ' ', d.apellidos)
            ELSE NULL
        END
        ORDER BY d.nombres
        SEPARATOR ' | '
    ) AS dependientes,

    /* ================= BITÁCORA ================= */
    bita.bitacora

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

/* ================= SUBQUERY BITÁCORA ================= */
LEFT JOIN (
    SELECT 
        b.solicitud_id,
        GROUP_CONCAT(
            CONCAT(
                DATE_FORMAT(b.created_at, '%d/%m/%Y %H:%i'),
                ' - ',
                COALESCE(u.name, 'Sistema'),
                ' (',
                b.evento,
                '): ',
                b.descripcion
            )
            ORDER BY b.created_at
            SEPARATOR ' || '
        ) AS bitacora
    FROM bitacoras b
    LEFT JOIN users u ON u.id = b.user_id
    GROUP BY b.solicitud_id
) bita ON bita.solicitud_id = s.id

/* WHERE s.no_solicitud = '2-2025' */

GROUP BY 
    s.id, s.no_solicitud, s.nombres, s.apellidos,
    s.email, s.telefono, s.cui, s.domicilio,
    s.observaciones, z.nombre, e.nombre, bita.bitacora;



    



/* SELECT DE TODAS LAS TABLAS PARA MOSTRAR LOS DATOS POR SOLICITUD Y EN GENERAL*/


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
    
    
    


