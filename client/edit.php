<?php
// ✏️ FORMULARIO DE EDICIÓN - Precarga datos del paciente desde servicio SOAP
// 🛡️ MODO ESTRICTO - Para mejor control de tipos de datos
declare(strict_types=1);

// 📦 INCLUIR CLIENTE SOAP - Para comunicación con el backend
require_once __DIR__ . '/../backend/soap_client.php';

// 🌐 CONFIGURAR CLIENTE SOAP - Conectar al servicio WSDL
$wsdl = 'http://localhost/pacientes_soap/backend/pacientes.wsdl';
$client = new PacientesSoapClient($wsdl);

// 🆔 ID RECIBIDO POR GET - Parámetro de URL para identificar el paciente
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    // ❌ ID INVÁLIDO - Redirigir con mensaje de error
    header("Location: list.php?error=invalid_id");
    exit;
}

$patient = null;
$error = null;

try {
    // 📞 LLAMADA AL SERVICIO SOAP - Obtener datos del paciente específico
    $res = $client->getPatient($id);

    // 🔄 NORMALIZAR RESPUESTA - Puede venir como stdClass o array
    if (is_object($res) || is_array($res)) {
        $arr = json_decode(json_encode($res), true);
        if (isset($arr['return'])) {
            $patient = (array)$arr['return'];  // 🎯 EXTRAER DATOS DEL ENVOLTORIO SOAP
        } else {
            $patient = $arr;
        }
    }

} catch (Throwable $e) {
    // ❌ ERROR DE CONEXIÓN - Capturar excepciones del servicio SOAP
    $error = $e->getMessage();
}

// 🚫 VERIFICAR QUE EL PACIENTE EXISTA - Validar que se obtuvieron datos
if (!$patient || empty($patient['id'])) {
    header("Location: list.php?error=notfound");  // 🔍 PACIENTE NO ENCONTRADO
    exit;
}

/**
 * 🛡️ FUNCIÓN DE SEGURIDAD - Escapar output para prevenir XSS
 * @param mixed $v Valor a escapar
 * @return string Valor escapado seguro para HTML
 */
function esc($v) {
    return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Editar Paciente #<?= esc($patient['id']) ?></title>  <!-- 🏷️ TÍTULO DINÁMICO CON ID -->
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="../assets/style.css">  <!-- 🎨 ESTILOS COMPARTIDOS -->
</head>
<body>
  <div class="container">

    <!-- 🎯 CABECERA ESPECÍFICA - Contexto de edición -->
    <div class="header">
      <div class="brand">
        <div class="logo">GP</div>
        <div>
          <div class="title">Editar Paciente #<?= esc($patient['id']) ?></div>  <!-- ✏️ TÍTULO CON ID -->
          <div class="subtitle">Precarga de datos para edición.</div>  <!-- 💡 INDICA FUNCIONALIDAD -->
        </div>
      </div>
      <div class="actions">
        <!-- 🧭 NAVEGACIÓN - Opciones para salir del formulario -->
        <a class="btn btn-ghost" href="index.php">Volver al Inicio</a>
        <a class="btn btn-ghost" href="list.php">Ver Pacientes</a>
      </div>
    </div>

    <!-- 📝 FORMULARIO DE EDICIÓN - Similar a create pero con datos precargados -->
    <div class="form">
      <!-- 📤 ACTION: update.php - Procesa la actualización -->
      <form action="update.php" method="post">

        <!-- 🔒 CAMPO OCULTO - Preservar ID durante la actualización -->
        <input type="hidden" name="id" value="<?= esc($patient['id']) ?>">

        <!-- 📑 FILA 1: Datos personales básicos -->
        <div class="row">
          <div class="field">
            <label>Nombre</label>
            <!-- 🔤 INPUT CON VALOR PRECARGADO - Datos actuales del paciente -->
            <input type="text" name="nombre" required value="<?= esc($patient['nombre'] ?? '') ?>">
          </div>
          <div class="field">
            <label>Apellido</label>
            <input type="text" name="apellido" required value="<?= esc($patient['apellido'] ?? '') ?>">
          </div>
        </div>

        <!-- 📑 FILA 2: Documento e información demográfica -->
        <div class="row">
          <div class="field">
            <label>Documento</label>
            <input type="text" name="documento" required value="<?= esc($patient['documento'] ?? '') ?>">
          </div>
          <div class="field">
            <label>Edad</label>
            <!-- 🔢 INPUT NUMÉRICO - Con valor mínimo pero sin máximo -->
            <input type="number" name="edad" required min="0" value="<?= esc($patient['edad'] ?? '') ?>">
          </div>
        </div>

        <!-- 📑 FILA 3: Sexo y contacto telefónico -->
        <div class="row">
          <div class="field">
            <label>Sexo</label>
            <!-- ⚥ SELECTOR CON OPCIÓN ACTUAL SELECCIONADA -->
            <select name="sexo" required>
              <option value="M" <?= (($patient['sexo'] ?? '') === 'M') ? 'selected' : '' ?>>M</option>
              <option value="F" <?= (($patient['sexo'] ?? '') === 'F') ? 'selected' : '' ?>>F</option>
              <option value="O" <?= (($patient['sexo'] ?? '') === 'O') ? 'selected' : '' ?>>Otro</option>
            </select>
          </div>
          <div class="field">
            <label>Teléfono</label>
            <input type="text" name="telefono" value="<?= esc($patient['telefono'] ?? '') ?>">
          </div>
        </div>

        <!-- 📑 CAMPO INDIVIDUAL: Dirección -->
        <div class="field">
          <label>Dirección</label>
          <input type="text" name="direccion" value="<?= esc($patient['direccion'] ?? '') ?>">
        </div>

        <!-- 📅 CAMPO INDIVIDUAL: Fecha de registro -->
        <div class="field">
          <label>Fecha Registro</label>
          <!-- 📆 INPUT DATE - Para selección de fecha nativa -->
          <input type="date" name="fecha_registro" value="<?= esc($patient['fecha_registro'] ?? '') ?>">
        </div>

        <!-- 🎯 BOTONES DE ACCIÓN - Confirmar o cancelar la operación -->
        <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:8px">
          <a class="btn btn-ghost" href="list.php">Cancelar</a>  <!-- ❌ CANCELAR -->
          <button class="btn btn-primary" type="submit">Actualizar Paciente</button>  <!-- ✅ CONFIRMAR ACTUALIZACIÓN -->
        </div>

      </form>
    </div>

  </div>
</body>
</html>