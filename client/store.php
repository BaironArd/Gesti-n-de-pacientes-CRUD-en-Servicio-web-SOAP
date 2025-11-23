<?php
// 💾 PROCESADOR DE CREACIÓN - Maneja el envío del formulario de creación
// 📦 INCLUIR CLIENTE SOAP - Para comunicación con el backend
require_once __DIR__ . "/../backend/soap_client.php";

// 🛡️ VALIDACIÓN DE CAMPOS OBLIGATORIOS - Verificar datos mínimos requeridos
if (empty($_POST["nombre"]) || empty($_POST["apellido"]) || empty($_POST["documento"])) {
    // ❌ DATOS INCOMPLETOS - Redirigir con mensaje de error específico
    header("Location: create.php?error=Faltan datos");
    exit;
}

// 🔢 VALIDACIÓN DE EDAD - Debe estar en rango válido (0-150 años)
$edad = intval($_POST["edad"]);
if ($edad < 0 || $edad > 150) {
    // ❌ EDAD INVÁLIDA - Redirigir con mensaje descriptivo
    header("Location: create.php?error=Edad inválida (debe ser 0-150)");
    exit;
}

// ⚥ VALIDACIÓN DE SEXO - Solo valores predefinidos permitidos
if (!in_array($_POST["sexo"], ['M', 'F', 'O'])) {
    // ❌ SEXO INVÁLIDO - Prevenir valores no autorizados
    header("Location: create.php?error=Sexo inválido");
    exit;
}

try {
    // 🌐 INICIALIZAR CLIENTE SOAP - Conectar al servicio WSDL
    $client = new PacientesSoapClient("http://localhost/pacientes_soap/backend/pacientes.wsdl");

    // 🏗️ PREPARAR DATOS PARA SOAP - Estructura y limpieza de inputs
    $data = [
        "nombre" => trim($_POST["nombre"]),          // ✂️ ELIMINAR ESPACIOS EN BLANCO
        "apellido" => trim($_POST["apellido"]),      // ✂️ LIMPIAR APELLIDO
        "documento" => trim($_POST["documento"]),    // ✂️ LIMPIAR DOCUMENTO
        "edad" => intval($_POST["edad"]),            // 🔢 CONVERTIR A ENTERO
        "sexo" => $_POST["sexo"],                    // ⚥ VALOR YA VALIDADO
        "telefono" => trim($_POST["telefono"]),      // ✂️ LIMPIAR TELÉFONO (opcional)
        "direccion" => trim($_POST["direccion"]),    // ✂️ LIMPIAR DIRECCIÓN (opcional)
        "fecha_registro" => date("Y-m-d")            // 📅 FECHA ACTUAL EN FORMATO ISO
    ];

    // 📞 LLAMADA AL SERVICIO SOAP - Crear paciente en el backend
    $id = $client->createPatient($data);

    // ✅ CREACIÓN EXITOSA - ID mayor a 0 indica éxito
    if ($id > 0) {
        // 🎉 REDIRIGIR CON MENSAJE DE ÉXITO - Incluir ID asignado
        header("Location: list.php?success=Paciente creado con ID $id");
    } else {
        // ❌ CREACIÓN FALLIDA - ID 0 o negativo indica error
        header("Location: create.php?error=No se pudo crear");
    }

} catch (Throwable $e) {
    // 🌐 ERROR DE CONEXIÓN SOAP - Capturar cualquier excepción
    // 🔒 CODIFICAR MENSAJE PARA URL - Prevenir problemas en redirección
    header("Location: create.php?error=" . urlencode($e->getMessage()));
}
// 🚫 TERMINAR EJECUCIÓN - Prevenir salida adicional
exit;