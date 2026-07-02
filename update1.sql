-- Script para agregar la columna 'monto_abonado' a la tabla 'pedidos'
-- Ejecutar este script en phpMyAdmin o desde la línea de comandos de MySQL

ALTER TABLE pedidos ADD COLUMN monto_abonado DECIMAL(10,2) DEFAULT 0;

-- Verificar que la columna se haya agregado correctamente
DESCRIBE pedidos;