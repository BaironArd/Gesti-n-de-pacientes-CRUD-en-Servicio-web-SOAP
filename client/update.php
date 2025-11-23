<?php
// ✏️ PROCESADOR DE ACTUALIZACIÓN - Maneja el envío del formulario de edición
// 📦 INCLUIR CLIENTE SOAP - Para comunicación con el backend
require_once __DIR__ . '/../backend/soap_client.php';

// 🛡️ VALIDACIÓN DE CAMPOS OBLIGATORIOS - Verificar datos mínimos requeridos
if (empty($_POST['id']) || empty($_POST['nombre']) || empty($_POST['apellido']) || empty($_POST['documento'])) {
    // ❌ DATOS INCOMPLETOS - Redirigir al listado con error
    header("Location: list.php?error=Faltan datos obligatorios");
    exit;
}

try {
    // 🌐 CONFIGURAR CLIENTE SOAP - Conectar al servicio WSDL
    $wsdl = 'http://localhost/pacientes_soap/backend/pacientes.wsdl';
    $client = new PacientesSoapClient($wsdl);

    // 🏗️ PREPARAR DATOS PARA ACTUALIZACIÓN - Estructura completa del paciente
    $data = [
        "id" => (int)$_POST['id'],                   // 🔑 ID PARA IDENTIFICAR PACIENTE
        "nombre" => trim($_POST['nombre']),          // ✂️ NOMBRE LIMPIO
        "apellido" => trim($_POST['apellido']),      // ✂️ APELLIDO LIMPIO
        "documento" => trim($_POST['documento']),    // ✂️ DOCUMENTO LIMPIO
        "edad" => (int)$_POST['edad'],               // 🔢 EDAD CONVERTIDA A ENTERO
        "sexo" => $_POST['sexo'],                    // ⚥ SEXO (sin validación adicional)
        "telefono" => trim($_POST['telefono']),      // ✂️ TELÉFONO LIMPIO (opcional)
        "direccion" => trim($_POST['direccion']),    // ✂️ DIRECCIÓN LIMPIA (opcional)
        "fecha_registro" => $_POST['fecha_registro'] // 📅 FECHA ORIGINAL (no se modifica)
    ];

    // 🔥 CORRECCIÓN IMPORTANTE: Enviar los datos directos al servicio SOAP
    // 📞 LLAMADA AL SERVICIO SOAP - Actualizar paciente existente
    $ok = $client->updatePatient($data);

    // ✅ ACTUALIZACIÓN EXITOSA - Redirigir con mensaje de confirmación
    if ($ok) {
        header("Location: list.php?success=Paciente actualizado correctamente");
        exit;
    }

    // ❌ ACTUALIZACIÓN FALLIDA - Volver al formulario con error
    // 🔗 PRESERVAR ID - Para mantener contexto en la redirección
    header("Location: edit.php?id=" . $data['id'] . "&error=No se pudo actualizar");

} catch (Throwable $e) {
    // 🌐 ERROR DE CONEXIÓN SOAP - Capturar y mostrar error específico
    // 🔗 PRESERVAR ID - Para volver al formulario correcto
    header("Location: edit.php?id=" . $_POST['id'] . "&error=" . urlencode($e->getMessage()));
}
// 🚫 TERMINAR EJECUCIÓN
exit;
?>