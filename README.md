AUTOR DEL PROYECTO (BAIRON SEBASTIAN ARDILA MENDOZA)
"INDIVIDUAL"
✅ Objetivo General

Desarrollar un servicio web SOAP que permita gestionar pacientes (CRUD) con persistencia en pacientes.xml, interfaz web y cliente SOAP.

🧠 Plan de Trabajo Completo
1️⃣ Preparación del Proyecto
| Tarea                      | Descripción                                            |
| -------------------------- | ------------------------------------------------------ |
| Crear carpeta del proyecto | `/GINPAC-SOAP/`                                        |
| Crear subcarpetas          | `/backend` `/frontend` `/wsdl` `/data`                 |
| Crear archivo XML          | `data/pacientes.xml` inicial vacío con estructura base |
| Definir tecnología         | PHP con SoapServer y SoapClient (recomendado)          |

Inicial XML:

<pacientes></pacientes>

2️⃣ Definir el WSDL (Contrato del servicio)

Ubicación: /wsdl/pacientes.wsdl

El WSDL debe definir:
✅ Tipos (datos del paciente)
✅ Estructura de mensajes
✅ Operaciones SOAP
✅ Endpoint del servicio

Operaciones:

| Operación            | Acción            |
| -------------------- | ----------------- |
| `crearPaciente`      | Crear paciente    |
| `buscarPaciente`     | Buscar por cédula |
| `listarPacientes`    | Listar todos      |
| `actualizarPaciente` | Modificar         |
| `eliminarPaciente`   | Borrar            |

3️⃣ Backend – Servidor SOAP

Ubicación: /backend/server.php

Responsabilidades:

| Módulo              | Función                         |
| ------------------- | ------------------------------- |
| Cargar WSDL         | `SoapServer('pacientes.wsdl')`  |
| Manejar operaciones | 5 funciones CRUD                |
| Persistir en XML    | Leer y escribir `pacientes.xml` |


Funciones a implementar:

| Función                | Archivo    | Acción                       |
| ---------------------- | ---------- | ---------------------------- |
| `crearPaciente()`      | server.php | Inserta nodo en XML          |
| `buscarPaciente()`     | server.php | Devuelve paciente por cédula |
| `listarPacientes()`    | server.php | Devuelve array de pacientes  |
| `actualizarPaciente()` | server.php | Modifica nodo existente      |
| `eliminarPaciente()`   | server.php | Borra nodo XML               |


4️⃣ Frontend – Cliente SOAP

Ubicación: /frontend

Archivos necesarios:

| Archivo                | Función                                  |
| ---------------------- | ---------------------------------------- |
| `index.php`            | Menú principal (Inicio)                  |
| `crear_paciente.php`   | Formulario CREAR                         |
| `listar_pacientes.php` | Tabla LISTAR + botones Editar / Eliminar |
| `editar_paciente.php`  | Formulario EDITAR auto relleno           |
| `cliente.php`          | Clase SOAPClient para consumir métodos   |


Cada archivo debe:

✅ Conectarse al WSDL
✅ Llamar operación SOAP correspondiente
✅ Mostrar resultados con mensajes claros
✅ Tener botón Volver al Inicio

5️⃣ Flujo de la Interfaz
| Vista              | Acción                 | Método SOAP                             |
| ------------------ | ---------------------- | --------------------------------------- |
| Inicio             | Menú navegación        | —                                       |
| Registrar paciente | Formulario -> Submit   | `crearPaciente`                         |
| Ver pacientes      | Tabla dinámica         | `listarPacientes`                       |
| Editar             | Form cargado -> Submit | `buscarPaciente` + `actualizarPaciente` |
| Eliminar           | Botón + confirmación   | `eliminarPaciente`                      |


6️⃣ Diseño (UI/UX)

📌 Requerido:

HTML + CSS limpio (no solo HTML puro)

Botones, tablas, formularios ordenados

Botón Volver al Inicio en todas las vistas

Alert JS en eliminación

Recomendación: Bootstrap o Tailwind para mejor presentación.

7️⃣ Pruebas
| Prueba          | Validación                |
| --------------- | ------------------------- |
| Crear paciente  | Aparece en XML            |
| Buscar paciente | Datos correctos           |
| Listar          | Muestra todos             |
| Editar          | Cambios reflejados en XML |
| Eliminar        | Nodo desaparece del XML   |

8️⃣ Documentación

Incluye:
✅ Explicación SOAP
✅ Flujo Cliente → WSDL → Servidor → XML
✅ Screenshots
✅ Diagrama básico (opcional)
✅ Comentarios en código

9️⃣ Preparación de la Sustentación

Debes explicar:

| Tema             | Qué decir                            |
| ---------------- | ------------------------------------ |
| ¿Qué es SOAP?    | Protocolo basado en XML/WSDL         |
| Por qué no REST  | Se pidió SOAP académico              |
| Flujo            | Cliente → SOAP → WSDL → Server → XML |
| CRUD funcionando | Demostración en vivo                 |
| Quién hizo qué   | Trabajo colaborativo                 |


Tu parte: diagramas + flujo + conclusiones ✅

📂 Mapa Final del Proyecto
GINPAC-SOAP/
 ├── data/
 │    └── pacientes.xml
 ├── wsdl/
 │    └── pacientes.wsdl
 ├── backend/
 │    └── server.php
 ├── frontend/
 │    ├── index.php
 │    ├── cliente.php
 │    ├── crear_paciente.php
 │    ├── listar_pacientes.php
 │    └── editar_paciente.php
 └── README.pdf o documentación 
<img width="189" height="262" alt="image" src="https://github.com/user-attachments/assets/75015be2-c365-4908-9bb2-05c24d0a246b" />
