<?php

$root = realpath( dirname( __FILE__ ) );

include_once "$root/config.php";
include_once "$root/common.php";

$EmpresaId=Request::getQuery("id");
if(!is_numeric($EmpresaId))
{
  $EmpresaId=1;
}

$Tipo=Request::getQuery("Tipo");
if($Tipo!=1 && $Tipo!=2 && $Tipo!=3)
{
  $Tipo=0;
}

$empresa = Db::query("SELECT * FROM empresas where id=$EmpresaId and activo=1");
if(count($empresa)==0)
{
  Theme::_('empresas-notfound');
  exit();
}

$productos = Db::query("SELECT * FROM productos where id_empresa=$EmpresaId and activo=1");

$query="SELECT 
          temas.id,
          temas.id_empresa,
          temas.id_producto,
          usuarios.usuario,
          temas.tipo,
          temas.titulo,
          temas.fecha,
          count(temas_respuestas.id) as respuestas
        FROM temas 
        inner join usuarios on
          temas.id_usuario=usuarios.id  
        left join temas_respuestas on
          temas.id=temas_respuestas.id_tema
        where 
          temas.id_empresa=$EmpresaId and
          temas.activo=1 ";
if($Tipo!=0){$query.="and temas.tipo=$Tipo ";}
$query.="group by
          temas.id,
          temas.id_empresa,
          temas.id_producto,
          usuarios.usuario,
          temas.tipo,
          temas.titulo,
          temas.fecha
        ";
$temas = Db::query($query);

$datos = array();

$datos["TipoTemas"]=array();
$datos["TipoTemas"]["Q"]="pregunta";
$datos["TipoTemas"]["I"]="idea";
$datos["TipoTemas"]["P"]="problema";

$datos["Empresa"]=$empresa[0];
$datos["Productos"]=$productos;
$datos["Temas"]=$temas;
switch($Tipo)
{
  case 1:
    $datos["TemaId"]="1";
    $datos["Tema"]="Preguntas";
    break;
  case 2:
    $datos["TemaId"]="2";
    $datos["Tema"]="Ideas";
    break;
  case 3:
    $datos["TemaId"]="3";
    $datos["Tema"]="Problemas";
    break;
  default:
    $datos["TemaId"]="0";
    $datos["Tema"]="Temas";
}

$temp=Db::query("SELECT id FROM temas where id_empresa=$EmpresaId and activo=1");
$datos["CountTemas"]=count($temp);
$temp=Db::query("SELECT id FROM temas where id_empresa=$EmpresaId and activo=1 and tipo=1");
$datos["CountPreguntas"]=count($temp);
$temp=Db::query("SELECT id FROM temas where id_empresa=$EmpresaId and activo=1 and tipo=2");
$datos["CountIdeas"]=count($temp);
$temp=Db::query("SELECT id FROM temas where id_empresa=$EmpresaId and activo=1 and tipo=3");
$datos["CountProblemas"]=count($temp);

Theme::_('empresa', $datos);
