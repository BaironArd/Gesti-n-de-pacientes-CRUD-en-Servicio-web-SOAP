<?php
declare(strict_types=1);

/**
 * 🏥 CLIENTE SOAP PARA SERVICIO DE PACIENTES
 * Cliente especializado que encapsula las llamadas SOAP al servidor
 * Proporciona una interfaz más simple y tipada para la aplicación web
 */
class PacientesSoapClient {

    private SoapClient $client;

    /**
     * 🏗️ CONSTRUCTOR - Inicializa el cliente SOAP
     * Configura opciones importantes para evitar problemas de caché
     * @param string $wsdlUrl URL del archivo WSDL del servicio
     */
    public function __construct(string $wsdlUrl) {

        // 🔥 CONFIGURACIÓN CRÍTICA - Evitar caché de WSDL durante desarrollo
        $this->client = new SoapClient($wsdlUrl, [
            'trace' => 1,                    // 📝 HABILITAR TRAZA - Para depuración de mensajes
            'exceptions' => true,            // 🚨 LANZAR EXCEPCIONES - Mejor manejo de errores
            'cache_wsdl' => WSDL_CACHE_NONE  // 🔄 NO CACHEAR WSDL - Ver cambios inmediatamente
        ]);
    }

    /**
     * 📦 MÉTODO PRIVADO: DESEMPAQUETAR RESPUESTAS SOAP
     * Convierte objetos SOAP complejos en arrays PHP simples
     * @param mixed $r Respuesta SOAP del servidor
     * @return mixed Array o valor simple extraído de la respuesta
     */
    private function unwrap($r) {
        // 🔄 CONVERSIÓN OBJETO→ARRAY - Técnica simple usando JSON
        $a = json_decode(json_encode($r), true);
        return $a["return"] ?? $a;  // 🎯 EXTRAER CONTENIDO ÚTIL - Ignorar envoltorio SOAP
    }

    /**
     * 📋 LISTAR TODOS LOS PACIENTES
     * Llama al servicio getPatients y procesa la respuesta
     * @return array Lista de pacientes, array vacío si no hay datos
     */
    public function getPatients(): array {
        // 📞 LLAMADA SOAP DIRECTA - Sin parámetros necesarios
        $res = $this->client->getPatients();
        
        // 🎁 EXTRAER Y FORMATEAR - Obtener array de pacientes o vacío
        return $this->unwrap($res)["paciente"] ?? [];
    }

    /**
     * 🔍 OBTENER PACIENTE POR ID
     * 🔥 CORREGIDO: Envía solo el ID numérico al servidor
     * @param int $id ID del paciente a buscar
     * @return array|null Datos del paciente o null si no existe
     */
    public function getPatient(int $id): ?array {
        // 📤 ENVÍO DIRECTO DE ID - Sin estructura compleja
        $res = $this->client->getPatient($id);
        
        // 📦 DESEMPAQUETAR RESPUESTA - Puede ser array o null
        return $this->unwrap($res);
    }

    /**
     * ➕ CREAR NUEVO PACIENTE
     * Prepara los datos y envía solicitud de creación al servidor
     * @param array $d Datos del paciente a crear
     * @return int ID asignado al nuevo paciente (0 si falla)
     */
    public function createPatient(array $d): int {

        // 🏗️ CONSTRUIR PARÁMETROS SOAP - Estructura esperada por el servidor
        $params = [
            "nombre" => $d["nombre"],
            "apellido" => $d["apellido"],
            "documento" => $d["documento"],
            "edad" => $d["edad"],
            "sexo" => $d["sexo"],
            "telefono" => $d["telefono"],
            "direccion" => $d["direccion"],
            "fecha_registro" => $d["fecha_registro"],
        ];

        // 📞 LLAMADA AL SERVICIO SOAP - Crear paciente
        $res = $this->client->createPatient($params);
        
        // 🔢 CONVERTIR A ENTERO - Asegurar tipo de retorno consistente
        return intval($this->unwrap($res));
    }

    /**
     * ✏️ ACTUALIZAR PACIENTE EXISTENTE
     * Envía todos los datos del paciente para actualización completa
     * @param array $d Datos actualizados del paciente (debe incluir ID)
     * @return bool True si la actualización fue exitosa
     */
    public function updatePatient(array $d): bool {

        // 🏗️ CONSTRUIR PARÁMETROS COMPLETOS - Incluyendo ID para identificación
        $params = [
            "id" => $d["id"],
            "nombre" => $d["nombre"],
            "apellido" => $d["apellido"],
            "documento" => $d["documento"],
            "edad" => $d["edad"],
            "sexo" => $d["sexo"],
            "telefono" => $d["telefono"],
            "direccion" => $d["direccion"],
            "fecha_registro" => $d["fecha_registro"]
        ];

        // 📞 LLAMADA AL SERVICIO SOAP - Actualizar paciente
        $res = $this->client->updatePatient($params);
        
        // 🔄 CONVERTIR A BOOLEANO - Resultado claro de éxito/fracaso
        return boolval($this->unwrap($res));
    }

    /**
     * 🗑️ ELIMINAR PACIENTE POR DOCUMENTO
     * 🔥 CORREGIDO: Usa llamada SOAP directa con solo el documento
     * @param string $documento Número de documento del paciente a eliminar
     * @return bool True si la eliminación fue exitosa
     */
    public function deletePatient(string $documento): bool {

        // 📞 LLAMADA SOAP ESPECIAL - Usando __soapCall para envío directo
        // 🎯 ENVÍO DIRECTO DEL STRING - Sin estructura de array
        $res = $this->client->__soapCall("deletePatient", [$documento]);

        // 🔄 CONVERTIR A BOOLEANO - Resultado claro de éxito/fracaso
        return boolval($this->unwrap($res));
    }
}