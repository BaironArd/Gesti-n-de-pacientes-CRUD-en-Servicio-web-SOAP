<?php
// ⚙️ CONFIGURACIÓN INICIAL - Mostrar todos los errores para depuración
ini_set("display_errors", 1);
error_reporting(E_ALL);

// 📁 RUTA DEL ARCHIVO XML - Base de datos de pacientes
$xmlFile = __DIR__ . "/pacientes.xml";

/**
 * 🏥 CLASE PRINCIPAL DEL SERVICIO SOAP
 * Gestiona todas las operaciones CRUD sobre los pacientes
 * usando XML como almacenamiento persistente
 */
class PacientesService
{
    private string $xmlFile;

    /**
     * 🏗️ CONSTRUCTOR - Inicializa el servicio
     * Crea el archivo XML si no existe
     */
    public function __construct($xmlFile)
    {
        $this->xmlFile = $xmlFile;

        // 📝 CREAR ARCHIVO XML SI NO EXISTE - Inicializa con estructura básica
        if (!file_exists($xmlFile)) {
            file_put_contents($xmlFile,
                "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<pacientes></pacientes>");
        }
    }

    /**
     * 📖 CARGAR ARCHIVO XML
     * Lee y parsea el XML, lo recrea si está corrupto
     * @return SimpleXMLElement Objeto XML listo para usar
     */
    private function loadXML()
    {
        $xml = simplexml_load_file($this->xmlFile);
        if ($xml === false) {
            // 🔄 RECREAR XML SI ESTÁ DAÑADO - Previene errores de parseo
            file_put_contents($this->xmlFile,
                "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<pacientes></pacientes>");
            $xml = simplexml_load_file($this->xmlFile);
        }
        return $xml;
    }

    /**
     * 💾 GUARDAR ARCHIVO XML
     * Formatea el XML para mejor legibilidad y lo guarda
     * @param SimpleXMLElement $xml Objeto XML a guardar
     * @return bool True si se guardó correctamente
     */
    private function saveXML($xml)
    {
        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;  // 🎨 FORMATEAR XML - Indentación para legibilidad
        $dom->loadXML($xml->asXML());
        return $dom->save($this->xmlFile);
    }

    /**
     * 📋 LISTAR TODOS LOS PACIENTES
     * Servicio SOAP: Recupera todos los registros de pacientes
     * @return array Lista de pacientes en formato estructurado
     */
    public function getPatients()
    {
        $xml = $this->loadXML();
        $list = [];

        // 🔄 ITERAR SOBRE CADA PACIENTE - Convertir XML a array PHP
        foreach ($xml->paciente as $p) {
            $list[] = [
                "id"             => (int)$p->id,
                "nombre"         => (string)$p->nombre,
                "apellido"       => (string)$p->apellido,
                "documento"      => (string)$p->documento,
                "edad"           => (int)$p->edad,
                "sexo"           => (string)$p->sexo,
                "telefono"       => (string)$p->telefono,
                "direccion"      => (string)$p->direccion,
                "fecha_registro" => (string)$p->fecha_registro
            ];
        }

        return ["paciente" => $list];  // 📦 ESTRUCTURA ESPERADA POR SOAP
    }

    /**
     * 🔍 OBTENER PACIENTE POR ID
     * Servicio SOAP: Busca un paciente específico por su ID
     * @param mixed $params Puede ser ID numérico o array/objeto con ID
     * @return array|null Datos del paciente o null si no existe
     */
    public function getPatient($params)
    {
        // 🎯 EXTRAER ID DESDE DIFERENTES FORMATOS - Flexibilidad en parámetros
        if (is_array($params) && isset($params['id'])) {
            $id = (int)$params['id'];
        } elseif (is_object($params) && isset($params->id)) {
            $id = (int)$params->id;
        } elseif (is_numeric($params)) {
            $id = (int)$params;
        } else {
            return null;  // ❌ ID NO VÁLIDO
        }

        $xml = $this->loadXML();

        // 🔎 BÚSQUEDA LINEAL EN XML - Recorre todos los pacientes
        foreach ($xml->paciente as $p) {
            if ((int)$p->id === $id) {
                return [
                    "id"             => (int)$p->id,
                    "nombre"         => (string)$p->nombre,
                    "apellido"       => (string)$p->apellido,
                    "documento"      => (string)$p->documento,
                    "edad"           => (int)$p->edad,
                    "sexo"           => (string)$p->sexo,
                    "telefono"       => (string)$p->telefono,
                    "direccion"      => (string)$p->direccion,
                    "fecha_registro" => (string)$p->fecha_registro
                ];
            }
        }

        return null;  // ❌ PACIENTE NO ENCONTRADO
    }

    /**
     * ➕ CREAR NUEVO PACIENTE
     * Servicio SOAP: Agrega un nuevo registro de paciente
     * @param mixed $params Datos del paciente a crear
     * @return int ID asignado al nuevo paciente, 0 si falla
     */
    public function createPatient($params)
    {
        try {
            $data = is_object($params) ? $params : (object)$params;

            $xml = $this->loadXML();

            // 🔢 GENERAR ID AUTOMÁTICAMENTE - Busca el máximo ID existente + 1
            $newId = 1;
            if (count($xml->paciente) > 0) {
                $lastId = 0;
                foreach ($xml->paciente as $p) {
                    $idActual = (int)$p->id;
                    if ($idActual > $lastId) {
                        $lastId = $idActual;
                    }
                }
                $newId = $lastId + 1;  // 📈 INCREMENTAR ID
            }

            // 🏷️ CREAR NUEVO NODO PACIENTE - Construye estructura XML
            $paciente = $xml->addChild('paciente');
            $paciente->addChild('id', $newId);
            $paciente->addChild('nombre', (string)$data->nombre);
            $paciente->addChild('apellido', (string)$data->apellido);
            $paciente->addChild('documento', (string)$data->documento);
            $paciente->addChild('edad', (int)$data->edad);
            $paciente->addChild('sexo', (string)$data->sexo);
            $paciente->addChild('telefono', (string)($data->telefono ?? ''));     // 📞 CAMPO OPCIONAL
            $paciente->addChild('direccion', (string)($data->direccion ?? ''));   // 🏠 CAMPO OPCIONAL
            $paciente->addChild('fecha_registro', (string)$data->fecha_registro);

            return $this->saveXML($xml) ? $newId : 0;  // 💾 RETORNAR ID O 0 SI FALLA

        } catch (Exception $e) {
            return 0;  // ❌ CAPTURA DE ERRORES - Fallo silencioso
        }
    }

    /**
     * ✏️ ACTUALIZAR PACIENTE EXISTENTE
     * Servicio SOAP: Modifica los datos de un paciente
     * @param mixed $params Datos actualizados del paciente (debe incluir ID)
     * @return bool True si se actualizó correctamente
     */
    public function updatePatient($params)
    {
        try {
            $data = is_object($params) ? $params : (object)$params;

            $xml = $this->loadXML();
            $found = false;

            // 🔄 BUSCAR Y ACTUALIZAR - Localiza por ID y modifica campos
            foreach ($xml->paciente as $p) {
                if ((int)$p->id === (int)$data->id) {
                    // 📝 ACTUALIZAR TODOS LOS CAMPOS - Sobrescribe valores existentes
                    $p->nombre = (string)$data->nombre;
                    $p->apellido = (string)$data->apellido;
                    $p->documento = (string)$data->documento;
                    $p->edad = (int)$data->edad;
                    $p->sexo = (string)$data->sexo;
                    $p->telefono = (string)($data->telefono ?? '');
                    $p->direccion = (string)($data->direccion ?? '');
                    $p->fecha_registro = (string)$data->fecha_registro;
                    $found = true;
                    break;  // ⏹️ DETENER BÚSQUEDA - Solo un paciente por ID
                }
            }

            return $found ? $this->saveXML($xml) : false;  // 💾 GUARDAR SI ENCONTRÓ

        } catch (Exception $e) {
            return false;  // ❌ ERROR EN ACTUALIZACIÓN
        }
    }

    /**
     * 🗑️ ELIMINAR PACIENTE POR DOCUMENTO
     * Servicio SOAP: Elimina un paciente usando su número de documento
     * @param mixed $params Documento del paciente a eliminar
     * @return bool True si se eliminó correctamente
     */
    public function deletePatient($params)
    {
        try {
            // 📝 REGISTRO DE DEPURACIÓN - Log para troubleshooting
            error_log("deletePatient() params: " . print_r($params, true));

            // 🎯 EXTRAER DOCUMENTO DESDE DIFERENTES FORMATOS
            if (is_array($params) && isset($params['documento'])) {
                $documento = (string)$params['documento'];
            } elseif (is_object($params) && isset($params->documento)) {
                $documento = (string)$params->documento;
            } else {
                $documento = (string)$params;
            }
            error_log("Documento FINAL extraído: " . $documento);

            error_log("Documento recibido: $documento");

            // ❌ VALIDAR DOCUMENTO NO VACÍO
            if (trim($documento) === "") {
                error_log("Documento vacío");
                return false;
            }

            $xml = $this->loadXML();
            $found = false;

            // 🔍 BUSCAR POR DOCUMENTO - Comparación exacta de strings
            foreach ($xml->paciente as $p) {
                $docActual = (string)$p->documento;
                error_log("Comparando: '$docActual' == '$documento'");

                if ($docActual === $documento) {
                    // 🗑️ ELIMINAR NODO XML - Conversión a DOM para remover
                    $dom = dom_import_simplexml($p);
                    $dom->parentNode->removeChild($dom);

                    error_log("Eliminado OK");
                    $found = true;
                    break;
                }
            }

            if ($found) {
                $save = $this->saveXML($xml);
                error_log("Guardado XML: " . ($save ? "true" : "false"));
                return $save;  // 💾 CONFIRMAR GUARDADO
            }

            error_log("No se encontró paciente con documento $documento");
            return false;  // ❌ NO ENCONTRADO

        } catch (Exception $e) {
            error_log("Error deletePatient: " . $e->getMessage());
            return false;  // ❌ ERROR EN ELIMINACIÓN
        }
    }
}

// 🌐 INICIALIZACIÓN DEL SERVIDOR SOAP
try {
    // 🚀 CREAR SERVIDOR SOAP - Usa WSDL para definir contrato de servicio
    $server = new SoapServer(__DIR__ . "/pacientes.wsdl", [
        'cache_wsdl' => WSDL_CACHE_NONE  // 🔄 NO CACHEAR WSDL - Para desarrollo
    ]);

    // 🔗 VINCULAR CLASE DE SERVICIO - Expone métodos como operaciones SOAP
    $server->setClass("PacientesService", $xmlFile);
    
    // ▶️ EJECUTAR SERVICIO - Procesa peticiones SOAP entrantes
    $server->handle();

} catch (Exception $e) {
    // ❌ MANEJO DE ERRORES DEL SERVIDOR SOAP
    echo "Error en servidor SOAP: " . $e->getMessage();
}
?>