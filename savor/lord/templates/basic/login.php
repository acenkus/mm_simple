<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<div class="login-panel panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Please Sign In</h3>
				</div>
				<div class="panel-body">
					<form role="form" id="user_reg" name="user_reg" method="post" action="">
						<fieldset>
							<div class="form-group">
								<input class="form-control" placeholder="<?php echo $lang['user']['user']; ?>" name="vartotojas" id="user_login" type="text" autofocus>
							</div>
							<div class="form-group">
								<input class="form-control" placeholder="<?php echo $lang['user']['password']; ?>" name="slaptazodis" id="user_pass" type="password" value="">
							</div>
							<!-- Change this to a button or input when using this as a form -->
							<input type="hidden" name="action" value="prisijungimas" />
							<input class="btn btn-lg btn-success btn-block" type="submit" class="submit" value="<?php echo $lang['user']['login']; ?>" />

						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Core Scripts - Include with every page -->
<script src="js/jquery-1.10.2.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>

<!-- SB Admin Scripts - Include with every page -->
<script src="js/sb-admin.js"></script>

