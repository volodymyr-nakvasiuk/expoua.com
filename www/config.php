<?php
	session_start();

	if (isset($_GET['e'])) define ('ConfigType', $_GET['e']);

	require_once '../init.php';

	Bootstrap::setupSelfConst();
	Bootstrap::setupPhpIni();
	Bootstrap::setupRegistry ();
	Bootstrap::setupTranslation();

	$lang = Zend_Registry::get ('Zend_Translate');
	$modulesPath = Bootstrap::$root . '/application/config/modules';
	require_once $modulesPath.'/auth.php';
	$error = false;
	$data = auth::run();
	if (!$data){
		if (isset($_POST['module']) && isset($_POST['action'])){
			$module = $_POST['module'];
			$action = $_POST['action'];
			if (file_exists($modulesPath.'/'.$module.'.php')){
				require_once $modulesPath.'/'.$module.'.php';
				if (method_exists($module,$action)){
					$error = $module::$action();
					if (!$error){
						header("Location: ".$_SERVER["REQUEST_URI"]);
						exit;
					}
				}
				else {
					$error = $lang->translate('Can\'t find action "'.$action.'" in module "'.$module.'".');
				}
			}
			else {
				$error = $lang->translate('Can\'t find module "'.$module.'".');
			}
		}
		if (!$error){
			if (isset($_GET['m'])){
				$module = $_GET['m'];
				if (file_exists($modulesPath.'/'.$module.'.php')){
					require_once $modulesPath.'/'.$module.'.php';
					$data = $module::run();
				}
				else {
					$error = $lang->translate('Can\'t find module "'.$module.'".');
				}
			}
			else{
				$error = $lang->translate('No module name entered.');
			}
		}
	}

	if ($data && $data['error']) $error = $data['error'];
?>
<!doctype html>
<html lang="en" class="no-js">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=IE8"/>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<title><?php echo $lang->translate('Configurations for ').php_uname();?></title>
	<link href="/css/config/style.css" rel="stylesheet" type="text/css" />
	<!--[if IE]><![endif]-->
	<?php /*<script type="text/javascript"></script>*/ ?>
</head>
<!--[if lt IE 7 ]> <body class="ie6"> <![endif]-->
<!--[if IE 7 ]>    <body class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <body class="ie8"> <![endif]-->
<!--[if IE 9 ]>    <body class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <body> <!--<![endif]-->
<?php
	if ($error){
?>
<script type="text/javascript">alert('ERROR: <?php echo addcslashes($error, "'\"\n\r\0\t\\");?>');history.back();</script>
<?php
	}
	elseif($data) {
?>
<div id="container">
	<div id="top">
		<h1><?php echo $data['title'];?></h1>
	</div>

	<div id="leftSide">
		<form action="<?php echo $_SERVER["REQUEST_URI"];?>" method="POST" class="form">
		<input type="hidden" name="module" value="<?php echo $data['module'];?>"/>
		<input type="hidden" name="action" value="<?php echo $data['action'];?>"/>
		<?php foreach($data['fieldsets'] as $fieldSet){ ?>
		<fieldset>
			<legend><?php echo $fieldSet['title'];?></legend>
			<?php foreach($fieldSet['fields'] as $field){ ?>
			<label for="<?php echo $field['name'];?>"><?php echo $field['title'];?></label>
			<div class="div_texbox">
				<input
					name="<?php echo $field['name'];?>"
					type="<?php echo $field['type'];?>"
					class="<?php echo $field['class'];?>"
					value="<?php echo $field['value'];?>"
				/>
			</div>
			<?php } ?>
			<div class="clear"></div>
		</fieldset>
		<hr size="1">
		<?php } ?>
<?php /*
		<fieldset>
			<legend>Personal details</legend>
				<label for="name">Name</label>

				<label for="username">Username</label>
				<div class="div_texbox">
					<input name="username" type="text" class="username" id="username" value="username" />
				</div>
				<label for="password">Password</label>
				<div class="div_texbox">
					<input name="password" type="password" class="password" id="password" value="password" />
				</div>
				<div class="div_texbox">
					<input name="name" type="text" class="textbox" id="name" value="John Doe" />
				</div>
				<label for="address">Address</label>
				<div class="div_texbox">
					<input name="address" type="text" class="textbox" id="address" value="12 main" />
				</div>
				<label for="city">City</label>
				<div class="div_texbox">
					<input name="city" type="text" class="textbox" id="city" value="Rochester" />
				</div>
				<label for="country">Country</label>
				<div class="div_texbox">
					<input name="country" type="text" class="textbox" id="country" value="United States" />
				</div>
			<div class="clear"></div>
		</fieldset>
		<hr size="1">
*/ ?>
		<div class="button_div">
			<input name="Submit" type="submit" value="OK" class="buttons" />
		</div>
		</form>
	</div>
<?php
	}
?>
<?php
/*
	<div id="rightSide">
		<p>
 			This is simple right side text.<br/>
 			<u>Underlined text.</u>
		</p>
	</div>
	<div class="clear"></div>
 */
?>
</div>
</body>
</html>

