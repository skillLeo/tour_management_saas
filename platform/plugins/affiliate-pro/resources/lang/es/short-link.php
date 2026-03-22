<?php

return [
    'name' => 'Enlaces Cortos',
    'short_link' => 'Enlace Corto',
    'short_links' => 'Enlaces Cortos',
    'create' => 'Crear Enlace Corto',
    'edit' => 'Editar Enlace Corto',
    'delete' => 'Eliminar Enlace Corto',
    'short_link_details' => 'Detalles del Enlace Corto',
    'untitled' => 'Enlace Sin Título',

    // Form fields
    'affiliate' => 'Afiliado',
    'title' => 'Título',
    'title_placeholder' => 'Ingrese un título descriptivo para este enlace corto',
    'short_code' => 'Código Corto',
    'short_code_placeholder' => 'Ingrese código corto único (ej: summer2024)',
    'short_code_help' => 'Esto se usará en la URL: yoursite.com/go/[short-code]',
    'destination_url' => 'URL de Destino',
    'destination_url_help' => 'La URL completa donde se redirigirá a los usuarios',
    'product' => 'Producto',
    'product_help' => 'Opcional: Vincule este enlace corto a un producto específico para un mejor seguimiento',
    'all_products' => 'Todos los Productos',
    'short_url' => 'URL Corta',
    'clicks' => 'Clics',
    'conversions' => 'Conversiones',
    'conversion_rate' => 'Tasa de Conversión',
    'total_clicks' => 'Total de Clics',
    'total_conversions' => 'Total de Conversiones',
    'statistics' => 'Estadísticas',
    'actions' => 'Acciones',
    'created_at' => 'Creado el',
    'updated_at' => 'Actualizado el',

    // Validation messages
    'affiliate_required' => 'Por favor seleccione un afiliado.',
    'affiliate_not_exists' => 'El afiliado seleccionado no existe.',
    'short_code_required' => 'El código corto es obligatorio.',
    'short_code_unique' => 'Este código corto ya está en uso.',
    'short_code_alpha_dash' => 'El código corto solo puede contener letras, números, guiones y guiones bajos.',
    'destination_url_required' => 'La URL de destino es obligatoria.',
    'destination_url_invalid' => 'Por favor ingrese una URL válida.',
    'product_not_exists' => 'El producto seleccionado no existe.',

    // Actions
    'copy_url' => 'Copiar URL',
    'copy_short_url' => 'Copiar URL Corta',
    'test_link' => 'Probar Enlace',
    'visit_destination' => 'Visitar Destino',
    'copied_to_clipboard' => '¡URL copiada al portapapeles!',
    'copy_failed' => 'Error al copiar la URL. Por favor intente nuevamente.',

    // UI sections
    'url_information' => 'Información de URL',
    'affiliate_product_info' => 'Información de Afiliado y Producto',
    'quick_actions' => 'Acciones Rápidas',
    'performance' => 'Rendimiento',
    'product_link' => 'Enlace de Producto',
    'general_link' => 'Enlace General',

    // Performance indicators
    'excellent' => 'Excelente',
    'good' => 'Bueno',
    'average' => 'Promedio',
    'no_data' => 'Sin Datos',

    'back' => 'Volver',

    // Status messages
    'affiliate_not_found' => 'Afiliado no encontrado',
    'short_link_not_found' => 'Enlace corto no encontrado',

    // Table headers
    'affiliate_column' => 'Afiliado',
    'title_column' => 'Título y Código',
    'destination_column' => 'Destino',
    'short_url_column' => 'URL Corta',
    'product_column' => 'Producto',
    'stats_column' => 'Estadísticas',
    'created_column' => 'Creado',

    // Bulk actions
    'bulk_delete_confirm' => '¿Está seguro de que desea eliminar estos enlaces cortos?',
    'bulk_delete_success' => 'Los enlaces cortos seleccionados se han eliminado correctamente.',

    // Permissions
    'permissions' => [
        'index' => 'Ver enlaces cortos',
        'create' => 'Crear enlaces cortos',
        'edit' => 'Editar enlaces cortos',
        'destroy' => 'Eliminar enlaces cortos',
    ],
];
