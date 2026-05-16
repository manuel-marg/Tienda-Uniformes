-- Script SQL Actualizado - Versión 2
CREATE DATABASE IF NOT EXISTS tienda_uniformes CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tienda_uniformes;

-- 1. Tabla de productos (Catálogo Dinámico)
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    precio_base DECIMAL(10, 2) NOT NULL,
    activo TINYINT(1) DEFAULT 1,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Tabla de pedidos (Cabecera)
CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    cliente_nombre VARCHAR(150) NOT NULL,
    cliente_telefono VARCHAR(20) NOT NULL,
    metodo_entrega ENUM('Envío', 'Retiro en Tienda') NOT NULL,
    estado_pago ENUM('Pendiente', 'Pagado') DEFAULT 'Pendiente',
    estado_pedido ENUM('Pendiente', 'Listo', 'Entregado') DEFAULT 'Pendiente',
    total DECIMAL(10, 2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB;

-- 3. Tabla de detalles_pedido (Relación con Productos)
CREATE TABLE IF NOT EXISTS detalles_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    producto_id INT NOT NULL,
    color VARCHAR(100),
    talla_superior VARCHAR(10),
    talla_inferior VARCHAR(10),
    tela VARCHAR(100),
    estampado_bordado TEXT,
    cantidad INT NOT NULL DEFAULT 1,
    subtotal DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Datos de ejemplo iniciales
INSERT INTO productos (nombre, descripcion, precio_base) VALUES 
('Filipina Quirúrgica Clásica', 'Cuello en V, tela antifluido básica.', 280.00),
('Pantalón Jogger Médico', 'Con resorte en tobillos y múltiples bolsas.', 220.00),
('Bata Médica Premium', 'Bata larga blanca de alta resistencia.', 450.00);
-- 4. Tabla de Usuarios (Acceso al sistema)
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100)
) ENGINE=InnoDB;

-- Usuario por defecto (usuario: admin, password: admin123)
INSERT INTO usuarios (usuario, password, nombre) VALUES ('admin', 'admin123', 'Administrador');
