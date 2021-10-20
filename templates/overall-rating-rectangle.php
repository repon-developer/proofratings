<?php 

?>
<table class="form-table">
	<tr>
		<th scope="row">
			<?php _e('Shortcode', 'proofratings') ?>
			<p class="description" style="font-weight: normal">Embed shortcode</p>
		</th>
		<td><code class="shortocde-area" id="floating-badge-shortcode">[proofratings_floating_badge badge_style="style1"]</code></td>
	</tr>

	<tr>
		<th scope="row"><?php _e('Badge Visibility', 'proofratings') ?></th>
		<td>
			<label>
				<input name="proofratings_floating_badge_settings[float]" value="no" type="hidden">
				<input class="checkbox-switch checkbox-float-embed" name="proofratings_floating_badge_settings[float]" value="yes" type="checkbox" <?php checked( 'yes', $badge_settings['float'] ) ?>>
				<?php _e('Float/Embed only option', 'proofratings'); ?>
			</label>
		</td>
	</tr>

	<tr id="badge-tablet-visibility">
		<th scope="row"><?php _e('Tablet Visibility', 'proofratings') ?></th>
		<td>
			<label>
				<input name="proofratings_floating_badge_settings[tablet]" value="no" type="hidden">
				<input class="checkbox-switch" name="proofratings_floating_badge_settings[tablet]" value="yes" type="checkbox" <?php checked( 'yes', $badge_settings['tablet'] ) ?>>
				<?php _e('Show/Hide on tablet', 'proofratings'); ?>
			</label>
		</td>
	</tr>

	<tr id="badge-mobile-visibility">
		<th scope="row"><?php _e('Mobile Visibility', 'proofratings') ?></th>
		<td>
			<label>
				<input name="proofratings_floating_badge_settings[mobile]" value="no" type="hidden">
				<input class="checkbox-switch" name="proofratings_floating_badge_settings[mobile]" value="yes" type="checkbox" <?php checked( 'yes', $badge_settings['mobile'] ) ?>>
				<?php _e('Show/Hide on mobile', 'proofratings'); ?>
			</label>
		</td>
	</tr>

	<tr id="badge-close-options">
		<th scope="row"><?php _e('Close option', 'proofratings') ?></th>
		<td>
			<label>
				<input name="proofratings_floating_badge_settings[close_button]" value="no" type="hidden">
				<input class="checkbox-switch" name="proofratings_floating_badge_settings[close_button]" value="yes" type="checkbox" <?php checked( 'yes', $badge_settings['close_button'] ) ?>>
			</label>
		</td>
	</tr>

	<tr>
		<th scope="row" style="vertical-align:middle"><?php _e('Badge Type', 'proofratings') ?></th>
		<td>
			<div class="proofratings-styles">
				<select name="proofratings_floating_badge_settings[badge_style]" data-name="badge_style">
					<option value="style1" <?php selected('style1', @$badge_settings['badge_style']) ?> data-img="<?php echo PROOFRATINGS_PLUGIN_URL; ?>/assets/images/floating-badge-style1.png"><?php _e('Style 1', 'proofratings'); ?></option>
					<option value="style2" <?php selected('style2', @$badge_settings['badge_style']) ?> data-img="<?php echo PROOFRATINGS_PLUGIN_URL; ?>/assets/images/floating-badge-style2.png"><?php _e('Style 2', 'proofratings'); ?></option>
				</select>

				<img src="<?php echo PROOFRATINGS_PLUGIN_URL; ?>/assets/images/floating-badge-style1.png" alt="Proofratings style">
			</div>
		</td>
	</tr>

	<tr id="badge-position">
		<th scope="row"><?php _e('Position', 'proofratings') ?></th>
		<td>
			<select name="proofratings_floating_badge_settings[position]" data-position="<?php echo @$badge_settings['position']; ?>">
				<option value="left" <?php selected('left', $badge_settings['position']) ?>><?php _e('Left', 'proofratings') ?></option>
				<option value="right" <?php selected('right', $badge_settings['position']) ?>><?php _e('Right', 'proofratings') ?></option>
			</select>
		</td>
	</tr>

	<tr>
		<th scope="row"><?php _e('Star Color', 'proofratings') ?></th>
		<td>
			<input class="proofratings-color-field" type="text" 
				name="proofratings_floating_badge_settings[star_color]"
				value="<?php esc_attr_e($badge_settings['star_color']) ?>" data-default-color="#212A3D">
		</td>
	</tr>

	<tr id="badge-hide-shadow">
		<th scope="row"><?php _e('Shadow', 'proofratings') ?></th>
		<td>
			<input name="proofratings_floating_badge_settings[shadow]" value="no" type="hidden">
			<input class="checkbox-switch" name="proofratings_floating_badge_settings[shadow]" value="yes" type="checkbox" <?php checked( 'yes', $badge_settings['shadow'] ) ?>>
		</td>
	</tr>

	<tr id="badge-shadow-color">
		<th scope="row"><?php _e('Shadow Color', 'proofratings') ?></th>
		<td>
			<input class="proofratings-color-field" type="text" 
				name="proofratings_floating_badge_settings[shadow_color]"
				value="<?php esc_attr_e($badge_settings['shadow_color']) ?>" data-default-color="#f6d300">
		</td>
	</tr>

	<tr id="badge-shadow-hover-color">
		<th scope="row"><?php _e('Shadow Hover Color', 'proofratings') ?></th>
		<td>
			<input class="proofratings-color-field" type="text" 
				name="proofratings_floating_badge_settings[shadow_hover]"
				value="<?php esc_attr_e($badge_settings['shadow_hover']) ?>" data-default-color="#377dbc">
		</td>
	</tr>

	<tr>
		<th scope="row"><?php _e('Background Color', 'proofratings') ?></th>
		<td>
			<input class="proofratings-color-field" type="text" 
				name="proofratings_floating_badge_settings[background_color]" 
				value="<?php esc_attr_e($badge_settings['background_color']) ?>" data-default-color="#fff">
		</td>
	</tr>

	<tr>
		<th scope="row"><?php _e('Review Text Color', 'proofratings') ?></th>
		<td>
			<input class="proofratings-color-field" type="text" 
				name="proofratings_floating_badge_settings[review_text_color]" 
				value="<?php esc_attr_e($badge_settings['review_text_color']) ?>">
		</td>
	</tr>

	<tr>
		<th scope="row"><?php _e('Review Background Color', 'proofratings') ?></th>
		<td>
			<input class="proofratings-color-field" type="text" 
				name="proofratings_floating_badge_settings[review_background]" 
				value="<?php esc_attr_e($badge_settings['review_background']) ?>" data-default-color="#212a3d">
		</td>
	</tr>
</table>