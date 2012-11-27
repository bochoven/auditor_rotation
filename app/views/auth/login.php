<?$this->view('partials/head')?>

	<div class="container">
		 <div class="row">
    		<h2 class="span12"><i class="icon-lock"></i> Login</h2>
      	</div><!--/row-->
      	<div class="row">
      		<?php if (isset($error)):?>
      		<div class="alert alert-error">
				<?php echo $error?>
			</div>
			<?php endif?>

			<div class="offset4 span4">
			<form class="well" action="<?php echo $url?>" method="post" accept-charset="UTF-8" class="loginfields">
			    <label for="loginusername">Username:</label><input type="text" id="loginusername" name="login" class="text" value="<?php echo $login?>"></input><br/>
			    <label for="loginpassword">Password:</label><input type="password" id="loginpassword" name="password" class="text"></input><br>
			    <input class="btn" type="submit" id="submit" value="Login" />
			</form>
			</div>
      	</div>

<?$this->view('partials/foot')?>

	</div><!--/.fluid-container-->
  </body>
</html>