<?php

namespace IcanBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PuntoVenta
 *
 * @ORM\Table(name="punto_venta")
 * @ORM\Entity(repositoryClass=IcanBundle\Entity\PuntoVentaRepository"))
 */
class PuntoVenta
{
    /**
     * @var integer
     *
     * @ORM\Column(name="punto_venta_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $puntoVentaId;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=255, nullable=true)
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=1000, nullable=true)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="ubicacion_mapa", type="string", length=5000, nullable=true)
     */
    private $ubicacionMapa;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estado", type="boolean", nullable=true)
     */
    private $estado;

    /**
     * @return int
     */
    public function getPuntoVentaId() {
        return $this->puntoVentaId;
    }

    /**
     * @param int $puntoVentaId
     */
    public function setPuntoVentaId($puntoVentaId) {
        $this->puntoVentaId = $puntoVentaId;
    }

    /**
     * @return string
     */
    public function getTitulo() {
        return $this->titulo;
    }

    /**
     * @param string $titulo
     */
    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    /**
     * @return string
     */
    public function getDescripcion() {
        return $this->descripcion;
    }

    /**
     * @param string $descripcion
     */
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    /**
     * @return string
     */
    public function getUbicacionMapa() {
        return $this->ubicacionMapa;
    }

    /**
     * @param string $ubicacionMapa
     */
    public function setUbicacionMapa($ubicacionMapa) {
        $this->ubicacionMapa = $ubicacionMapa;
    }

    /**
     * @return bool
     */
    public function isEstado() {
        return $this->estado;
    }

    /**
     * @param bool $estado
     */
    public function setEstado($estado) {
        $this->estado = $estado;
    }


}

