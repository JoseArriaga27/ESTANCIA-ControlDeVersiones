<?php
require_once __DIR__ . '/../../config/db_connection.php';
require_once __DIR__ . '/../models/grupoModel.php';

$model = new GrupoModel($connection);
$mensaje = ''; $tipo = '';

if (isset($_POST['insertar'])) {
    $nombre = $_POST['nombreGrupo'];
    $idPeriodo = $_POST['idPeriodo'];
    $idCarrera = $_POST['idCarrera'];
    $ok = $model->agregarGrupo($nombre, $idPeriodo, $idCarrera);
    $mensaje = $ok ? "Grupo registrado correctamente." : "Error al registrar el grupo.";
    $tipo = $ok ? "success" : "danger";
    header("Location: ../views/gruposView.php?msg=$mensaje&type=$tipo");
    exit;
}

if (isset($_POST['actualizar'])) {
    $id = $_POST['idGrupo'];
    $nombre = $_POST['nombreGrupo'];
    $idPeriodo = $_POST['idPeriodo'];
    $idCarrera = $_POST['idCarrera'];
    $ok = $model->editarGrupo($id, $nombre, $idPeriodo, $idCarrera);
    $mensaje = $ok ? "Grupo actualizado correctamente." : "Error al actualizar el grupo.";
    $tipo = $ok ? "success" : "danger";
    header("Location: ../views/gruposView.php?msg=$mensaje&type=$tipo");
    exit;
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $ok = $model->eliminarGrupo($id);
    $mensaje = $ok ? "Grupo eliminado correctamente." : "Error al eliminar el grupo.";
    $tipo = $ok ? "success" : "danger";
    header("Location: ../views/gruposView.php?msg=$mensaje&type=$tipo");
    exit;
}

header("Location: ../views/gruposView.php");
exit;
