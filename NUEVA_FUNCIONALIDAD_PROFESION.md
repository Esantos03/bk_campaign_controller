# 👔 Nueva Funcionalidad: Campo Profesión

## 📋 Descripción

Se ha agregado el campo **"profesión"** a los clientes, permitiendo categorizar y filtrar clientes según su ocupación profesional.

---

## ✅ Cambios Implementados

### 1. Base de Datos
**Migración:** `2026_03_04_011527_add_profesion_to_clientes_table.php`

```sql
ALTER TABLE clientes 
ADD COLUMN profesion VARCHAR(255) NULL AFTER email,
ADD INDEX idx_profesion (profesion);
```

**Características:**
- Campo nullable (opcional)
- Índice para búsquedas rápidas
- Ubicado después del campo email

### 2. Modelo Cliente
**Archivo:** `app/Models/Cliente.php`

```php
protected $fillable = [
    'nombre',
    'apellido',
    'telefono_whatsapp',
    'email',
    'profesion',  // ← NUEVO
    'estado',
];
```

### 3. Formulario de Cliente (Filament)
**Archivo:** `app/Filament/Resources/Clientes/Schemas/ClienteForm.php`

**Campo agregado:**
```php
Select::make('profesion')
    ->options([
        'Arquitecto' => 'Arquitecto',
        'Programador' => 'Programador',
        'Ingeniero' => 'Ingeniero',
        'Diseñador' => 'Diseñador',
        'Médico' => 'Médico',
        'Abogado' => 'Abogado',
        'Contador' => 'Contador',
        'Profesor' => 'Profesor',
        'Enfermero' => 'Enfermero',
        'Administrador' => 'Administrador',
        'Vendedor' => 'Vendedor',
        'Empresario' => 'Empresario',
        'Estudiante' => 'Estudiante',
        'Otro' => 'Otro',
    ])
    ->searchable()
    ->nullable()
    ->helperText('Selecciona la profesión del cliente')
```

**Características:**
- Select con 14 opciones predefinidas
- Searchable (búsqueda en el dropdown)
- Nullable (opcional)
- Helper text informativo

### 4. Tabla de Clientes (Filament)
**Archivo:** `app/Filament/Resources/Clientes/Tables/ClientesTable.php`

**Columna agregada:**
```php
TextColumn::make('profesion')
    ->searchable()
    ->sortable()
    ->badge()
    ->color(fn (string $state): string => match ($state) {
        'Arquitecto' => 'info',
        'Programador' => 'success',
        'Ingeniero' => 'warning',
        'Diseñador' => 'danger',
        'Médico' => 'primary',
        default => 'gray',
    })
    ->placeholder('Sin especificar')
```

**Características:**
- Searchable (búsqueda en tabla)
- Sortable (ordenamiento)
- Badge con colores por profesión
- Placeholder si está vacío

**Filtro agregado:**
```php
SelectFilter::make('profesion')
    ->options([...])
    ->multiple()
    ->searchable()
```

**Características:**
- Filtro múltiple (seleccionar varias profesiones)
- Searchable (búsqueda en filtro)
- Todas las profesiones disponibles

### 5. Seeder Actualizado
**Archivo:** `database/seeders/CampanaSeeder.php`

Los clientes de prueba ahora incluyen profesiones:
- Juan Pérez → Arquitecto
- María González → Programador
- Carlos Rodríguez → Ingeniero

---

## 🎨 Colores de Badges por Profesión

| Profesión | Color | Badge |
|-----------|-------|-------|
| Arquitecto | Info (Azul) | 🔵 |
| Programador | Success (Verde) | 🟢 |
| Ingeniero | Warning (Amarillo) | 🟡 |
| Diseñador | Danger (Rojo) | 🔴 |
| Médico | Primary (Morado) | 🟣 |
| Otros | Gray (Gris) | ⚪ |

---

## 📱 Uso en el Panel Admin

### Crear/Editar Cliente

1. Ir a **Admin → Clientes → Crear/Editar**
2. Llenar los campos obligatorios (nombre, apellido, teléfono)
3. Seleccionar **Profesión** del dropdown
4. El campo es opcional, puede dejarse vacío
5. Guardar

### Filtrar por Profesión

1. Ir a **Admin → Clientes**
2. Click en el icono de filtro (embudo)
3. Seleccionar **Profesión**
4. Elegir una o varias profesiones
5. La tabla se filtra automáticamente

**Ejemplo:**
- Filtrar por "Programador" y "Ingeniero"
- Muestra solo clientes con esas profesiones

### Buscar por Profesión

1. Ir a **Admin → Clientes**
2. Usar la barra de búsqueda
3. Escribir el nombre de la profesión
4. Los resultados se filtran en tiempo real

### Ordenar por Profesión

1. Ir a **Admin → Clientes**
2. Click en el encabezado de columna "Profesión"
3. Ordena alfabéticamente (A-Z o Z-A)

---

## 🔍 Profesiones Disponibles

1. **Arquitecto** 🏗️
2. **Programador** 💻
3. **Ingeniero** ⚙️
4. **Diseñador** 🎨
5. **Médico** 🏥
6. **Abogado** ⚖️
7. **Contador** 📊
8. **Profesor** 📚
9. **Enfermero** 💉
10. **Administrador** 📋
11. **Vendedor** 🛍️
12. **Empresario** 💼
13. **Estudiante** 🎓
14. **Otro** ❓

---

## 💡 Casos de Uso

### 1. Segmentación de Campañas
**Escenario:** Crear campaña específica para profesionales de tecnología

**Pasos:**
1. Filtrar clientes por "Programador" e "Ingeniero"
2. Crear actividad técnica (ej: "Taller de IA")
3. Asignar solo estos clientes filtrados

### 2. Reportes por Profesión
**Escenario:** Analizar qué profesiones tienen mayor asistencia

**Pasos:**
1. Exportar lista de clientes con profesión
2. Cruzar con datos de confirmaciones
3. Identificar profesiones más activas

### 3. Comunicación Personalizada
**Escenario:** Enviar mensajes adaptados a cada profesión

**Pasos:**
1. Filtrar por profesión
2. Crear campaña con mensaje específico
3. Ejemplo: "Taller de diseño arquitectónico" para Arquitectos

---

## 🔧 Personalización

### Agregar Nuevas Profesiones

**Archivo:** `app/Filament/Resources/Clientes/Schemas/ClienteForm.php`

```php
Select::make('profesion')
    ->options([
        // ... profesiones existentes
        'Nueva Profesión' => 'Nueva Profesión',  // ← Agregar aquí
    ])
```

**También actualizar en:**
- `app/Filament/Resources/Clientes/Tables/ClientesTable.php` (filtro)

### Cambiar Colores de Badges

**Archivo:** `app/Filament/Resources/Clientes/Tables/ClientesTable.php`

```php
->color(fn (string $state): string => match ($state) {
    'Arquitecto' => 'info',      // Cambiar color aquí
    'Programador' => 'success',
    // ...
})
```

**Colores disponibles:**
- `primary` (morado)
- `success` (verde)
- `warning` (amarillo)
- `danger` (rojo)
- `info` (azul)
- `gray` (gris)

---

## 📊 Consultas SQL Útiles

### Contar clientes por profesión
```sql
SELECT profesion, COUNT(*) as total
FROM clientes
WHERE profesion IS NOT NULL
GROUP BY profesion
ORDER BY total DESC;
```

### Clientes sin profesión
```sql
SELECT * FROM clientes
WHERE profesion IS NULL;
```

### Top 5 profesiones
```sql
SELECT profesion, COUNT(*) as total
FROM clientes
WHERE profesion IS NOT NULL
GROUP BY profesion
ORDER BY total DESC
LIMIT 5;
```

---

## 🧪 Testing

### Verificar Campo en BD
```bash
php artisan tinker
>>> DB::table('clientes')->first()->profesion
```

### Crear Cliente con Profesión
```bash
php artisan tinker
>>> App\Models\Cliente::create([
    'nombre' => 'Test',
    'apellido' => 'Usuario',
    'telefono_whatsapp' => '9999999999',
    'profesion' => 'Programador',
    'estado' => 'activo'
]);
```

### Filtrar por Profesión
```bash
php artisan tinker
>>> App\Models\Cliente::where('profesion', 'Programador')->get();
```

---

## 📝 Notas Técnicas

### Índice de Base de Datos
- Se agregó índice en `profesion` para optimizar búsquedas
- Mejora performance en filtros y ordenamiento

### Nullable
- El campo es opcional (nullable)
- Clientes existentes tienen `NULL` por defecto
- Se puede actualizar manualmente o dejar vacío

### Validación
- No hay validación estricta (acepta cualquier texto)
- El select limita opciones en UI
- Se puede escribir texto libre si se modifica directamente en BD

---

## 🔄 Migración de Datos Existentes

Si tienes clientes existentes sin profesión:

### Opción 1: Actualizar Manualmente
```sql
UPDATE clientes 
SET profesion = 'Programador' 
WHERE id IN (1, 2, 3);
```

### Opción 2: Actualizar desde Panel Admin
1. Ir a cada cliente
2. Editar
3. Seleccionar profesión
4. Guardar

### Opción 3: Script de Migración
```php
// En tinker o seeder
$clientes = Cliente::whereNull('profesion')->get();
foreach ($clientes as $cliente) {
    $cliente->update(['profesion' => 'Otro']);
}
```

---

## ✅ Checklist de Verificación

- [x] Migración ejecutada
- [x] Campo agregado al modelo
- [x] Formulario actualizado
- [x] Tabla actualizada con columna
- [x] Filtro implementado
- [x] Búsqueda habilitada
- [x] Ordenamiento habilitado
- [x] Badges con colores
- [x] Seeder actualizado
- [x] Datos de prueba actualizados
- [x] Índice en base de datos

---

## 🎉 Resultado Final

Los clientes ahora tienen un campo de profesión que permite:

✅ **Categorizar** clientes por ocupación
✅ **Filtrar** múltiples profesiones simultáneamente
✅ **Buscar** por profesión en la tabla
✅ **Ordenar** alfabéticamente
✅ **Visualizar** con badges de colores
✅ **Segmentar** campañas por profesión

**¡Funcionalidad lista para usar!** 🚀
