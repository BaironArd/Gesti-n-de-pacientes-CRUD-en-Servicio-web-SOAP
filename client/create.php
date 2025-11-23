<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Registrar Paciente</title>  <!-- 🏷️ TÍTULO ESPECÍFICO DE LA PÁGINA -->
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <!-- 🎨 ESTILOS - Ruta relativa desde la carpeta actual -->
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
  <div class="container">
    <!-- 🎯 CABECERA ESPECÍFICA - Contexto de creación de paciente -->
    <div class="header">
      <div class="brand">
        <div class="logo">GP</div>
        <div>
          <div class="title">Registrar Paciente</div>  <!-- ✏️ TÍTULO DE ACCIÓN -->
          <div class="subtitle">Completa los datos y guarda.</div>  <!-- 💡 INSTRUCCIÓN SIMPLE -->
        </div>
      </div>
      <div class="actions">
        <!-- 🧭 NAVEGACIÓN SECUNDARIA - Opciones para salir del formulario -->
        <a class="btn btn-ghost" href="index.php">Volver al Inicio</a>
        <a class="btn btn-ghost" href="list.php">Ver Pacientes</a>
      </div>
    </div>

    <!-- 🚨 MANEJO DE ERRORES - Muestra mensajes de error desde store.php -->
    <?php if (!empty($_GET['error'])): ?>
        <div class="centered-note" style="color:red; background: #ffe6e6; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ffcccc;">
            <strong>Error:</strong> <?= htmlspecialchars(urldecode($_GET['error'])) ?>  <!-- 🔒 SANITIZACIÓN HTML -->
        </div>
    <?php endif; ?>

    <!-- 📝 FORMULARIO PRINCIPAL - Captura de datos del paciente -->
    <div class="form">
      <!-- 📤 ACTION: store.php - Procesa el envío del formulario -->
      <form action="store.php" method="post">
        
        <!-- 📑 FILA 1: Datos personales básicos -->
        <div class="row">
          <div class="field">
            <label>Nombre *</label>  <!-- * INDICA CAMPO OBLIGATORIO -->
            <!-- 🔤 INPUT TEXTO - Requerido, máximo 50 caracteres, preserva valor en error -->
            <input type="text" name="nombre" required maxlength="50" value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
          </div>
          <div class="field">
            <label>Apellido *</label>
            <input type="text" name="apellido" required maxlength="50" value="<?= htmlspecialchars($_POST['apellido'] ?? '') ?>">
          </div>
        </div>

        <!-- 📑 FILA 2: Documento e información demográfica -->
        <div class="row">
          <div class="field">
            <label>Documento *</label>  <!-- 🆔 IDENTIFICACIÓN ÚNICA -->
            <input type="text" name="documento" required maxlength="20" value="<?= htmlspecialchars($_POST['documento'] ?? '') ?>">
          </div>
          <div class="field">
            <label>Edad *</label>  <!-- 🎂 VALIDACIÓN: 0-150 AÑOS -->
            <input type="number" name="edad" required min="0" max="150" value="<?= htmlspecialchars($_POST['edad'] ?? '') ?>">
          </div>
        </div>

        <!-- 📑 FILA 3: Sexo y contacto telefónico -->
        <div class="row">
          <div class="field">
            <label>Sexo *</label>
            <!-- ⚥ SELECTOR DE SEXO - Opciones predefinidas M/F/O -->
            <select name="sexo" required>
              <option value="">Seleccionar</option>  <!-- 🚫 OPCIÓN VACÍA POR DEFECTO -->
              <!-- 🔄 PRESERVA SELECCIÓN - Mantiene valor seleccionado en caso de error -->
              <option value="M" <?= (($_POST['sexo'] ?? '') === 'M') ? 'selected' : '' ?>>Masculino</option>
              <option value="F" <?= (($_POST['sexo'] ?? '') === 'F') ? 'selected' : '' ?>>Femenino</option>
              <option value="O" <?= (($_POST['sexo'] ?? '') === 'O') ? 'selected' : '' ?>>Otro</option>
            </select>
          </div>
          <div class="field">
            <label>Teléfono</label>  <!-- 📞 CAMPO OPCIONAL -->
            <input type="text" name="telefono" maxlength="15" value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>">
          </div>
        </div>

        <!-- 📑 CAMPO INDIVIDUAL: Dirección -->
        <div class="field">
          <label>Dirección</label>  <!-- 🏠 CAMPO OPCIONAL -->
          <input type="text" name="direccion" maxlength="100" value="<?= htmlspecialchars($_POST['direccion'] ?? '') ?>">
        </div>

        <!-- 🎯 BOTONES DE ACCIÓN - Confirmar o cancelar la operación -->
        <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:8px">
          <a class="btn btn-ghost" href="list.php">Cancelar</a>  <!-- ❌ CANCELAR - Regresa al listado -->
          <button class="btn btn-primary" type="submit">Guardar Paciente</button>  <!-- ✅ CONFIRMAR - Envía formulario -->
        </div>
      </form>
    </div>

  </div>
</body>
</html>