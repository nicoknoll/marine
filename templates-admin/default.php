<?php

//$searchForm = $user->hasPermission('page-edit') ? $modules->get('ProcessPageSearch')->renderSearchForm() : '';
$bodyClass = $input->get->modal ? 'modal' : '';
if(!isset($content)) $content = '';

$config->styles->prepend($config->urls->adminTemplates . "styles/main.css?v=2"); 
$config->styles->append($config->urls->adminTemplates . "styles/inputfields.css"); 
$config->styles->append($config->urls->adminTemplates . "styles/ui.css?v=2"); 
$config->scripts->append($config->urls->adminTemplates . "scripts/inputfields.js"); 
$config->scripts->append($config->urls->adminTemplates . "scripts/main.js?v=2"); 

$browserTitle = wire('processBrowserTitle'); 
if(!$browserTitle) $browserTitle = __(strip_tags($page->get('title|name')), __FILE__) . ' &bull; ProcessWire';


?>
<?php 
if(!$user->isLoggedin()) {
	include($config->paths->adminTemplates . "login.inc");
	exit;
}
?>
<!DOCTYPE html>
<html lang="<?php echo __('en', __FILE__); // HTML tag lang attribute
	/* this intentionally on a separate line */ ?>"> 
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex, nofollow" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php echo $browserTitle; ?></title>

	<script type="text/javascript">
		<?php

		$jsConfig = $config->js();
		$jsConfig['debug'] = $config->debug;
		$jsConfig['urls'] = array(
			'root' => $config->urls->root, 
			'admin' => $config->urls->admin, 
			'modules' => $config->urls->modules, 
			'core' => $config->urls->core, 
			'files' => $config->urls->files, 
			'templates' => $config->urls->templates,
			'adminTemplates' => $config->urls->adminTemplates,
			); 
		?>

		var config = <?php echo json_encode($jsConfig); ?>;
	</script>

	<?php foreach($config->styles->unique() as $file) echo "\n\t<link type='text/css' href='$file' rel='stylesheet' />"; ?>
	<?php foreach($config->scripts->unique() as $file) echo "\n\t<script type='text/javascript' src='$file'></script>"; ?>

</head>
<body<?php if($bodyClass) echo " class='$bodyClass'"; ?>>




	<div id="wrapper">

		<?php if($bodyClass != "modal"){?>
		<nav>
			<a id="logo" href="http://processwire.com/">ProcessWire</a>
			<ul id="sidenav" class="nav">
			<?php include($config->paths->adminTemplates . "sidenav.inc"); ?>
			</ul>
			
			
			<!--<a id='sitelink' href='<?php echo $config->urls->root; ?>'><?php echo __('Site', __FILE__); ?></a>-->
			
			<?php if(!$user->isGuest()): ?>
			<div id="user">
				<?php $grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $user->email ) ) ) . "?d=mm&s=44"; ?>
				<div class="avatar" style="background-image:url(<?php echo $grav_url; ?>);"></div>
				<span class="links">
				<a class="title" <?php echo (($user->hasPermission('profile-edit')) ? 'href="'.$config->urls->admin.'profile/"' : ''); ?>><?php echo ucfirst($user->get("title|name")); ?></a>
				<a class="action" href="<?php echo $config->urls->admin; ?>login/logout/"><?php echo __('logout', __FILE__); ?></a>
				</span>
			</div>
			<?php endif; ?>
			
			
		</nav>
		<?php } ?>
		<div id="main">

			<?php if(count($notices)) include($config->paths->adminTemplates . "notices.inc"); ?>
			
			
	
			<?php if(trim($page->summary)) echo "<h2>{$page->summary}</h2>"; ?>
			
			<?php if($page->body) echo $page->body; ?>
			
			<?php echo $content?>
		</div>
		
		
		<?php if($config->debug && $this->user->isSuperuser()) include($config->paths->adminTemplates . "debug.inc"); ?>
		
		
	</div>
	
	
	<!-- Breadcrumb -->
	<?php if($bodyClass != "modal"){?>
		<?php if(!$user->isGuest()): ?>
			
			<ul id="breadcrumb" class="nav"><?php
				foreach($this->fuel('breadcrumbs') as $breadcrumb) {
					$title = __($breadcrumb->title, __FILE__); 
					echo "\n\t\t\t\t<li><a href='{$breadcrumb->url}'>{$title}</a></li>";
				}
				
				$on = __(strip_tags($this->fuel->processHeadline ? $this->fuel->processHeadline : $page->get("title|name")), __FILE__);
				
				echo '<li><a class="on" href="'.$page->url.'../?open='.$input->get->id.'">'.$on.'</a></li>';
				?>
			
			</ul>
		
		<?php endif; ?>	

		<div id="footer" class="footer">ProcessWire <?php echo $config->version . ' <!--v' . $config->systemVersion; ?>--> &copy; <?php echo date("Y"); ?> Ryan Cramer</div>

	<?php } ?>

</body>
</html>
