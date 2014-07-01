<?php

$featurlicious = Featurlicious::get_instance();

$featured_areas = $featurlicious->sm_read_areas();
?>

<div class="wrap">
	<div class="initial">
		<h2><?php _e( 'Featured Content', 'featurlicious' ) ?></h2>
		<div class="error-area"></div>
		<h3><?php _e( 'Available posts', 'featurlicious' ) ?></h3>
		<p><?php _e( 'To feature a post use the searchbox below. You can also create a new featured content area in the dialog below.', 'featurlicious' ) ?></p>

		<div class="sm-search">
			<input type="text" id="input-search">
			<?php submit_button(__( 'Search', 'featurlicious' ), 'small', 'search-posts'); ?>
			<div id="search-result"></div>
		</div>

		<div class="add-area">
			<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"].'?page=featurlicious/featurlicious-admin.php'); ?>">

				<div class="inputs">
					<label><?php _e( 'Area name', 'featurlicious' ) ?></label>
					<input type="text" name="area-name" id="area-name" placeholder="<?php _e( 'Ej. Home carousel', 'featurlicious' ) ?>"><br>

					<label><?php _e( 'Area description', 'featurlicious' ) ?></label>
					<input type="text" name="area-description" placeholder="<?php _e( 'Ej. Appears in the homepage above the fold', 'featurlicious' ) ?>"><br>
				</div>

				<label><?php _e( 'Give your area a name above, then click Create area.', 'featurlicious' ) ?></label>
				<?php submit_button(__( 'Create area', 'featurlicious' ), 'primary', 'create-area'); ?>
			</form>

			<?php if(!empty($_POST)){
				$area_name = $_POST['area-name'];
				$area_description = $_POST['area-description'];
				$featurlicious->sm_create_area($area_name, $area_description);
			}
			?>

		</div>
	</div>
	

	<div class="featured-areas">
		<?php
		if($featured_areas->have_posts()) {
			while($featured_areas->have_posts()) {
				$featured_areas->the_post();
				$post_id = get_the_ID();
				if(!empty(get_the_title())) {
					?>
					<div class="postbox" id="<?php echo $post_id ?>">
						<h3><?php the_title() ?></h3>
						<p><?php the_excerpt() ?></p>
						<div class="posts-of-area">
							<ul>
								<?php $posts = get_post_meta($post_id, 'sim_posts', true);
								if(!empty($posts)) {
									foreach($posts as $post) {
										$post_id = $post[0];
										$post_title = $post[1];
										$post_permalink = $post[2];
										?>
										<li>
											<a href="<?php echo $post_permalink ?>" id="<?php echo $post_id ?>" class="post-title" target="blank"><?php echo $post_title ?></a>
											<a href="" class="remove-link"><?php _e( 'Remove', 'featurlicious' ) ?></a>
										</li>
										<?php
									}
								}
								?>
							</ul>
							<input type="text" id="input-id">
							<?php submit_button(__( 'Add', 'featurlicious' ), 'small', 'add-post'); ?>
						</div>
						<a href="" class="delete-area"><?php _e( 'Delete', 'featurlicious' ) ?></a>
					</div>
					<?php
				}
			}
		}

		?>
	</div>
</div>