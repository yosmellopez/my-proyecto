<?php

namespace IcanBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use IcanBundle\Entity;

class EstacionServicioController extends BaseController
{

    public function indexAction() {
        return $this->render('IcanBundle:EstacionServicio:index.html.twig', array());
    }

    /**
     * listarAction Acción que lista los estacionServicios
     *
     */
    public function listarAction(Request $request) {
        // search filter by keywords
        $query = !empty($request->get('query')) ? $request->get('query') : array();
        $sSearch = isset($query['generalSearch']) && is_string($query['generalSearch']) ? $query['generalSearch'] : '';
        //Sort
        $sort = !empty($request->get('sort')) ? $request->get('sort') : array();
        $sSortDir_0 = !empty($sort['sort']) ? $sort['sort'] : 'asc';
        $iSortCol_0 = !empty($sort['field']) ? $sort['field'] : 'titulo';
        //$start and $limit
        $pagination = !empty($request->get('pagination')) ? $request->get('pagination') : array();
        $page = !empty($pagination['page']) ? (int)$pagination['page'] : 1;
        $limit = !empty($pagination['perpage']) ? (int)$pagination['perpage'] : -1;
        $start = 0;

        try {
            $pages = 1;
            $total = $this->TotalEstacionServicios($sSearch);
            if ($limit > 0) {
                $pages = ceil($total / $limit); // calculate total pages
                $page = max($page, 1); // get 1 page when $_REQUEST['page'] <= 0
                $page = min($page, $pages); // get last page when $_REQUEST['page'] > $totalPages
                $start = ($page - 1) * $limit;
                if ($start < 0) {
                    $start = 0;
                }
            }

            $meta = array('page' => $page, 'pages' => $pages, 'perpage' => $limit, 'total' => $total, 'field' => $iSortCol_0, 'sort' => $sSortDir_0);

            $data = $this->ListarEstacionServicios($start, $limit, $sSearch, $iSortCol_0, $sSortDir_0);

            $resultadoJson = array('meta' => $meta, 'data' => $data);

            return new Response(json_encode($resultadoJson));

        } catch (Exception $e) {
            $resultadoJson['success'] = false;
            $resultadoJson['error'] = $e->getMessage();

            return new Response(json_encode($resultadoJson));
        }
    }

    /**
     * salvarAction Acción que inserta un menu en la BD
     *
     */
    public function salvarAction(Request $request) {
        $estacionServicio_id = $request->get('estacionServicio_id');

        $ubicacionMapa = $request->get('ubicacionMapa');
        $titulo = $request->get('titulo');
        $descripcion = $request->get('descripcion');
        $estado = $request->get('estado');

        if ($estacionServicio_id == "") {
            $resultado = $this->SalvarEstacionServicio($ubicacionMapa, $titulo, $descripcion, $estado);
        } else {
            $resultado = $this->ActualizarEstacionServicio($estacionServicio_id, $ubicacionMapa, $titulo, $descripcion, $estado);
        }

        if ($resultado['success']) {

            $resultadoJson['success'] = $resultado['success'];
            $resultadoJson['message'] = "La operación se realizó correctamente";

            return new Response(json_encode($resultadoJson));
        } else {
            $resultadoJson['success'] = $resultado['success'];
            $resultadoJson['error'] = $resultado['error'];

            return new Response(json_encode($resultadoJson));
        }
    }

    /**
     * eliminarAction Acción que elimina un estacionServicio en la BD
     *
     */
    public function eliminarAction(Request $request) {
        $estacionServicio_id = $request->get('estacionServicio_id');

        $resultado = $this->EliminarEstacionServicio($estacionServicio_id);
        if ($resultado['success']) {
            $resultadoJson['success'] = $resultado['success'];
            $resultadoJson['message'] = "La operación se realizó correctamente";
            return new Response(json_encode($resultadoJson));
        } else {
            $resultadoJson['success'] = $resultado['success'];
            $resultadoJson['error'] = $resultado['error'];
            return new Response(json_encode($resultadoJson));
        }
    }

    /**
     * eliminarEstacionServiciosAction Acción que elimina los estacionServicios seleccionados en la BD
     *
     */
    public function eliminarEstacionServiciosAction(Request $request) {
        $ids = $request->get('ids');

        $resultado = $this->EliminarEstacionServicios($ids);
        if ($resultado['success']) {
            $resultadoJson['success'] = $resultado['success'];
            $resultadoJson['message'] = "La operación se realizó correctamente";
            return new Response(json_encode($resultadoJson));
        } else {
            $resultadoJson['success'] = $resultado['success'];
            $resultadoJson['error'] = $resultado['error'];
            return new Response(json_encode($resultadoJson));
        }
    }

    /**
     * cargarDatosAction Acción que carga los datos del estacionServicio en la BD
     *
     */
    public function cargarDatosAction(Request $request) {
        $estacionServicio_id = $request->get('estacionServicio_id');

        $resultado = $this->CargarDatosEstacionServicio($estacionServicio_id);
        if ($resultado['success']) {

            $resultadoJson['success'] = $resultado['success'];
            $resultadoJson['estacionServicio'] = $resultado['estacionServicio'];

            return new Response(json_encode($resultadoJson));
        } else {
            $resultadoJson['success'] = $resultado['success'];
            $resultadoJson['error'] = $resultado['error'];

            return new Response(json_encode($resultadoJson));
        }
    }

    /**
     * salvarImagenAction Acción para subir una imagen al servidor
     *
     */
    public function salvarImagenAction() {
        try {
            $nombre_archivo = $_FILES['foto']['name'];
            $array_nombre_archivo = explode('.', $nombre_archivo);
            $pos = count($array_nombre_archivo) - 1;
            $extension = $array_nombre_archivo[$pos];

            $archivo = $this->generarCadenaAleatoria() . '.' . $extension;

            //Manejar la imagen
            $dir = 'uploads/estacionServicios/';
            $archivo_tmp = $_FILES['foto']['tmp_name'];
            move_uploaded_file($archivo_tmp, $dir . $archivo);


            $resultadoJson['success'] = true;
            $resultadoJson['name'] = $archivo;
            return new Response(json_encode($resultadoJson));
        } catch (Exception $e) {
            $resultadoJson['success'] = false;
            $resultadoJson['error'] = $e->getMessage();
            return new Response(json_encode($resultadoJson));
        }
    }

    /**
     * eliminarImagenAction Acción que elimina una imagen en la BD
     *
     */
    public function eliminarImagenAction(Request $request) {
        $imagen = $request->get('imagen');

        $resultado = $this->EliminarImagen($imagen);
        if ($resultado['success']) {

            $resultadoJson['success'] = $resultado['success'];
            $resultadoJson['message'] = "La operación se realizó correctamente";
            return new Response(json_encode($resultadoJson));
        } else {
            $resultadoJson['success'] = $resultado['success'];
            $resultadoJson['error'] = $resultado['error'];
            return new Response(json_encode($resultadoJson));
        }
    }

    /**
     * EliminarImagen: Elimina una imagen en la BD
     *
     * @author Marcel
     */
    public function EliminarImagen($imagen) {
        $resultado = array();
        //Eliminar foto
        if ($imagen != "") {
            $dir = 'uploads/estacionServicios/';
            if (is_file($dir . $imagen)) {
                unlink($dir . $imagen);
            }
        }

        $em = $this->getDoctrine()->getManager();

        $entity = $this->getDoctrine()->getRepository('IcanBundle:EstacionServicio')->findOneBy(array('imagen' => $imagen));
        if ($entity != null) {
            $entity->setImagen("");
        }

        $em->flush();

        $resultado['success'] = true;
        return $resultado;
    }

    /**
     * CargarDatosEstacionServicio: Carga los datos de un estacionServicio
     *
     * @param int $estacionServicio_id Id
     *
     * @author Marcel
     */
    public function CargarDatosEstacionServicio($estacionServicio_id) {
        $resultado = array();
        $arreglo_resultado = array();

        $entity = $this->getDoctrine()->getRepository('IcanBundle:EstacionServicio')->find($estacionServicio_id);
        if ($entity != null) {

            $arreglo_resultado['ubicacionMapa'] = $entity->getUbicacionMapa();
            $arreglo_resultado['titulo'] = $entity->getTitulo();
            $arreglo_resultado['descripcion'] = $entity->getDescripcion();
            $arreglo_resultado['estado'] = ($entity->isEstado() == 1) ? true : false;

            $resultado['success'] = true;
            $resultado['estacionServicio'] = $arreglo_resultado;
        }

        return $resultado;
    }

    /**
     * EliminarEstacionServicio: Elimina un rol en la BD
     * @param int $estacionServicio_id Id
     * @author Marcel
     */
    public function EliminarEstacionServicio($estacionServicio_id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->getDoctrine()->getRepository('IcanBundle:EstacionServicio')->find($estacionServicio_id);
        if ($entity != null) {
            $em->remove($entity);
            $em->flush();
            $resultado['success'] = true;
        } else {
            $resultado['success'] = false;
            $resultado['error'] = "No existe el registro solicitado";
        }

        return $resultado;
    }

    /**
     * EliminarEstacionServicios: Elimina los estacionServicios seleccionados en la BD
     * @param int $ids Ids
     * @author Marcel
     */
    public function EliminarEstacionServicios($ids) {
        $em = $this->getDoctrine()->getManager();
        if ($ids != "") {
            $ids = explode(',', $ids);
            foreach ($ids as $estacionServicio_id) {
                if ($estacionServicio_id != "") {
                    $entity = $this->getDoctrine()->getRepository('IcanBundle:EstacionServicio')->find($estacionServicio_id);
                    if ($entity != null) {
                        $em->remove($entity);
                    }
                }
            }
        }
        $em->flush();
        $resultado['success'] = true;
        $mensaje = "La operación se ha realizado correctamente";
        $resultado['message'] = $mensaje;

        return $resultado;
    }

    /**
     * ActualizarEstacionServicio: Actualiza los datos del estacionServicio en la BD
     *
     * @param string $estacionServicio_id Id
     *
     * @author Marcel
     */
    public function ActualizarEstacionServicio($estacionServicio_id, $ubicacionMapa, $titulo, $descripcion, $estado) {
        $em = $this->getDoctrine()->getManager();

        $resultado = array();
        $entity = $this->getDoctrine()->getRepository('IcanBundle:EstacionServicio')->find($estacionServicio_id);
        if ($entity != null) {
            //Verificar nombre
            $estacionServicio = $this->getDoctrine()->getRepository('IcanBundle:EstacionServicio')->findOneBy(array('titulo' => $titulo));
            if ($estacionServicio != null) {
                if ($entity->getEstacionId() != $estacionServicio->getEstacionId()) {
                    $resultado['success'] = false;
                    $resultado['error'] = "El nombre del estacionServicio está en uso, por favor intente ingrese otro.";
                    return $resultado;
                }
            }

            $entity->setUbicacionMapa($ubicacionMapa);
            $entity->setTitulo($titulo);
            $entity->setDescripcion($descripcion);
            $entity->setEstado($estado);

            $em->flush();
            $resultado['success'] = true;
        } else {
            $resultado['success'] = false;
            $resultado['error'] = "No existe un estacionServicio que se corresponda con ese identificador";
        }
        return $resultado;
    }

    /**
     * SalvarEstacionServicio: Guarda los datos del usuario en la BD
     *
     *
     * @author Marcel
     */
    public function SalvarEstacionServicio($ubicacionMapa, $titulo, $descripcion, $estado) {
        $resultado = array();
        $em = $this->getDoctrine()->getManager();

        //Verificar nombre
        $estacionServicio = $this->getDoctrine()->getRepository('IcanBundle:EstacionServicio')->findOneBy(array('titulo' => $titulo));
        if ($estacionServicio != null) {
            $resultado['success'] = false;
            $resultado['error'] = "El nombre del estacionServicio está en uso, por favor intente ingrese otro.";
            return $resultado;
        }

        $entity = new Entity\EstacionServicio();
        $entity->setUbicacionMapa($ubicacionMapa);
        $entity->setTitulo($titulo);
        $entity->setDescripcion($descripcion);
        $entity->setEstado($estado);

        $em->persist($entity);
        $em->flush();

        $resultado['success'] = true;
        return $resultado;
    }

    /**
     * RenombrarImagen: Renombra la imagen en la BD
     *
     * @author Marcel
     */
    public function RenombrarImagen($id, $imagen) {
        $dir = 'uploads/estacionServicios/';
        $imagen_new = "";

        if ($imagen != "") {
            $extension_array = explode('.', $imagen);
            $extension = $extension_array[1];

            //Imagen nueva
            $imagen_new = $id . '.' . $extension;
            if (is_file($dir . $imagen)) {
                //Renombrar imagen
                rename($dir . $imagen, $dir . $imagen_new);
            }
        }

        return $imagen_new;
    }

    /**
     * ListarEstacionServicios: Listar los estacionServicios
     *
     * @param int $start Inicio
     * @param int $limit Limite
     * @param string $sSearch Para buscar
     *
     * @author Marcel
     */
    public function ListarEstacionServicios($start, $limit, $sSearch, $iSortCol_0, $sSortDir_0) {
        $arreglo_resultado = array();
        $cont = 0;

        $lista = $this->getDoctrine()->getRepository('IcanBundle:EstacionServicio')->ListarEstacionServicios($start, $limit, $sSearch, $iSortCol_0, $sSortDir_0);

        foreach ($lista as $value) {
            $estacionServicio_id = $value->getEstacionId();
            $acciones = $this->ListarAcciones($estacionServicio_id);

            $arreglo_resultado[$cont] = array("id" => $estacionServicio_id, "titulo" => $value->getTitulo(), "descripcion" => $value->getDescripcion(), "estado" => ($value->isEstado()) ? 1 : 0, "acciones" => $acciones);

            $cont++;
        }

        return $arreglo_resultado;
    }

    /**
     * TotalEstacionServicios: Total de estacionServicios
     * @param string $sSearch Para buscar
     * @author Marcel
     */
    public function TotalEstacionServicios($sSearch) {
        $total = $this->getDoctrine()->getRepository('IcanBundle:EstacionServicio')->TotalEstacionServicios($sSearch);

        return $total;
    }

    /**
     * ListarAcciones: Lista los permisos de un usuario de la BD
     *
     * @author Marcel
     */
    public function ListarAcciones($id) {

        $acciones = "";

        $acciones .= '<a href="javascript:;" class="edit m-portlet__nav-link btn m-btn m-btn--hover-success m-btn--icon m-btn--icon-only m-btn--pill" title="Editar registro" data-id="' . $id . '"> <i class="la la-edit"></i> </a> ';
        $acciones .= ' <a href="javascript:;" class="delete m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Eliminar registro" data-id="' . $id . '"><i class="la la-trash"></i></a>';

        return $acciones;
    }

}
