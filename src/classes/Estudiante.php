<?php

/**
 * Clase Estudiante
 * Representa un estudiante con su nombre y tres notas parciales.
 * 
 * @author [Tu Nombre] — Módulo: Clase base de datos
 */
class Estudiante
{
    public string $nombre;
    public float  $nota1;
    public float  $nota2;
    public float  $nota3;

    /**
     * Constructor de la clase Estudiante.
     *
     * @param string $nombre Nombre del estudiante
     * @param float  $nota1  Primera nota parcial  (0–100)
     * @param float  $nota2  Segunda nota parcial  (0–100)
     * @param float  $nota3  Tercera nota parcial  (0–100)
     */
    public function __construct(string $nombre, float $nota1, float $nota2, float $nota3)
    {
        $this->nombre = $nombre;
        $this->nota1  = $nota1;
        $this->nota2  = $nota2;
        $this->nota3  = $nota3;
    }

    /**
     * Calcula y devuelve el promedio de las tres notas del estudiante.
     *
     * @return float Promedio redondeado a dos decimales
     */
    public function getPromedio(): float
    {
        return round(($this->nota1 + $this->nota2 + $this->nota3) / 3, 2);
    }
}
