<?php
// 🗑️ ELIMINACIÓN DE PACIENTES - Script que procesa la eliminación por documento
// 📦 INCLUIR CLIENTE SOAP - Para comunicación con el backend
require_once __DIR__ . '/../backend/soap_client.php';

// 🚫 VALIDAR PARÁMETRO OBLIGATORIO - Documento debe estar presente
if (!isset($_GET['documento'])) {
    header("Location: list.php?error=Documento inválido");  // ❌ PARÁMETRO FALTANTE
    exit;
}

try {
    // 🌐 CONFIGURAR CLIENTE SOAP - Conectar al servicio
    $wsdl = "http://localhost/pacientes_soap/backend/pacientes.wsdl";
    $client = new PacientesSoapClient($wsdl);

    // 🔥 CORRECCIÓN IMPORTANTE: Usar documento en lugar de ID
    $documento = (string)$_GET['documento'];
    
    // 📞 LLAMADA AL SERVICIO SOAP - Eliminar paciente por documento
    $ok = $client->deletePatient($documento);

    // ✅ ELIMINACIÓN EXITOSA - Redirigir con mensaje de éxito
    if ($ok) {
        header("Location: list.php?success=Paciente eliminado correctamente");
        exit;
    }

    // ❌ ELIMINACIÓN FALLIDA - Redirigir con mensaje de error genérico
    header("Location: list.php?error=No se pudo eliminar el paciente");
    exit;

} catch (Throwable $e) {
    // 🌐 ERROR DE CONEXIÓN SOAP - Capturar y mostrar error específico
    header("Location: list.php?error=" . urlencode($e->getMessage()));
    exit;
}