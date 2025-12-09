-- Agregar columnas a la tabla fletes
ALTER TABLE fletes 
ADD COLUMN IF NOT EXISTS cliente_id BIGINT UNSIGNED NULL AFTER id,
ADD COLUMN IF NOT EXISTS descripcion TEXT NULL AFTER telefono;

-- Agregar foreign key
ALTER TABLE fletes
ADD CONSTRAINT fk_fletes_cliente 
FOREIGN KEY (cliente_id) REFERENCES cliente(ID_cliente) ON DELETE SET NULL;
