<?php
// 🏠 PÁGINA PRINCIPAL - Vista de inicio del sistema de gestión de pacientes
// Punto de entrada principal con navegación a todas las funcionalidades
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Gestor de Pacientes - Inicio</title>  <!-- 🏷️ TÍTULO DE PÁGINA -->
  <meta name="viewport" content="width=device-width,initial-scale=1">  <!-- 📱 DISEÑO RESPONSIVE -->
  <!-- 🎨 HOJA DE ESTILOS - Carga dinámica desde la raíz del servidor -->
  <link rel="stylesheet" href="<?php echo $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']; ?>/assets/style.css">
</head>
<body>
  <!-- 🏗️ CONTENEDOR PRINCIPAL - Centrado con sombra y bordes redondeados -->
  <div class="container" role="main">
    
    <!-- 🎯 CABECERA - Logo, título y menú de navegación -->
    <div class="header">
      <div class="brand">
        <div class="logo">GP</div>  <!-- 🔷 LOGO - Iniciales "Gestor de Pacientes" -->
        <div>
          <div class="title">Gestor Interno de Pacientes</div>  <!-- 🏥 TÍTULO PRINCIPAL -->
          <div class="subtitle">Interfaz SOAP • PHP 8.1 • Laragon</div>  <!-- 🔧 STACK TECNOLÓGICO -->
        </div>
      </div>
      <div class="actions">
        <!-- 🧭 MENÚ DE NAVEGACIÓN - Botones de acceso rápido -->
        <a class="btn btn-ghost" href="index.php">Inicio</a>  <!-- 🏠 INICIO - Página actual -->
        <a class="btn btn-ghost" href="list.php">Ver Pacientes</a>  <!-- 📋 LISTA - Ver todos los registros -->
        <a class="btn btn-primary" href="create.php">Registrar Paciente</a>  <!-- ➕ ACCIÓN PRINCIPAL - Crear nuevo -->
      </div>
    </div>

    <!-- 🎪 CONTENIDO CENTRAL - Tarjetas de acción principales -->
    <div class="center">
      <div class="grid" style="width:100%">
        <!-- 🎯 TARJETA: REGISTRAR PACIENTE - Acción principal del sistema -->
        <a class="card-action" href="create.php">
          <h3>Registrar Paciente</h3>  <!-- 📝 TÍTULO DE ACCIÓN -->
          <p>Agregar un nuevo paciente al sistema.</p>  <!-- ℹ️ DESCRIPCIÓN -->
        </a>

        <!-- 🎯 TARJETA: VER PACIENTES - Navegación a listado completo -->
        <a class="card-action" href="list.php">
          <h3>Ver Pacientes</h3>
          <p>Listado completo — editar o eliminar registros.</p>  <!-- ✏️🔄 ACCIONES DISPONIBLES -->
        </a>
      </div>
      
      <!-- 💡 INSTRUCCIÓN DE USO - Guía simple para el usuario -->
      <p class="centered-note">Usa los botones para navegar.</p>
    </div>
    
    <!-- 👣 PIE DE PÁGINA - Información técnica y créditos -->
    <div class="footer">
      <div>Hecho con • PHP 8.1 + SOAP</div>  <!-- 🔧 TECNOLOGÍAS UTILIZADAS -->
      <div><small>Servicio SOAP: backend/pacientes.wsdl</small></div>  <!-- 🌐 ENDPOINT DEL SERVICIO -->
    </div>
  </div>
</body>
</html>