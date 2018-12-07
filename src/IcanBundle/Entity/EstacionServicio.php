<?php

namespace IcanBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EstacionServicio
 *
 * @ORM\Table(name="estacion_servicio")
 * @ORM\Entity(repositoryClass=IcanBundle\Entity\EstacionServicioRepository"))
 */
class EstacionServicio
{
    /**
     * @var integer
     *
     * @ORM\Column(name="estacion_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $estacionId;

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
    public function getEstacionId() {
        return $this->estacionId;
    }

    /**
     * @param int $estacionId
     */
    public function setEstacionId($estacionId) {
        $this->estacionId = $estacionId;
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

