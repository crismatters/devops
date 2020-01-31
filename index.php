<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>DevOps</title>
    <!-- Bootstrap CSSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </head>
  <?php
    $servername = "192.168.56.13";
    $username = "admin";
    $password = "123";
    $dbname = "devops";
    $dbc = mysqli_connect($servername, $username, $password, $dbname);
    if (!$dbc) {
      die("Conexion fallida: " . mysqli_connect_error());
    };
    if (isset($update)) {

    }
    if(isset($_GET['edit'])){
      $sql="select * from users where id=".$_GET['edit'];
      $rs=mysqli_query($dbc, $sql);
      $rs=mysqli_fetch_array($rs);
      $name=$rs['name'];
      $nick=$rs['nick'];
    }
    if (isset($_POST['name'])) {
      $sql="insert into users(name, nick) values('".$_POST['name']."', '".$_POST['nick']."')";
      mysqli_query($dbc, $sql);
      header('location: index.php');
    }
    if (isset($_GET['delete'])) {
      $sql="delete from users where id=".$_GET['delete'];
      mysqli_query($dbc, $sql);
      header('location: index.php');
    }
    $sql = "select * from users";
    $rs = mysqli_query($dbc,$sql) or die("Error: ".mysqli_error($dbc));
  ?>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
     <a class="navbar-brand" href="#"><img src="DevOps.png" style="max-width:70px"/></a>
     <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
       <span class="navbar-toggler-icon"></span>
     </button>
     <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="#">DevOps</a>
            </li>
          </ul>
       </div>
       <div class="collapse navbar-collapse" id="navbarSupportedContent">
         <form class="form-inline my-2 my-lg-0" <?php if(isset($name)){echo "action='index.php?update=".$rs['id']."'";}else{echo "action='index.php'";} ?> method="post">
          <input class="form-control mr-sm-2" type="text" name="name" placeholder="Username" <?php if(isset($name)){echo "value='".$name."'";}?> required>
          <input class="form-control mr-sm-2" type="text" name="nick" placeholder="Nickname" <?php if(isset($nick)){echo "value='".$nick."'";}?> required>
          <button data-toggle="tooltip" title="Add User" class="btn btn-outline-success my-2 my-sm-0" type="submit"><i class="fa fa-plus"></i></button>
        </form>
       </div>
    </nav>
<div class="container">
   <div class="row">
        <div class="col-md-12">
          <table class="table">
          <tr><th>ID</th><th>USER</th> <th>NICKNAME</th><th></th></tr>
            <?php
              while($row=mysqli_fetch_array($rs)){
                 echo "<tr><td>".$row['id']."</td><td>".$row['name']."</td><td>".$row['nick']."</td>
                       <td><a href='index.php?delete=".$row['id']."' class='btn btn-danger'>
                         <i class='fa fa-trash' data-toggle='tooltip' title='Delete User'></i>
                       </a>
                       <a href='index.php?edit=".$row['id']."' class='btn btn-warning'>
                         <i class='fa fa-pencil' data-toggle='tooltip' title='Edit User'></i>
                       </a></td></tr>";
              }
            ?>
          </table>
        </div>
      </div>
</div>
  </body>
  <script type="text/javascript">
  $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    });
  </script>
</html>
