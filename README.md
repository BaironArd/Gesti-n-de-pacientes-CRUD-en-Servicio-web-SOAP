# 🏥 Sistema de Gestión de Pacientes - Servicio Web SOAP

## 👥 Autores
- **Bairon Sebastian Ardila Mendoza**
- **Julian Andres Parada Cuadros**

## 🎯 Objetivo General
Desarrollar un servicio web SOAP que permita gestionar pacientes (CRUD) con persistencia en XML, interfaz web y cliente SOAP.

## 📁 Estructura Actual del Proyecto
<img width="557" height="350" alt="image" src="https://github.com/user-attachments/assets/99c4953c-aac1-42d6-8bb2-fe11b2f5f197" />


text

## 🛠️ Tecnologías Implementadas
- **PHP** con SoapServer y SoapClient
- **SOAP** con WSDL personalizado
- **XML** para persistencia de datos
- **HTML5 + CSS3** para interfaz web

## 🔧 Funcionalidades Implementadas

### ✅ Operaciones CRUD Completas
| Operación | Método SOAP | Archivo |
|-----------|-------------|---------|
| **Crear** | `createPatient()` | store.php |
| **Listar** | `getPatients()` | list.php |
| **Buscar** | `getPatient()` | edit.php |
| **Actualizar** | `updatePatient()` | update.php |
| **Eliminar** | `deletePatient()` | delete.php |

## 🚀 Características del Sistema

### 🔄 Servicio SOAP
- WSDL con operaciones CRUD completas
- Cliente SOAP reutilizable en `soap_client.php`
- Manejo de errores y validaciones
- Persistencia en archivo XML

### 🎨 Interfaz Web
- Diseño responsive con CSS personalizado
- Formularios para crear y editar pacientes
- Listado en tabla con acciones
- Confirmación antes de eliminar

## 📊 Estructura de Datos
Los pacientes se almacenan en `pacientes.xml` con:
- ID automático
- Nombre, apellido, documento
- Edad, sexo, teléfono, dirección
- Fecha de registro automática

## 🖥️ Instalación
1. Clonar el repositorio
2. Configurar servidor web (Apache/Laragon)
3. Acceder a `frontend/index.php`

## 🔗 Endpoints
- **Servicio SOAP:** `http://localhost/pacientes_soap/backend/server.php`
- **WSDL:** `http://localhost/pacientes_soap/backend/pacientes.wsdl`

---

## 📝 Nota de Desarrollo
Proyecto colaborativo desarrollado por **Bairon** y **Julian** para la implementación de servicios web SOAP con PHP.
