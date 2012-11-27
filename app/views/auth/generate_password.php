<?$this->view('partials/head')?>

	<div class="container">
		 <div class="row">
    		<h2 class="span12"><i class="icon-lock"></i> Generate password hash</h2>
      	</div><!--/row-->
      	<div class="row">
      		<?php if (isset($error)):?>
      		<div class="alert alert-error">
				<?php echo $error?>
			</div>
			<?php endif?>

			<div class="offset3 span6">
			<form class="well" action="" method="post" accept-charset="UTF-8">

					<label for="loginusername">Username:</label><input type="text" id="loginusername"><br/>
					<label for="loginpassword">Password:</label><input type="password" id="loginpassword">
					<label for="genpwd">Add this line to config.php:</label><input type="text" id="genpwd" class="input-xxlarge"><br/>
			</form>
			</div>
      	</div>

<?$this->view('partials/foot')?>

	<script type="text/javascript">
		genpwd();

		$('#loginusername').change(function(){
			genpwd();
		});
		$('#loginusername').keyup(function(){
			genpwd();
		});
		$('#loginpassword').change(function(){
			genpwd();
		})
		$('#loginpassword').keyup(function(){
			genpwd();
		});

		function genpwd()
		{
			var usr = $('#loginusername').val();
			var pwd = $('#loginpassword').val();
			var str = '';

			// Send password and get hash
			if(usr && pwd)
			{
				$.post( '<?=url('auth/xhttp_hash')?>', {pwd:pwd},
					function( data ) {
						$('#genpwd').val("$GLOBALS['auth_config']['" + usr + "'] = '" + data + "';");
					  }
				);
			}
			else
			{
				$('#genpwd').val('');
			}
			
			

			
		}
	</script>

	</div><!--/.fluid-container-->
  </body>
</html>