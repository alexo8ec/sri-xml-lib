<?php

namespace SRI;

use PDO;
use PDOException;

class Conexion
{
    private $user_name;
    private $password;
    private $database;
    private $host_name;
    private $conexion;

    public function __construct($user_name, $password, $database, $host_name = 'localhost')
    {
        $this->user_name = $user_name;
        $this->password = $password;
        $this->database = $database;
        $this->host_name = $host_name;
        $this->conectar();
    }

    private function conectar()
    {
        try {
            $this->conexion = new PDO(
                "mysql:host={$this->host_name};dbname={$this->database};charset=utf8mb4",
                $this->user_name,
                $this->password
            );
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("❌ Error de conexión: " . $e->getMessage());
        }
    }

    // Método público para obtener la conexión activa
    public function getConexion()
    {
        return $this->conexion;
    }

    // Método para ejecutar consultas con parámetros
    public function consulta($sql, $parametros = [])
    {
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute($parametros);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para ejecutar inserts/updates/deletes
    public function ejecutar($sql, $parametros = [])
    {
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute($parametros);
    }

    public function consultarUno($sql, $parametros = [])
    {
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute($parametros);
        return $stmt->fetch(PDO::FETCH_ASSOC); // devuelve solo un array asociativo, no un array de arrays
    }
}
