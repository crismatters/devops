<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>DevOps</title>
    <!-- Bootstrap CSSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" ></script>
  </head>
  <?php
    $servername = "192.168.56.13"; //DB1
    $username = "admin";
    $password = "123";
    $dbname = "devops";
    $dbc = mysqli_connect($servername, $username, $password, $dbname);
    if (!$dbc) {
      die("Failed: " . mysqli_connect_error());
    };

    $ftp_server = "192.168.56.12";
    $ftp_user_name = "vagrant";
    $ftp_user_pass = "vagrant";
    $conn_id = ftp_connect($ftp_server);
    $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
    $contents = ftp_nlist($conn_id, ".");

    if (isset($_GET['dropfile'])) {
      if (ftp_delete($conn_id, $_GET['dropfile']))
          { echo "<script>alert('Successfully deleted ".$_GET['dropfile']."')</script> ";
           header('location: index.php');}
        else
          { echo "<script>alert('Error deleting ".$_GET['dropfile']."')</script>";
            header('location: index.php');}
    }
    if (isset($_POST['upload'])) {
      $file = $_FILES['uploaded']['tmp_name'];
      $destination_file = "/uploaded/".basename($_FILES['uploaded']['name']);
      $ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");
      $login = ftp_login($ftp_conn, 'vagrant', 'vagrant');
      if (ftp_put($ftp_conn, $destination_file, $file, FTP_BINARY))
          { echo "<script>alert('Successfully uploaded ".$_FILES['uploaded']['name']."')</script> ";
            header('location: index.php');}
        else
          { echo "<script>alert('Error uploading $file')</script>";
            header('location: index.php');}
    }
    if (isset($_GET['update'])) { // Changes are confirmed
      $sql="update users set name='".$_POST['name']."', nick='".$_POST['nick']."' where id=".$_GET['update'];
      mysqli_query($dbc,$sql);
      header('location: index.php');
    }
    if(isset($_GET['edit'])){ // Confirm changes
      $sql="select * from users where id=".$_GET['edit'];
      $rs=mysqli_query($dbc, $sql);
      $rs=mysqli_fetch_array($rs);
      $name=$rs['name'];
      $nick=$rs['nick'];
      $id=$rs['id'];
    }
    if (isset($_POST['name']) && !(isset($_GET['update']))) { // Data posted
      $sql="insert into users(name, nick) values('".$_POST['name']."', '".$_POST['nick']."')";
      mysqli_query($dbc, $sql);
      header('location: index.php');
    }
    if (isset($_GET['delete'])) { // Delete button clicked
      $sql="delete from users where id=".$_GET['delete'];
      mysqli_query($dbc, $sql);
      header('location: index.php');
    }
    $sql = "select * from users";
    $rs = mysqli_query($dbc,$sql) or die("Error: ".mysqli_error($dbc));
  ?>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
     <a class="navbar-brand" href="index.php"><img src="DevOps.png" style="max-width:70px"/></a>
     <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="#">DevOps</a>
            </li>
          </ul>
       </div>
       <div class="collapse navbar-collapse" id="navbarSupportedContent">
         <form class="form-inline my-2 my-lg-0" <?php if(isset($_GET['edit'])){echo "action='index.php?update=$id'";}else{echo "action='index.php'";} ?> method="post">
          <input class="form-control mr-sm-2" type="text" name="name" placeholder="Username" <?php if(isset($_GET['edit'])){echo "value='".$name."'";}?> required>
          <input class="form-control mr-sm-2" type="text" name="nick" placeholder="Nickname" <?php if(isset($_GET['edit'])){echo "value='".$nick."'";}?> required>
          <button data-toggle="tooltip" <?php if(isset($_GET['edit'])){echo "title='Update User'";}else{echo "title='Add User'";} ?> class="btn btn-outline-success my-2 my-sm-0" type="submit">
            <?php if(isset($_GET['edit'])){echo "<i class='fa fa-refresh'></i>  Update";}else{echo "<i class='fa fa-plus'></i> Add User";}?></button>
        </form>
       </div>
       <div class="collapse navbar-collapse" id="navbarSupportedContent">
         <a class="navbar-brand" href="index.php"><img src="Softtek.png" style="max-width:70px"/></a>
       </div>
    </nav>
    <div class="container">
      <hr>
       <div class="row">
            <div class="col-md-12">
              <table class="table table-hover table-bordered" id="users">
              <thead class="thead"><th>ID</th><th>USER</th> <th>NICKNAME</th><th></th><th></th></thead>
                <?php
                  while($row=mysqli_fetch_array($rs)){
                     echo "<tr><td>".$row['id']."</td><td>".$row['name']."</td><td>".$row['nick']."</td>
                           <td><a href='index.php?delete=".$row['id']."' class='btn btn-danger'  data-toggle='tooltip' title='Delete User'>
                             <i class='fa fa-trash'></i>
                           </a></td><td>
                           <a href='index.php?edit=".$row['id']."' class='btn btn-warning' data-toggle='tooltip' title='Edit User'>
                             <i class='fa fa-pencil'></i>
                           </a></td></tr>";
                  }
                ?>
              </table>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <h3>FTP Upload</h3> <br>
                <form action="index.php" method="post" enctype="multipart/form-data" >
                  <input type="file" name="uploaded" id="uploaded"> <br>
                  <input type="submit" name="upload" value="Submit" class="btn btn-info">
                </form>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card" style="padding:5px">
              <!-- <pre><code>  <?php var_dump($contents); ?> </code></pre> -->
              <h3 align="center">FTP Server Files.</h3>
              <table>
                <tr>
                  <?php
                    $i=0;
                      foreach ($contents as &$file) {
                        $i++;
                          if($file != "uploaded"){
                            echo "<td><center>
                                  <a target='_blank' href='ftp://$ftp_user_name:$ftp_user_pass@$ftp_server/".$file."'
                                    data-toggle='tooltip' title='Download ".$file."'>
                                    <i class='fa fa-file-o'></i><br>".$file."</a><br>
                                    <a href='index.php?dropfile=".$file."' data-toggle='tooltip' title='Delete ".$file."'>
                                    <i class='fa fa-trash btn btn-danger'></i></a></center></td>";
                            if ($i == 3) { echo "</tr><tr>"; }
                        }
                      }
                   ?>
                </tr>
              </table>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card" style="padding:5px">
              <!-- <pre><code>  <?php var_dump($contents); ?> </code></pre> -->
              <h3 align="center">FTP Server - Uploaded Files.</h3>
              <table>
                <tr>
                  <?php
                    $i=0;
                    ftp_chdir($conn_id, "./uploaded");
                    $uploads = ftp_nlist($conn_id, "");
                      foreach ($uploads as &$file) {
                        $i++;
                        echo "<td><center>
                              <a target='_blank' href='ftp://$ftp_user_name:$ftp_user_pass@$ftp_server/uploaded/".$file."'
                                data-toggle='tooltip' title='Download ".$file."'>
                                <i class='fa fa-file-o'></i><br>".$file."</a><br>
                                <a href='index.php?dropfile=uploaded/".$file."' data-toggle='tooltip' title='Delete ".$file."'>
                                <i class='fa fa-trash btn btn-danger'></i></a></center></td>";
                            if ($i == 3) { echo "</tr><tr>"; }
                      }
                   ?>
                </tr>
              </table>
              </div>
            </div>
          </div>
      <hr>
    </div>
    <footer class="navbar navbar-expand-lg navbar-dark bg-dark">
     <a class="navbar-brand" href="index.php"><img src="DevOps.png" style="max-width:70px"/></a>
     <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="#">DevOps</a>
            </li>
          </ul>
       </div>
       <div class="collapse navbar-collapse" id="navbarSupportedContent"></div>
       <div class="collapse navbar-collapse" id="navbarSupportedContent">
         <a class="navbar-brand" href="index.php"><img src="Softtek.png" style="max-width:70px"/></a>
       </div>
    </footer>
</body>
  <script type="text/javascript">
  $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    });
    $(document).ready(function() {
       $('#users').DataTable();
    });
  </script>
</html>
