<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->config->item('charset');?>" />
<title>68 KB Administration</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/tooltip.js"></script>
<link href="<?php echo base_url();?>themes/admin/default/style/default.css" rel="stylesheet" type="text/css" />
<meta http-equiv="pragma" content="no-cache" />

<?php $this->core_events->trigger('template/admin/header'); ?>
</head>

<body> 
	<div id="wrapper">
		<div id="header"><p><?php echo lang('kb_loggedin'); ?> <?php echo $this->session->userdata('username'); ?> | <a href="<?php echo site_url('admin/kb/logout');?>"><?php echo lang('kb_logout'); ?></a> | <a href="http://68kb.com/knowledge-base/" target="_blank"><?php echo lang('kb_support_knowledge_base'); ?></a> | <a href="<?php echo site_url();?>" target="_blank"><?php echo lang('kb_view_site'); ?></a></p></div>

		<div id="topmenu">
			<div id="menu">
				<?php //echo $this->session->userdata('level');?>
				<a href="<?php echo site_url('admin');?>" class="<?php echo ($nav=='dashboard') ? 'activeMenuItem' : 'inactiveMenuItem'; ?>" accesskey="D"><?php echo lang('kb_dashboard'); ?></a>
				<a href="<?php echo site_url('admin/articles');?>" class="<?php echo ($nav=='articles') ? 'activeMenuItem' : 'inactiveMenuItem'; ?>" accesskey="A"><?php echo lang('kb_articles'); ?></a>
				<?php if ($this->session->userdata('level') <= 3): ?>
				<a href="<?php echo site_url('admin/categories');?>" class="<?php echo ($nav=='categories') ? 'activeMenuItem' : 'inactiveMenuItem'; ?>" accesskey="C"><?php echo lang('kb_categories'); ?></a>
				<?php endif; ?>
				<?php if ($this->session->userdata('level') <= 3): ?>
				<a href="<?php echo site_url('admin/glossary');?>" class="<?php echo ($nav=='glossary') ? 'activeMenuItem' : 'inactiveMenuItem'; ?>" accesskey="G"><?php echo lang('kb_glossary'); ?></a>
				<?php endif; ?>
				<?php if ($this->session->userdata('level') == 1): ?>
				<a href="<?php echo site_url('admin/users');?>" class="<?php echo ($nav=='users') ? 'activeMenuItem' : 'inactiveMenuItem'; ?>" accesskey="U"><?php echo lang('kb_users'); ?></a>
				<?php endif; ?>
				<?php if ($this->session->userdata('level') <= 2): ?>
				<?php if($settings['comments'] == 'Y'): ?><a href="<?php echo site_url('admin/comments');?>" class="<?php echo ($nav=='comments') ? 'activeMenuItem' : 'inactiveMenuItem'; ?>" accesskey="1"><?php echo lang('kb_comments'); ?></a><?php endif; ?>
				<?php endif; ?>
				<?php if ($this->session->userdata('level') == 1): ?>
				<a href="<?php echo site_url('admin/settings');?>" class="<?php echo ($nav=='settings') ? 'activeMenuItem' : 'inactiveMenuItem'; ?>" accesskey="S"><?php echo lang('kb_settings'); ?></a>
				<?php endif; ?>
			</div>
		</div>

		<div id="submenu">
			
			<?php if($nav=='dashboard'): ?>
			<div id="submenu_1">
				<a href="<?php echo site_url('admin');?>" accesskey="H"><?php echo lang('kb_dashboard'); ?></a>
				<a href="http://68kb.com/forums/" accesskey="F" target="_blank"><?php echo lang('kb_forums'); ?></a>
				<a href="<?php echo site_url('admin/kb/logout');?>"><?php echo lang('kb_logout'); ?></a>
			</div>
			<?php endif;?>
			
			<?php if($nav=='articles'): ?>
			<div id="submenu_2">
				<a href="<?php echo site_url('admin/articles');?>"><?php echo lang('kb_manage_articles'); ?></a>
				<a href="<?php echo site_url('admin/articles/add');?>"><?php echo lang('kb_add_article'); ?></a>
				<?php $this->core_events->trigger('template/admin/articles'); ?>
			</div>
			<?php endif; ?>
			
			<?php if($nav=='categories'): ?>
			<div id="submenu_3">
				<a href="<?php echo site_url('admin/categories');?>"><?php echo lang('kb_manage_categories'); ?></a>
				<a href="<?php echo site_url('admin/categories/add');?>"><?php echo lang('kb_add_category'); ?></a>
				<?php $this->core_events->trigger('admin/template/nav/categories');?>
			</div>
			<?php endif; ?>
			
			<?php if($nav=='glossary'): ?>
			<div id="submenu_4">
				<a href="<?php echo site_url('admin/glossary');?>"><?php echo lang('kb_manage_glossary'); ?></a>
				<a href="<?php echo site_url('admin/glossary/add');?>"><?php echo lang('kb_add_term'); ?></a>
				<?php $this->core_events->trigger('admin/template/nav/glossary');?>
			</div>
			<?php endif; ?>
			
			<?php if($nav=='users'): ?>
			<div id="submenu_5">
				<a href="<?php echo site_url('admin/users');?>"><?php echo lang('kb_manage_users'); ?></a>
				<a href="<?php echo site_url('admin/users/add');?>"><?php echo lang('kb_add_user'); ?></a>
				<?php $this->core_events->trigger('admin/template/nav/users');?>
			</div>
			<?php endif; ?>
			
			<?php if($nav=='comments'): ?>
			<div id="submenu_6">
				<a href="<?php echo site_url('admin/comments');?>"><?php echo lang('kb_comments'); ?></a>
				<?php $this->core_events->trigger('admin/template/nav/comments');?>
			</div>
			<?php endif; ?>
			
			<?php if($nav=='settings'): ?>
			<div id="submenu_7">
				<a href="<?php echo site_url('admin/settings');?>"><?php echo lang('kb_settings'); ?></a>
				<a href="<?php echo site_url('admin/settings/templates/');?>" accesskey="T"><?php echo lang('kb_templates'); ?></a>
				<a href="<?php echo site_url('admin/modules/');?>" accesskey="M"><?php echo lang('kb_modules'); ?></a>
				<a href="<?php echo site_url('admin/stats/');?>"><?php echo lang('kb_stats'); ?></a>
				<a href="<?php echo site_url('admin/utility/');?>"><?php echo lang('kb_utilities'); ?></a>
				<?php $this->core_events->trigger('admin/template/nav/settings');?>
			</div>
			<?php endif; ?>
		</div>
		
		<div id="content">

		
			<!-- // Content // -->
			
			<?php echo $body; ?>
			
			<!-- // End Content // -->
		
		</div>
</div>

<div id="footer">
	&copy; 2008 - 2010 68 KB - <?php echo $settings['version']; ?> <br />
	Time: <?=$this->benchmark->elapsed_time();?> - Memory: <?=$this->benchmark->memory_usage();?>
</div>
<?php $this->core_events->trigger('admin/template/footer');?>
</body>
</html>