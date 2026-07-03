-- Script para agregar la columna 'combinacion_modelos' a la tabla 'detalles_pedido'
-- Ejecutar este script en phpMyAdmin o desde la línea de comandos de MySQL

ALTER TABLE detalles_pedido ADD COLUMN combinacion_modelos VARCHAR(255) DEFAULT NULL;

-- Verificar que la columna se haya agregado correctamente
DESCRIBE detalles_pedido;