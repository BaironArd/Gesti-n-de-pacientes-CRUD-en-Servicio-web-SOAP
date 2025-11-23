<?php
// 📋 LISTA DE PACIENTES - Vista que muestra todos los registros con opciones de gestión
// 🚀 CONFIGURACIÓN PARA DEPURACIÓN - Muestra todos los errores
ini_set("display_errors", 1);
error_reporting(E_ALL);

// 🔄 DESHABILITAR CACHÉ WSDL - Para desarrollo y evitar problemas de caché
ini_set("soap.wsdl_cache_enabled", "0");
ini_set("soap.wsdl_cache_ttl", "0");

// 📦 INCLUIR CLIENTE SOAP - Para comunicación con el servicio backend
require_once __DIR__ . '/../backend/soap_client.php';

// 🌐 CONFIGURAR CLIENTE SOAP - Conectar al servicio WSDL
$wsdl = 'http://localhost/pacientes_soap/backend/pacientes.wsdl';
$client = new PacientesSoapClient($wsdl);

$error = "";
$patients = [];

try {
    // 📞 LLAMADA AL SERVICIO SOAP - Obtener todos los pacientes
    $result = $client->getPatients();

    // 🔄 CONVERTIR RESPUESTA SOAP - De objeto a array PHP
    $data = json_decode(json_encode($result), true);

    // 🎯 EXTRAER LISTA DE PACIENTES - Diferentes estructuras posibles
    if (isset($data['paciente'])) {
        $patients = $data['paciente'];
    } elseif (isset($data['return']['paciente'])) {
        $patients = $data['return']['paciente'];
    } elseif (isset($data['return'])) {
        $patients = $data['return'];
    } else {
        $patients = $data;
    }

    // 🔧 NORMALIZAR ESTRUCTURA - Si es un solo paciente, convertirlo en array
    if (!empty($patients) && isset($patients['id'])) {
        $patients = [$patients];
    }

    // 🛡️ GARANTIZAR QUE SEA ARRAY - Prevenir errores en el foreach
    if (!is_array($patients)) {
        $patients = [];
    }

} catch (Throwable $e) {
    // ❌ MANEJO DE ERRORES - Capturar y mostrar errores de conexión SOAP
    $patients = [];
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Listado de Pacientes</title>  <!-- 🏷️ TÍTULO DE LA PÁGINA -->
<link rel="stylesheet" href="../assets/style.css">  <!-- 🎨 ESTILOS COMPARTIDOS -->
</head>
<body>

<div class="container">

    <!-- 🎯 CABECERA - Título y navegación -->
    <div class="header">
        <div class="brand">
            <div class="logo">GP</div>
            <div>
                <div class="title">Listado de Pacientes</div>  <!-- 📊 TÍTULO CONTEXTUAL -->
                <div class="subtitle">Editar o eliminar registros</div>  <!-- 🛠️ DESCRIPCIÓN DE ACCIONES -->
            </div>
        </div>

        <div class="actions">
            <a href="index.php"><button class="btn btn-ghost">Volver</button></a>  <!-- 🏠 NAVEGACIÓN -->
            <a href="create.php"><button class="btn btn-primary">Registrar Paciente</button></a>  <!-- ➕ ACCIÓN PRINCIPAL -->
        </div>
    </div>

    <!-- ✅ MENSAJES DE ÉXITO - Confirmación de operaciones exitosas -->
    <?php if (!empty($_GET['success'])): ?>
        <div class="centered-note" style="color:green; background:#e6ffe6; padding:12px; border-radius:8px; margin-bottom:20px;">
            ✔ <?= htmlspecialchars($_GET['success']) ?>  <!-- 🎉 ICONO DE CONFIRMACIÓN -->
        </div>
    <?php endif; ?>

    <!-- ❌ MENSAJES DE ERROR - Problemas en operaciones -->
    <?php if (!empty($_GET['error'])): ?>
        <div class="centered-note" style="color:red; background:#ffe6e6; padding:12px; border-radius:8px; margin-bottom:20px;">
            Error: <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php endif; ?>

    <!-- 🌐 ERRORES DEL SISTEMA - Problemas de conexión SOAP -->
    <?php if (!empty($error)): ?>
        <div class="centered-note" style="color:red; background:#ffe6e6; padding:12px; border-radius:8px; margin-bottom:20px;">
            Error del sistema: <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- 📊 TABLA PRINCIPAL - Listado de pacientes -->
    <table class="table">
        <thead>
            <tr>
                <!-- 🏷️ ENCABEZADOS DE COLUMNAS - Todos los campos del paciente -->
                <th>ID</th>  <!-- 🔑 IDENTIFICADOR ÚNICO -->
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Documento</th>  <!-- 🆔 DOCUMENTO PARA ELIMINACIÓN -->
                <th>Edad</th>
                <th>Sexo</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Fecha Registro</th>
                <th>Acciones</th>  <!-- 🛠️ COLUMNA DE OPERACIONES -->
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($patients)): ?>
            <?php foreach ($patients as $p): ?>
                <?php $p = (array)$p; ?>  <!-- 🔄 ASEGURAR QUE SEA ARRAY -->
                <tr>
                    <!-- 📝 CELDAS DE DATOS - Mostrar información de cada paciente -->
                    <td><?= htmlspecialchars($p['id'] ?? '') ?></td>
                    <td><?= htmlspecialchars($p['nombre'] ?? '') ?></td>
                    <td><?= htmlspecialchars($p['apellido'] ?? '') ?></td>
                    <td><?= htmlspecialchars($p['documento'] ?? '') ?></td>
                    <td><?= htmlspecialchars($p['edad'] ?? '') ?></td>
                    <td><?= htmlspecialchars($p['sexo'] ?? '') ?></td>
                    <td><?= htmlspecialchars($p['telefono'] ?? '') ?></td>
                    <td><?= htmlspecialchars($p['direccion'] ?? '') ?></td>
                    <td><?= htmlspecialchars($p['fecha_registro'] ?? '') ?></td>
                    <td class="actions-row">
                        <!-- ✏️ BOTÓN EDITAR - Navega a formulario de edición -->
                        <a href="edit.php?id=<?= urlencode($p['id']) ?>">
                            <button class="btn btn-ghost">Editar</button>
                        </a>
                        <!-- 🗑️ BOTÓN ELIMINAR - Confirmación JavaScript antes de eliminar -->
                        <a href="delete.php?documento=<?= urlencode($p['documento']) ?>" 
                        onclick="return confirm('¿Está seguro de eliminar este paciente?')">  <!-- ⚠️ CONFIRMACIÓN -->
                            <button class="btn btn-danger">Eliminar</button>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- 📭 ESTADO VACÍO - Mensaje cuando no hay pacientes -->
            <tr>
                <td colspan="10" style="text-align:center; color:#777; padding:20px;">
                    No hay pacientes registrados. 
                    <a href="create.php" style="color:#4CAF50;">Registrar uno nuevo</a>  <!-- 💡 LLAMADO A LA ACCIÓN -->
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

</div>

</body>
</html>