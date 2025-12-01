# TODO: Modificar Módulo de Registro de Interacciones

## Paso 1: Agregar alertas de color para eliminaciones

-   [x] Modificar la tabla en `resources/views/registros_interaccion/index.blade.php` para agregar clases CSS que resalten filas con acción 'delete' en rojo.

## Paso 2: Agregar modal para detalles de interacciones

-   [x] Crear un modal en la vista que muestre detalles completos de cada registro, incluyendo `registro_id`, `descripcion`, `datos_anteriores`, `datos_nuevos`.
-   [x] Agregar botón "Ver Detalles" en cada fila de la tabla.

## Paso 3: Mejorar filtros

-   [x] Revisar y simplificar los filtros existentes para que sean solo los necesarios: empleado, acción, módulo, fecha desde/hasta.
-   [x] Asegurarse de que los filtros sean intuitivos y eficientes.

## Paso 4: Agregar botón para imprimir reportes

-   [x] Agregar un botón "Imprimir" similar al de movimientos financieros.
-   [x] Crear una ruta y vista para el reporte de impresiones, basada en los filtros aplicados.
-   [x] Incluir estilos CSS para impresión.

## Paso 5: Actualizar controlador si es necesario

-   [x] Verificar si el controlador necesita cambios para pasar datos adicionales al modal o al reporte.

## Paso 6: Probar y ajustar

-   [ ] Verificar que todas las funcionalidades funcionen correctamente.
