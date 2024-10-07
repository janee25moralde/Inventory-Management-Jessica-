<?php
  ob_start();
  require_once('includes/load.php');
  if($session->isUserLoggedIn(true)) { redirect('home.php', false);}
?>
<?php include_once('layouts/header.php'); ?>
<div class="login-page" style = "padding: 20px; border-radius: 20px; display: flex; justify-content: center; width: 70%; height: 400px;">
  <style>
        body{
          background-color: #ff4d4d;
        }
  </style>
    <div class="text-center">
      <div class="jolliPicture" style = "display: flex; justify-content: center; position: relative; right: 60px; top: 80px;">
          <img src="pictures/jolli.png" alt="" style = "width: 150px; height: 150px; ">
      </div>

       
     </div>
        <div>
        <div style = "display: flex; justiy-content: center;">
        <h4 style = "font-weight: bolder; font-size: 25px; position: relative; left: 30px; ">Inventory Management System</h4>
        </div>
        <?php echo display_msg($msg); ?>
      <form method="post" action="auth.php" class="clearfix">
        <div class="form-group" style = "margin-top: 40px;">
              <label for="username" class="control-label" style = "color: #ff4d4d">Username</label>
              <input type="name" class="form-control" name="username" placeholder="Username">
        </div>
        <div class="form-group">
            <label for="Password" class="control-label" style = "color: #ff4d4d">Password</label>
            <input type="password" name= "password" class="form-control" placeholder="Password">
        </div>
        <div class="form-group">
               <div style = "display: flex; justify-content: center; margin-top: 40px; border-radius:10px;">
               <button type="submit" class="btn btn-danger" style="border-radius:0%">Login</button>
               </div>
        </div>
    </form>
      </div>
</div>
<?php include_once('layouts/footer.php'); ?>
