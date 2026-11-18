<?php

namespace PlantillaAW\Database;

class Database {
    private static $con;
    private static $lastResult = null;

    public function __construct() {
        if (!self::$con) {
            // Intentar cargar la configuración si no están definidas las constantes
            if (!defined('DB_HOST')) {
                $configPath = dirname(__DIR__, 2) . '/config/config.php';
                if (file_exists($configPath)) {
                    require_once $configPath;
                }
            }

            if (!defined('DB_HOST')) {
                die("Error: No se ha definido la configuración de conexión con la base de datos.");
            }

            self::$con = new \mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
            if (self::$con->connect_errno) {
                die("Error al conectar con la base de datos: " . self::$con->connect_error);
            }
        }
    }

    public static function getCon() {
        if (!self::$con) {
            new self();
        }
        return self::$con;
    }

    public static function query($sql) {
        $con = self::getCon();
        $result = $con->query($sql);
        if (!$result) {
            die("Error en la consulta: " . $con->error);
        }
        self::$lastResult = $result;
        return $result;
    }

    public static function getLastResult() {
        return self::$lastResult;
    }

    public static function getNumRows() {
        if (self::$lastResult) {
            return self::$lastResult->num_rows;
        }
        return 0;
    }

    public static function getTables() {
        $con = self::getCon();
        $sql = "SHOW TABLES";
        $result = $con->query($sql);
        if (!$result) {
            die("Error al obtener las tablas: " . $con->error);
        }
        $tables = [];
        while ($row = $result->fetch_assoc()) {
            $tables[] = $row[array_keys($row)[0]];
        }
        return $tables;
    }

    public static function getFields($tabla) {
        $con = self::getCon();
        $sql = "SHOW COLUMNS FROM $tabla";
        $result = $con->query($sql);
        if (!$result) {
            die("Error al obtener los campos: " . $con->error);
        }
        $fields = [];
        while ($row = $result->fetch_assoc()) {
            $fields[] = $row['Field'];
        }
        return $fields;
    }

    /**
     * @param string $tabla Nombre de la tabla
     * @param string $campos Campos a seleccionar, separados por comas
     * @param array $claves Claves para la condición WHERE
     * @param array $valores Valores para la condición WHERE
     * @return array Resultado de la consulta
     */
    public static function select($tabla, $campos = "*", $claves = [], $valores = []) {
        $con = self::getCon();
        $sql = "SELECT $campos FROM $tabla";
        if ($claves && $valores) {
            $condicion = implode(" AND ", array_map(function ($k, $v) {
                return "$k='$v'";
            }, $claves, $valores));
            $sql .= " WHERE $condicion";
        }
        $result = $con->query($sql);
        if (!$result) {
            die("Error en la consulta: " . $con->error);
        }
        $datos = [];
        while ($row = $result->fetch_assoc()) {
            $datos[] = $row;
        }
        return $datos;
    }

    /**
     * @param string $tabla Nombre de la tabla
     * @param array $datos Array asociativo con los datos a insertar
     * @return int ID del registro insertado
     */
    public static function insert($tabla, $datos) {
        $con = self::getCon();
        $camposTabla = self::getFields($tabla);
        $camposValidos = array_intersect($camposTabla, array_keys($datos));
        $campos = implode(",", $camposValidos);

        $valoresValidos = array_map(function ($campo) use ($datos) {
            return $datos[$campo];
        }, $camposValidos);
        $valores = implode("','", $valoresValidos);

        $sql = "INSERT INTO $tabla ($campos) VALUES ('$valores')";
        $result = $con->query($sql);
        if (!$result) {
            die("Error en la inserción: " . $con->error);
        }
        return $con->insert_id;
    }

    /**
     * @param string $tabla Nombre de la tabla
     * @param array $datos Array asociativo con los datos a actualizar
     * @param string $condicion Condición WHERE para la actualización
     * @return bool|\mysqli_result Resultado de la actualización
     */
    public static function update($tabla, $datos, $condicion) {
        $con = self::getCon();
        $set = implode(",", array_map(function ($k, $v) {
            return "$k='$v'";
        }, array_keys($datos), array_values($datos)));
        $sql = "UPDATE $tabla SET $set WHERE $condicion";
        $result = $con->query($sql);
        if (!$result) {
            die("Error en la actualización: " . $con->error);
        }
        return $result;
    }

    /**
     * @param string $tabla Nombre de la tabla
     * @param mixed ...$clave Array o valores de la clave primaria
     * @return bool|\mysqli_result Resultado de la eliminación
     */
    public static function delete($tabla, ...$clave) {
        $con = self::getCon();
        $sql = "SHOW KEYS FROM $tabla WHERE Key_name = 'PRIMARY'";
        $result = $con->query($sql);
        if (!$result) {
            die("Error al obtener la clave primaria: " . $con->error);
        }
        $clavePrimaria = [];
        while ($row = $result->fetch_assoc()) {
            $clavePrimaria[] = $row['Column_name'];
        }

        $valoresClave = [];
        foreach ($clave as $val) {
            if (is_array($val)) {
                $valoresClave = array_merge($valoresClave, $val);
            } else {
                $valoresClave[] = $val;
            }
        }

        $condicion = implode(" AND ", array_map(function ($k, $v) {
            return "$k='$v'";
        }, $clavePrimaria, $valoresClave));
        $sql = "DELETE FROM $tabla WHERE $condicion";
        $result = $con->query($sql);
        if (!$result) {
            die("Error en la eliminación: " . $con->error);
        }
        return $result;
    }
}
