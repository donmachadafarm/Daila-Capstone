<?php include 'includes/sections/header.php'; ?>

    <div class="container">
        <div class="row">
          <div class="col-md-4 offset-4 mx-auto d-block text-center"><br><br /><br>
            <img id="logo" src="images/logo.png" width=200 height="200">
          </div>
        </div>
        <div class="row">
            <div class="col-md-4 offset-4">
                <div class="align-self-center login-panel panel panel-default">
                    <div class="panel-heading">
                      <br><br><br><br><br>
                    </div>
                    <div class="panel-body">
                       <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                <div class="form-group">
                                    <input required class="form-control" placeholder="Username" name="username" type="text" autofocus maxlength="30" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>">
                                </div>
                                <div class="form-group">
                                    <input required class="form-control" placeholder="Password" name="password" type="password" value="" maxlength="20">
                                </div>
                                <input type="submit" name="submit" value="Login" class="btn btn-lg btn-success btn-block"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php



if (isset($_POST['submit'])){

    $message=NULL;

     if (empty($_POST['username'])){
      $_SESSION['username']=FALSE;
      $message.='<div id="note"><p>You forgot to enter your username!';
     } else {
      $_SESSION['username']=$_POST['username'];
     }

     if (empty($_POST['password'])){
      $_SESSION['password']=FALSE;
      $message.='<div id="note"><p>You forgot to enter your password!';
     } else {
      $_SESSION['password']=$_POST['password'];
     }
        $myusername = $_POST['username'];
          $mypassword = md5($_POST['password']);
          $sql = "SELECT * FROM User WHERE username = '$myusername' and password = '$mypassword'";
          $result = mysqli_query($conn,$sql);
          $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    /** Proceed to homepage of user **/
    if ($row["userType"]==101)
    {      $_SESSION['userType']=101;
           $_SESSION['userid']=$row["userID"];
           header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");

    } else {
     $message.='<div id="note" class="text-center"><br /><br /><p>Incorrect username or password. Please try again.</p></div>';
    if (isset($_SESSION['badlogin']))
      $_SESSION['badlogin']++;
    else
      $_SESSION['badlogin']=1;

    }
}/*End of main Submit conditional*/

if (isset($message)){
 echo '<font color="red">'.$message. '</font>';
}

?>


<?php include 'includes/sections/footer.php'; ?>
