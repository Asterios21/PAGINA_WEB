<?php 
require_once "Mysql-access.php";

$conexion = new mysqli($hn, $un, $pw, $db, $port);
if($conexion->connect_error) die("Falla en la conexion");

//Consulta para ver las tareas ingresadas
$view_tasks=$conexion->query("SELECT * FROM task_m");
if (!$view_tasks) die ("Consulta falló");

//Numero de filas de la consulta SELECT * FROM task_m
$view_tasks_rows = $view_tasks->num_rows;

//Borrar tarea
if (isset($_POST['delete']) && isset($_POST['tsk']))
{   
    $task = $_POST['tsk'];
    $query_delete = "DELETE FROM task_m WHERE task_id='$task'";
    $result_delete = $conexion->query($query_delete);
    if (!$result_delete) echo 'FALLO DELETE'; 
}
//Agregar Tarea
if (isset($_POST['dc_task'])!=NULL&& isset($_POST['exp_date'])!=NULL){

    $dc_task=$_POST['dc_task'];
    $exp_date=$_POST['exp_date'];
    $query_insert_task="INSERT INTO task_m values('T','$dc_task','$exp_date')";
    $result_insert_task=$conexion->query($query_insert_task);
    if(!$result_insert_task)die('FALLO INSERT');
}
//Ingreso de datos : Descripcion de tarea y fecha limite(task_description,exp_date)
echo <<<_END

<a href='Login.php'>Click aqui</a> para salir del gestor

<center>
<h1>TAREA</h1>
<form action="TaskMng.php" method="post">
<pre>

INGRESE UNA TAREA

   Descripcion de tarea   
        
<textarea name="dc_task" cols="50" rows="4" wrap="type" placeholder='Escribe' required></textarea>

        Fecha limite     

<input type="datetime-local" name="exp_date" required>

    <input type="submit" value="INGRESAR"> 
</pre>
</form>
</center>
           
_END;

if(isset($_POST['record'])){

    view_H($view_tasks_rows,$view_tasks);
}

echo "<center><form action=TaskMng.php method=post><pre>
    <input type=submit value='Ver Historial' name='record' >
    </center>";

//Funcion para ver todo sobre las tareas
function view_H($rows,$result){
for ($j = 0; $j < $rows; ++$j)
{
    $row = $result->fetch_array(MYSQLI_NUM);

    echo "<center><pre>   
    Numero de tarea                       $row[0]
        Descripcion de tarea                  $row[1]
                  Fecha de expiracion                   $row[2]
          </pre>
               
          <form action=TaskMng.php method=post>
          <input type='hidden' name='delete' value='yes'><input type='hidden' name='tsk' value='$row[0]'><input type=submit value=Borrar>           
          </center>  </form>"              
          ;
         
}
}

function mysql_entities_fix_string($conexion, $string)
{
  return htmlentities(mysql_fix_string($conexion, $string));
}
function mysql_fix_string($conexion, $string)
{
  //if (get_magic_quotes_gpc()) $string = stripslashes($string);
  return $conexion->real_escape_string($string);
}
?>