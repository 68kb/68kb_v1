<?php
/* This is the home page that shows the search, categories, and top articles. */
?>

	<h1><?php echo lang('kb_knowledge_base'); ?></h1>

	<div class="search">
		<form method="post" action="<?php echo site_url('search'); ?>" class="searchform">
			<label for="searchtext"><?php echo lang('kb_search_kb'); ?>:</label><br />
			<input type="text" name="searchtext" class="search_input" id="searchtext" value="" /> 
				<select tabindex="4" name="category" id="category">
					<option value="0"><?php echo lang('kb_incat'); ?></option>
					<?php foreach($cat_tree as $row): ?>
					<option value="<?php echo $row['cat_id']; ?>"><?php echo $row['cat_name']; ?></option>
					<?php endforeach; ?>
				</select>
			<input type="submit" name="Search" value="<?php echo lang('kb_search'); ?>" />
		</form>
	</div>


	<h2><?php echo lang('kb_browsecats'); ?></h2>

	<table width="100%" border="0">
		<tr>
			<td>
				<table width="100%">
					<?php 
					$perline = 2;
					$set = $perline;
					foreach($parents->result() as $row): 
						if(($set%$perline) == 0){
		       			  echo  "<tr>";
		      				}
					?>
						<td width="33%">
							<div class="folder"><a href="<?php echo site_url("category/".$row->cat_uri."/"); ?>"><?php echo $row->cat_name; ?></a></div>
						</td>
					<?php
						if((($set+1)%$perline) == 0){
		          				echo "</tr>";
						}
						$set = $set+1;
					?>
					<?php endforeach; ?>
				</table>
			</td>
		</tr>
	</table>


	<div class="grid_5">
		<h2 class="pop"><?php echo lang('kb_most_popular'); ?></h2>
		<ul class="articles">
			<?php foreach($pop->result() as $row): ?>
			<li><a href="<?php echo site_url("article/".$row->article_uri."/"); ?>"><?php echo $row->article_title;?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>

	<div class="grid_5">
		<h2 class="pop"><?php echo lang('kb_new_articles'); ?></h2>
		<ul class="articles">
			<?php foreach($latest->result() as $row): ?>
			<li><a href="<?php echo site_url("article/".$row->article_uri."/"); ?>"><?php echo $row->article_title;?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>

<?php $this->core_events->trigger('template/home'); ?>