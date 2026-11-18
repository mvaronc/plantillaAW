<?php

namespace PlantillaAW\Seguridad;

use PlantillaAW\Database\Database;

/** 
 * Clase de seguridad para la autenticación y autorización de usuarios.
 */
class Seguridad {

    private static function iniciarSesion() {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }
    }

    public static function autentica($l, $p) {
        self::iniciarSesion();
        $sql = "SELECT * FROM users WHERE login='$l'";
        Database::query($sql);
        if (Database::getNumRows() != 1) { // Verificar la identidad
            return false;
        } else {
            $resultadoSelect = Database::select("users", "*", ["login"], [$l]);
            $datosUser = $resultadoSelect[0];
            $hashGuardado = $datosUser['password'];
            if (!password_verify($p, $hashGuardado)) { // Autenticado
                return false;
            } else {
                $_SESSION['login'] = $l;
                $_SESSION['rol'] = $datosUser['rol'];
                return true;
            }
        }
    }

    public static function segura($urlNoPermitida, ...$roles) {
        self::iniciarSesion();
        if (!$roles) {
            if (!isset($_SESSION['rol'])) {
                header("Location:" . $urlNoPermitida);
                exit();
            }
        } else {
            $rolActual = $_SESSION['rol'] ?? null;
            if (!in_array($rolActual, $roles)) {
                header("Location:" . $urlNoPermitida);
                exit();
            }
        }
    }

    public static function estaAutenticado() {
        self::iniciarSesion();
        return isset($_SESSION['login']);
    }

    public static function getLogin() {
        self::iniciarSesion();
        if (self::estaAutenticado()) {
            return $_SESSION['login'];
        } else {
            return "";
        }
    }

    public static function getRol() {
        self::iniciarSesion();
        if (self::estaAutenticado()) {
            return $_SESSION['rol'];
        } else {
            return "";
        }
    }

    public static function cierraSesion($urlNoPermitida) {
        self::iniciarSesion();
        session_destroy();
        header("Location:" . $urlNoPermitida);
        exit();
    }
}
