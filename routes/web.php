<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.index');
});

Auth::routes();

Route::get('/home', function() {
    if (auth()->user()->hasRole('mozo')) return redirect('/admin/mozo/panel');
    if (auth()->user()->hasRole('chef')) return redirect('/admin/cocina/panel');
    return redirect('/admin');
})->name('home')->middleware('auth');

Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index')->middleware(['auth']);

// Configuraciones
Route::get('/admin/configuraciones', [App\Http\Controllers\ConfiguracionController::class, 'index'])->name('admin.configuracion.index')->middleware(['auth', 'can:Ver Configuraciones']);
Route::get('/admin/configuraciones/create', [App\Http\Controllers\ConfiguracionController::class, 'create'])->name('admin.configuracion.create')->middleware(['auth', 'can:Crear Configuraciones']);
Route::post('/admin/configuraciones/create', [App\Http\Controllers\ConfiguracionController::class, 'store'])->name('admin.configuracion.store')->middleware(['auth', 'can:Guardar Configuraciones']);
Route::get('/admin/configuraciones/{id}', [App\Http\Controllers\ConfiguracionController::class, 'show'])->name('admin.configuracion.show')->middleware(['auth', 'can:Ver Configuraciones']);
Route::get('/admin/configuraciones/{id}/edit', [App\Http\Controllers\ConfiguracionController::class, 'edit'])->name('admin.configuracion.edit')->middleware(['auth', 'can:Editar Configuraciones']);
Route::put('/admin/configuraciones/{id}', [App\Http\Controllers\ConfiguracionController::class, 'update'])->name('admin.configuracion.update')->middleware(['auth', 'can:Actualizar Configuraciones']);
Route::delete('/admin/configuraciones/{id}', [App\Http\Controllers\ConfiguracionController::class, 'destroy'])->name('admin.configuracion.destroy')->middleware(['auth', 'can:Eliminar Configuraciones']);

// Roles
Route::get('/admin/roles', [App\Http\Controllers\RoleController::class, 'index'])->name('admin.roles.index')->middleware(['auth', 'can:Ver Roles']);
Route::get('/admin/roles/create', [App\Http\Controllers\RoleController::class, 'create'])->name('admin.roles.create')->middleware(['auth', 'can:Crear Roles']);
Route::post('/admin/roles/create', [App\Http\Controllers\RoleController::class, 'store'])->name('admin.roles.store')->middleware(['auth', 'can:Guardar Roles']);
Route::get('/admin/roles/{id}', [App\Http\Controllers\RoleController::class, 'show'])->name('admin.roles.show')->middleware(['auth', 'can:Ver Roles']);
Route::get('/admin/roles/{id}/asignar', [App\Http\Controllers\RoleController::class, 'asignar_roles'])->name('admin.roles.asignar_roles')->middleware(['auth', 'can:Asignar Roles']);
Route::put('/admin/roles/asignar/{id}', [App\Http\Controllers\RoleController::class, 'update_asignar'])->name('admin.roles.update_asignar')->middleware(['auth', 'can:Asignar Roles']);
Route::get('/admin/roles/{id}/edit', [App\Http\Controllers\RoleController::class, 'edit'])->name('admin.roles.edit')->middleware(['auth', 'can:Editar Roles']);
Route::put('/admin/roles/{id}', [App\Http\Controllers\RoleController::class, 'update'])->name('admin.roles.update')->middleware(['auth', 'can:Actualizar Roles']);
Route::delete('/admin/roles/{id}', [App\Http\Controllers\RoleController::class, 'destroy'])->name('admin.roles.destroy')->middleware(['auth', 'can:Eliminar Roles']);

// Usuarios
Route::get('/admin/usuarios', [App\Http\Controllers\UsuarioController::class, 'index'])->name('admin.usuarios.index')->middleware(['auth', 'can:Ver Usuarios']);
Route::get('/admin/usuarios/create', [App\Http\Controllers\UsuarioController::class, 'create'])->name('admin.usuarios.create')->middleware(['auth', 'can:Crear Usuarios']);
Route::post('/admin/usuarios/create', [App\Http\Controllers\UsuarioController::class, 'store'])->name('admin.usuarios.store')->middleware(['auth', 'can:Guardar Usuarios']);
Route::get('/admin/usuarios/{id}', [App\Http\Controllers\UsuarioController::class, 'show'])->name('admin.usuarios.show')->middleware(['auth', 'can:Ver Usuarios']);
Route::get('/admin/usuarios/{id}/edit', [App\Http\Controllers\UsuarioController::class, 'edit'])->name('admin.usuarios.edit')->middleware(['auth', 'can:Editar Usuarios']);
Route::put('/admin/usuarios/{id}', [App\Http\Controllers\UsuarioController::class, 'update'])->name('admin.usuarios.update')->middleware(['auth', 'can:Actualizar Usuarios']);
Route::delete('/admin/usuarios/{id}', [App\Http\Controllers\UsuarioController::class, 'destroy'])->name('admin.usuarios.destroy')->middleware(['auth', 'can:Eliminar Usuarios']);

// QR mesas
Route::get('admin/mesas/qr', [App\Http\Controllers\MesaController::class, 'qrCodes'])->name('admin.mesas.qr');

// Mesas
use App\Http\Controllers\MesaController;
Route::middleware(['auth', 'can:Gestionar Mesas'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('mesas', MesaController::class);
});

// Categorías plato
use App\Http\Controllers\CategoriaPlatoController;
Route::middleware(['auth', 'can:Gestionar Categorias Plato'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('categorias_plato', CategoriaPlatoController::class);
});

// Platos
use App\Http\Controllers\PlatoController;
Route::middleware(['auth', 'can:Gestionar Platos'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('platos', PlatoController::class);
});

// Clientes
Route::middleware(['auth', 'can:Gestionar Clientes'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('clientes', App\Http\Controllers\ClienteController::class);
});

// Ventas - CON caja.abierta
Route::middleware(['auth', 'can:Gestionar Ventas', 'caja.abierta'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('ventas', App\Http\Controllers\VentaController::class);
});

Route::get('ventas/{venta}/nota-venta-pdf', [App\Http\Controllers\VentaController::class, 'notaVentaPdf'])->name('admin.ventas.nota_venta_pdf');

// Pedidos - CON caja.abierta
Route::middleware(['auth', 'can:Gestionar Pedidos', 'caja.abierta'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('pedidos', App\Http\Controllers\PedidoController::class);
    Route::get('/pedidos/platos/{ventaId}', [App\Http\Controllers\PedidoController::class, 'buscarPlatosPorVenta']);
});

// Proveedores
Route::get('/admin/proveedores', [App\Http\Controllers\ProveedorController::class, 'index'])->name('admin.proveedores.index')->middleware(['auth', 'can:Ver Proveedores']);
Route::get('/admin/proveedores/create', [App\Http\Controllers\ProveedorController::class, 'create'])->name('admin.proveedores.create')->middleware(['auth', 'can:Crear Proveedores']);
Route::post('/admin/proveedores/create', [App\Http\Controllers\ProveedorController::class, 'store'])->name('admin.proveedores.store')->middleware(['auth', 'can:Guardar Proveedores']);
Route::get('/admin/proveedores/{proveedor}/edit', [App\Http\Controllers\ProveedorController::class, 'edit'])->name('admin.proveedores.edit')->middleware(['auth', 'can:Editar Proveedores']);
Route::put('/admin/proveedores/{proveedor}', [App\Http\Controllers\ProveedorController::class, 'update'])->name('admin.proveedores.update')->middleware(['auth', 'can:Actualizar Proveedores']);
Route::delete('/admin/proveedores/{proveedor}', [App\Http\Controllers\ProveedorController::class, 'destroy'])->name('admin.proveedores.destroy')->middleware(['auth', 'can:Eliminar Proveedores']);

Route::get('admin/ventas/{id}/detalles', function($id) {
    $venta = App\Models\Venta::with('detalleVenta.plato')->findOrFail($id);
    return response()->json($venta->detalleVenta->map(fn($d) => [
        'plato'    => $d->plato->nombre ?? 'Sin nombre',
        'cantidad' => $d->cantidad,
        'precio'   => number_format($d->precio_unitario, 2),
        'subtotal' => number_format($d->subtotal, 2),
    ]));
})->middleware('auth');

// Compras
Route::middleware(['auth', 'can:Gestionar Compras'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('compras', App\Http\Controllers\CompraController::class);
});

// Ingredientes
Route::middleware(['auth', 'can:Gestionar Ingredientes'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('ingredientes', App\Http\Controllers\IngredienteController::class);
});

// Ingredientes platos
Route::get('/admin/ingredientes_platos', [App\Http\Controllers\IngredientePlatoController::class, 'index'])->name('admin.ingredientes_platos.index')->middleware(['auth', 'can:Ver Ingredientes Platos']);
Route::get('/admin/ingredientes_platos/create', [App\Http\Controllers\IngredientePlatoController::class, 'create'])->name('admin.ingredientes_platos.create')->middleware(['auth', 'can:Crear Ingredientes Platos']);
Route::post('/admin/ingredientes_platos/create', [App\Http\Controllers\IngredientePlatoController::class, 'store'])->name('admin.ingredientes_platos.store')->middleware(['auth', 'can:Guardar Ingredientes Platos']);
Route::get('/admin/ingredientes_platos/{ingredientePlato}/edit', [App\Http\Controllers\IngredientePlatoController::class, 'edit'])->name('admin.ingredientes_platos.edit')->middleware(['auth', 'can:Editar Ingredientes Platos']);
Route::put('/admin/ingredientes_platos/{ingredientePlato}', [App\Http\Controllers\IngredientePlatoController::class, 'update'])->name('admin.ingredientes_platos.update')->middleware(['auth', 'can:Actualizar Ingredientes Platos']);
Route::delete('/admin/ingredientes_platos/{ingredientePlato}', [App\Http\Controllers\IngredientePlatoController::class, 'destroy'])->name('admin.ingredientes_platos.destroy')->middleware(['auth', 'can:Eliminar Ingredientes Platos']);

// Facturas / XML / SUNAT
Route::get('admin/ventas/{venta}/factura', [App\Http\Controllers\VentaController::class, 'factura'])->name('admin.ventas.factura');
Route::get('admin/ventas/{venta}/xml', [App\Http\Controllers\VentaController::class, 'generarXmlLocal'])->name('admin.ventas.xml');
Route::post('/admin/ventas/{id}/sunat', [App\Http\Controllers\VentaController::class, 'enviarSunat'])->name('admin.ventas.sunat');

Route::middleware(['auth'])->get('/admin/mozo/pedidos', [App\Http\Controllers\PedidoController::class, 'panel'])->name('admin.mozo.pedidos');

Route::get('/testsoap', function () {
    return class_exists('SoapClient') ? 'SOAP ACTIVO' : 'SOAP NO ACTIVO';
});

// COCINA - SIN caja.abierta
Route::middleware(['auth', 'role:chef|ADMINISTRADOR'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('cocina/panel', [App\Http\Controllers\CocinaController::class, 'panel'])->name('cocina.panel');
    Route::put('cocina/pedido/{pedido}/estado', [App\Http\Controllers\CocinaController::class, 'cambiarEstado'])->name('cocina.pedido.estado');
    Route::get('cocina/pedidos/nuevos', [App\Http\Controllers\CocinaController::class, 'pedidosNuevos'])->name('cocina.pedidos.nuevos');
});

// MOZO - SIN caja.abierta (el mozo puede ver su panel aunque caja esté cerrada)
Route::middleware(['auth', 'role:mozo|ADMINISTRADOR'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('mozo/panel', [App\Http\Controllers\MozoController::class, 'panel'])->name('mozo.panel');
    Route::get('mozo/pedidos/estado', [App\Http\Controllers\MozoController::class, 'estadoPedidos'])->name('mozo.pedidos.estado');
    Route::put('mozo/pedido/{id}/entregar', [App\Http\Controllers\MozoController::class, 'marcarEntregado'])->name('mozo.pedido.entregar');
    Route::get('mozo/pedido/{id}/cobrar', [App\Http\Controllers\MozoController::class, 'cobrar'])->name('mozo.pedido.cobrar');
    Route::post('mozo/pedido/{id}/procesar-cobro', [App\Http\Controllers\MozoController::class, 'procesarCobro'])->name('mozo.pedido.procesar_cobro');
});

// MOZO crear pedido - CON caja.abierta (no puede crear pedidos sin caja)
Route::middleware(['auth', 'role:mozo|ADMINISTRADOR', 'caja.abierta'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('mozo/pedido', [App\Http\Controllers\MozoController::class, 'crearPedido'])->name('mozo.pedido.crear');
});

// PANTALLA PÚBLICA
Route::get('/pantalla', [App\Http\Controllers\PantallaController::class, 'index'])->name('pantalla');
Route::get('/pantalla/pedidos/json', [App\Http\Controllers\PantallaController::class, 'pedidosJson'])->name('pantalla.json');

// CAJA
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('caja', [App\Http\Controllers\CajaController::class, 'index'])->name('caja.index');
    Route::post('caja/abrir', [App\Http\Controllers\CajaController::class, 'abrir'])->name('caja.abrir');
    Route::get('caja/cobros-mozos', [App\Http\Controllers\CajaController::class, 'cobrosMozos'])->name('caja.cobros_mozos');
    Route::post('caja/{caja}/cerrar', [App\Http\Controllers\CajaController::class, 'cerrar'])->name('caja.cerrar');
    Route::get('caja/{caja}/reporte', [App\Http\Controllers\CajaController::class, 'reportePdf'])->name('caja.reporte');
});

// CARTA VIRTUAL
Route::get('/carta', [App\Http\Controllers\CartaController::class, 'index'])->name('carta');
Route::get('/carta/mesa/{mesa}', [App\Http\Controllers\CartaController::class, 'mesa'])->name('carta.mesa');
Route::post('/carta/pedido', [App\Http\Controllers\CartaController::class, 'pedido'])->name('carta.pedido');

// REPORTES
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('reportes', [App\Http\Controllers\ReporteController::class, 'index'])->name('reportes.index');
    Route::get('reportes/pdf', [App\Http\Controllers\ReporteController::class, 'exportPdf'])->name('reportes.pdf');
    Route::get('reportes/excel', [App\Http\Controllers\ReporteController::class, 'exportExcel'])->name('reportes.excel');
});

Route::get('admin/ventas/{id}/detalles', function($id) {
    $venta = App\Models\Venta::with('detalleVenta.plato', 'pagos')->findOrFail($id);
    return response()->json([
        'platos' => $venta->detalleVenta->map(fn($d) => [
            'plato'    => $d->plato->nombre ?? 'Sin nombre',
            'cantidad' => $d->cantidad,
            'precio'   => number_format($d->precio_unitario, 2),
            'subtotal' => number_format($d->subtotal, 2),
        ]),
        'pagos' => $venta->pagos->map(fn($p) => [
            'metodo' => $p->metodo,
            'monto'  => number_format($p->monto, 2),
        ]),
        'total' => number_format($venta->total, 2),
    ]);
})->middleware('auth');

Route::post('admin/clientes/rapido', function(\Illuminate\Http\Request $req) {
    $doc = $req->documento ?: 'SC' . str_pad(\App\Models\Cliente::count() + 1, 4, '0', STR_PAD_LEFT);
    $cliente = \App\Models\Cliente::create([
        'nombre'         => $req->nombre,
        'documento'      => $doc,
        'tipo_documento' => 'DNI',
        'email'          => '',
        'telefono'       => ''
    ]);
    return response()->json(['success' => true, 'id' => $cliente->id, 'nombre' => $cliente->nombre, 'documento' => $cliente->documento]);
})->middleware('auth');


// ══════════════════════════════════════════
// MÓDULOS CONTROL DE AULA - IE Sor Annetta
// ══════════════════════════════════════════

// Alumnos
Route::resource('admin/alumnos', App\Http\Controllers\AlumnoController::class)->names('alumnos');

// Actividades
Route::resource('admin/actividades', App\Http\Controllers\ActividadController::class)->names('actividades');

// Cuotas / Cobros
Route::get('admin/cuotas',              [App\Http\Controllers\CuotaController::class, 'index'])->name('cuotas.index');
Route::post('admin/cuotas',             [App\Http\Controllers\CuotaController::class, 'store'])->name('cuotas.store');
Route::delete('admin/cuotas/{cobro}',   [App\Http\Controllers\CuotaController::class, 'destroy'])->name('cuotas.destroy');

// Gastos
Route::resource('admin/gastos', App\Http\Controllers\GastoController::class)->names('gastos');

// Reuniones
Route::resource('admin/reuniones', App\Http\Controllers\ReunionController::class)
     ->names('reuniones')
     ->parameters(['reuniones' => 'reunion']);
Route::post('admin/reuniones/{reunion}/asistencia', [App\Http\Controllers\ReunionController::class, 'asistencia'])->name('reuniones.asistencia');

// Reportes aula
Route::get('admin/reportes-aula', [App\Http\Controllers\ReporteController::class, 'index'])->name('reportes.aula');

// Configuración del aula
Route::get('admin/configuracion-aula',  [App\Http\Controllers\ConfiguracionAulaController::class, 'index'])->name('configuracion.aula');
Route::put('admin/configuracion-aula',  [App\Http\Controllers\ConfiguracionAulaController::class, 'update'])->name('configuracion.aula.update');

// Usuarios del colegio
Route::get('admin/usuarios-aula', [App\Http\Controllers\UsuarioController::class, 'index'])->name('usuarios.aula');
Route::get('admin/usuarios-aula/create', [App\Http\Controllers\UsuarioController::class, 'create'])->name('usuarios.aula.create');
Route::post('admin/usuarios-aula', [App\Http\Controllers\UsuarioController::class, 'store'])->name('usuarios.aula.store');
Route::get('admin/usuarios-aula/{user}/edit', [App\Http\Controllers\UsuarioController::class, 'edit'])->name('usuarios.aula.edit');
Route::put('admin/usuarios-aula/{user}', [App\Http\Controllers\UsuarioController::class, 'update'])->name('usuarios.aula.update');
Route::delete('admin/usuarios-aula/{user}', [App\Http\Controllers\UsuarioController::class, 'destroy'])->name('usuarios.aula.destroy');

// Portal padres
Route::get('admin/portal-padres', [App\Http\Controllers\PadreController::class, 'index'])->name('portal.padres');

// PDFs
Route::get('admin/pdf/lista-alumnos',     [App\Http\Controllers\PdfController::class, 'listaAlumnos'])->name('pdf.alumnos');
Route::get('admin/pdf/asistencia/{reunion}', [App\Http\Controllers\PdfController::class, 'asistenciaReunion'])->name('pdf.asistencia');
Route::get('admin/pdf/estado-pagos',      [App\Http\Controllers\PdfController::class, 'estadoPagos'])->name('pdf.estado-pagos');
Route::get('admin/pdf/cobros-actividad',  [App\Http\Controllers\PdfController::class, 'cobrosActividad'])->name('pdf.cobros');
Route::get('admin/pdf/gastos',            [App\Http\Controllers\PdfController::class, 'gastos'])->name('pdf.gastos');
Route::get('admin/pdf/ingresos-gastos',   [App\Http\Controllers\PdfController::class, 'ingresosGastos'])->name('pdf.ingresos-gastos');
Route::get('admin/pdf/utilidad',          [App\Http\Controllers\PdfController::class, 'utilidad'])->name('pdf.utilidad');


// Caja Chica
Route::get('admin/caja-chica',                    [App\Http\Controllers\CajaChicaController::class, 'index'])->name('caja.index');
Route::post('admin/caja-chica/abrir',             [App\Http\Controllers\CajaChicaController::class, 'abrir'])->name('caja.abrir');
Route::post('admin/caja-chica/egreso',            [App\Http\Controllers\CajaChicaController::class, 'egreso'])->name('caja.egreso');
Route::post('admin/caja-chica/reponer',           [App\Http\Controllers\CajaChicaController::class, 'reponer'])->name('caja.reponer');
Route::post('admin/caja-chica/cerrar',            [App\Http\Controllers\CajaChicaController::class, 'cerrar'])->name('caja.cerrar');
Route::delete('admin/caja-chica/movimiento/{movimiento}', [App\Http\Controllers\CajaChicaController::class, 'eliminarMovimiento'])->name('caja.movimiento.eliminar');
Route::get('admin/caja-chica/{caja}/pdf',         [App\Http\Controllers\CajaChicaController::class, 'pdf'])->name('caja.pdf');
