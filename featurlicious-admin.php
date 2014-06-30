<?php

$featurlicious = Featurlicious::get_instance();

$featured_areas = $featurlicious->sm_read_areas();
?>

<div class="wrap">
	<h2><?php _e( 'Featured Content', 'featurlicious' ) ?></h2>
	<h3><?php _e( 'Available posts', 'featurlicious' ) ?></h3>
	<p><?php _e( 'To feature a post use the searchbox below. You can also create a new featured content area in the dialog below.', 'featurlicious' ) ?></p>

	<div class="sm-search">
		<input type="text" id="input-search">
		<?php submit_button(__( 'Search', 'featurlicious' ), 'small', 'search-posts'); ?>
		<div id="search-result"></div>
	</div>

	<div>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"].'?page=featurlicious/featurlicious-admin.php'); ?>">
			<label><?php _e( 'Area name', 'featurlicious' ) ?></label>
			<input type="text" name="area-name" placeholder="<?php _e( 'Ej. Home carousel', 'featurlicious' ) ?>">

			<label><?php _e( 'Area description', 'featurlicious' ) ?></label>
			<input type="text" name="area-description" placeholder="<?php _e( 'Ej. Appears in the homepage above the fold', 'featurlicious' ) ?>">

			<label><?php _e( 'Give your area a name above, then click Create area.', 'featurlicious' ) ?></label>
			<?php submit_button(__( 'Create area', 'featurlicious' ), 'primary', 'search-posts'); ?>
		</form>

		<?php if(!empty($_POST)){
			$area_name = $_POST['area-name'];
			$area_description = $_POST['area-description'];
			//$featurlicious->sm_create_area($area_name, $area_description);
		}
		?>

	</div>

	<div class="featured-areas">
		<?php
		if($featured_areas->have_posts()) {
			while($featured_areas->have_posts()) {
				$featured_areas->the_post();
				$post_id = get_the_ID();
				if(!empty(get_the_title())) {
					?>
					<div id="<?php echo $post_id ?>">
						<h3><?php the_title() ?></h3>
						<p><?php the_excerpt() ?></p>
					</div>
					<?php
				}
			}
		}

		wp_reset_postdata();
		?>
	</div>
</div>