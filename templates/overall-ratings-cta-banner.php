<?php $cta_banner = get_proofratings_overall_ratings_cta_banner(); ?>
<table class="form-table">
	<tr>
		<th scope="row"><?php _e('Tablet Visibility', 'proofratings') ?></th>
		<td>
			<label>
				<input name="proofratings_banner_badge[tablet]" value="no" type="hidden">
				<input class="checkbox-switch" name="proofratings_banner_badge[tablet]" value="yes" type="checkbox" <?php checked( 'yes', $cta_banner->tablet ) ?>>
				<?php _e('Show/Hide on tablet', 'proofratings'); ?>
			</label>
		</td>
	</tr>

	<tr>
		<th scope="row"><?php _e('Mobile Visibility', 'proofratings') ?></th>
		<td>
			<label>
				<input name="proofratings_banner_badge[mobile]" value="no" type="hidden">
				<input class="checkbox-switch" name="proofratings_banner_badge[mobile]" value="yes" type="checkbox" <?php checked( 'yes', $cta_banner->mobile ) ?>>
				<?php _e('Show/Hide on mobile', 'proofratings'); ?>
			</label>
		</td>
	</tr>

	<tr>
		<th scope="row"><?php _e('Close option', 'proofratings') ?></th>
		<td>
			<label>
				<input name="proofratings_banner_badge[close_button]" value="no" type="hidden">
				<input class="checkbox-switch" name="proofratings_banner_badge[close_button]" value="yes" type="checkbox" <?php checked( 'yes', $cta_banner->close_button ) ?>>
			</label>
		</td>
	</tr>

	<tr>
		<td style="padding-left: 0" colspan="2">
			<label><input name="proofratings_banner_badge[customize]" class="checkbox-switch checkbox-yesno" value="yes" type="checkbox" <?php checked( 'yes', $cta_banner->customize) ?>> Customize</label>
		</td>
	</tr>

	<tbody id="overall-ratings-cta-banner-customize-options" style="display: none">
		<tr>
			<th scope="row"><?php _e('Star Color', 'proofratings') ?></th>
			<td>
				<input class="proofratings-color-field" type="text" 
					name="proofratings_banner_badge[star_color]"
					value="<?php esc_attr_e($cta_banner->star_color) ?>" data-default-color="#212A3D">
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e('Top Shadow', 'proofratings') ?></th>
			<td>
				<input name="proofratings_banner_badge[top_shadow]" value="no" type="hidden">
				<input class="checkbox-switch" name="proofratings_banner_badge[top_shadow]" value="yes" type="checkbox" <?php checked( 'yes', $cta_banner->top_shadow ) ?>>
			</td>
		</tr>

		<tr id="banner-badge-shadow-color">
			<th scope="row"><?php _e('Background Color', 'proofratings') ?></th>
			<td>
				<input class="proofratings-color-field" type="text" 
					name="proofratings_banner_badge[background_color]"
					value="<?php esc_attr_e($cta_banner->background_color) ?>" data-default-color="#f6d300">
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e('Rating Text Color', 'proofratings') ?></th>
			<td>
				<input class="proofratings-color-field" type="text" 
					name="proofratings_banner_badge[rating_text_color]"
					value="<?php esc_attr_e($cta_banner->rating_text_color) ?>" data-default-color="#377dbc">
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e('Review Rating Background Color', 'proofratings') ?></th>
			<td>
				<input class="proofratings-color-field" type="text" 
					name="proofratings_banner_badge[review_rating_background_color]" 
					value="<?php esc_attr_e($cta_banner->review_rating_background_color) ?>" data-default-color="#fff">
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e('Number of Review Text Color', 'proofratings') ?></th>
			<td>
				<input class="proofratings-color-field" type="text" 
					name="proofratings_banner_badge[number_review_text_color]" 
					value="<?php esc_attr_e($cta_banner->number_review_text_color) ?>">
			</td>
		</tr>
	</tbody>
</table>

<h2><?php _e('Call-to-action Button', 'proofratings') ?></h2>
<table class="form-table">
	<tr>
		<th scope="row"><?php _e('Button 1 Text', 'proofratings') ?></th>
		<td>
			<input type="text" name="proofratings_banner_badge[button1_text]" value="<?php esc_attr_e($cta_banner->button1_text) ?>">
		</td>
	</tr>

	<tr>
		<th scope="row"><?php _e('Button1 URL', 'proofratings') ?></th>
		<td>
			<input type="text" name="proofratings_banner_badge[button1_url]" value="<?php esc_attr_e($cta_banner->button1_url) ?>">
		</td>
	</tr>

	<tr>
		<th scope="row"><?php _e('Open in new tab', 'proofratings') ?></th>
		<td>
			<label>
				<input name="proofratings_banner_badge[button1_blank]" value="no" type="hidden">
				<input class="checkbox-switch checkbox-onoff" name="proofratings_banner_badge[button1_blank]" value="yes" type="checkbox" <?php checked( 'yes', $cta_banner->button1_blank ) ?>>
			</label>
		</td>
	</tr>

	<tr>
		<th scope="row"><?php _e('Button1 Text Color', 'proofratings') ?></th>
		<td>
			<input class="proofratings-color-field" type="text" 
				name="proofratings_banner_badge[button1_textcolor]"
				value="<?php esc_attr_e($cta_banner->button1_textcolor) ?>">
		</td>
	</tr>

	<tr>
		<th scope="row"><?php _e('Button1 Background Color', 'proofratings') ?></th>
		<td>
			<input class="proofratings-color-field" type="text" 
				name="proofratings_banner_badge[button1_background_color]"
				value="<?php esc_attr_e($cta_banner->button1_background_color) ?>">
		</td>
	</tr>

	<tr>
		<th scope="row"><?php _e('Button1 Shape', 'proofratings') ?></th>
		<td>
			<label>
				<input name="proofratings_banner_badge[button1_shape]" value="round" type="hidden">
				<input class="checkbox-switch checkbox-shape" name="proofratings_banner_badge[button1_shape]" value="rectangle" type="checkbox" <?php checked( 'rectangle', $cta_banner->button1_shape ) ?>>
			</label>
		</td>
	</tr>

	<tr>
		<th scope="row"><?php _e('Button1 Border', 'proofratings') ?></th>
		<td>
			<input  name="proofratings_banner_badge[button1_border]" value="no" type="hidden">
			<label>
				<input class="checkbox-switch" name="proofratings_banner_badge[button1_border]" value="yes" type="checkbox" <?php checked( 'yes', $cta_banner->button1_border ) ?>>
			</label>
		</td>
	</tr>
	
	<tr id="button1-border-color">
		<th scope="row"><?php _e('Button1 Border Color', 'proofratings') ?></th>
		<td>
			<input class="proofratings-color-field" type="text"  name="proofratings_banner_badge[button1_border_color]" value="<?php esc_attr_e($cta_banner->button1_border_color) ?>">
		</td>
	</tr>

	<tr>
		<th scope="row"><?php _e('Button1 Hover Text Color', 'proofratings') ?></th>
		<td>
			<input class="proofratings-color-field" type="text" 
				name="proofratings_banner_badge[button1_hover_textcolor]"
				value="<?php esc_attr_e($cta_banner->button1_hover_textcolor) ?>">
		</td>
	</tr>
	
	<tr>
		<th scope="row"><?php _e('Button1 Hover Background Color', 'proofratings') ?></th>
		<td>
			<input class="proofratings-color-field" type="text" 
				name="proofratings_banner_badge[button1_hover_background_color]"
				value="<?php esc_attr_e($cta_banner->button1_hover_background_color) ?>">
		</td>
	</tr>

	<tr id="button1-border-hover-color">
		<th scope="row"><?php _e('Button1 Hover Border Color', 'proofratings') ?></th>
		<td>
			<input class="proofratings-color-field" type="text" 
				name="proofratings_banner_badge[button1_hover_border_color]"
				value="<?php esc_attr_e($cta_banner->button1_hover_border_color) ?>">
		</td>
	</tr>


	<!-- Button 2 settings -->
	<tr>
		<th scope="row"><?php _e('Button2', 'proofratings') ?></th>
		<td>
			<label>
				<input class="checkbox-switch" name="proofratings_banner_badge[button2]" value="yes" type="checkbox" <?php checked( 'yes', $cta_banner->button2 ) ?>>
			</label>
		</td>
	</tr>

	<tbody id="cta-button2-options">
		<tr>
			<th scope="row"><?php _e('Button2 Text', 'proofratings') ?></th>
			<td>
				<input type="text" name="proofratings_banner_badge[button2_text]" value="<?php esc_attr_e($cta_banner->button2_text) ?>">
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e('Button2 URL', 'proofratings') ?></th>
			<td>
				<input type="text" name="proofratings_banner_badge[button2_url]" value="<?php esc_attr_e($cta_banner->button2_url) ?>">
			</td>
		</tr>

		<tr>
		<th scope="row"><?php _e('Open in new tab', 'proofratings') ?></th>
		<td>
			<label>
				<input name="proofratings_banner_badge[button2_blank]" value="no" type="hidden">
				<input class="checkbox-switch checkbox-onoff" name="proofratings_banner_badge[button2_blank]" value="yes" type="checkbox" <?php checked( 'yes', $cta_banner->button2_blank ) ?>>
			</label>
		</td>
	</tr>

		<tr>
			<th scope="row"><?php _e('Button2 Text Color', 'proofratings') ?></th>
			<td>
				<input class="proofratings-color-field" type="text" 
					name="proofratings_banner_badge[button2_textcolor]"
					value="<?php esc_attr_e($cta_banner->button2_textcolor) ?>">
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e('Button2 Background Color', 'proofratings') ?></th>
			<td>
				<input class="proofratings-color-field" type="text" 
					name="proofratings_banner_badge[button2_background_color]"
					value="<?php esc_attr_e($cta_banner->button2_background_color) ?>">
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e('Button2 Shape', 'proofratings') ?></th>
			<td>
				<label>
					<input name="proofratings_banner_badge[button2_shape]" value="round" type="hidden">
					<input class="checkbox-switch checkbox-shape" name="proofratings_banner_badge[button2_shape]" value="rectangle" type="checkbox" <?php checked( 'rectangle', $cta_banner->button2_shape ) ?>>
				</label>
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e('Button2 Border', 'proofratings') ?></th>
			<td>
				<label>
					<input name="proofratings_banner_badge[button2_border]" value="no" type="hidden">
					<input class="checkbox-switch" name="proofratings_banner_badge[button2_border]" value="yes" type="checkbox" <?php checked( 'yes', $cta_banner->button2_border ) ?>>
				</label>
			</td>
		</tr>
		
		<tr id="button2-border-color">
			<th scope="row"><?php _e('Button2 Border Color', 'proofratings') ?></th>
			<td>
				<input class="proofratings-color-field" type="text"  name="proofratings_banner_badge[button2_border_color]" value="<?php esc_attr_e($cta_banner->button2_border_color) ?>">
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e('Button2 Hover Text Color', 'proofratings') ?></th>
			<td>
				<input class="proofratings-color-field" type="text" 
					name="proofratings_banner_badge[button2_hover_textcolor]"
					value="<?php esc_attr_e($cta_banner->button2_hover_textcolor) ?>">
			</td>
		</tr>
		
		<tr>
			<th scope="row"><?php _e('Button2 Hover Background Color', 'proofratings') ?></th>
			<td>
				<input class="proofratings-color-field" type="text" 
					name="proofratings_banner_badge[button2_hover_background_color]"
					value="<?php esc_attr_e($cta_banner->button2_hover_background_color) ?>">
			</td>
		</tr>

		<tr id="button2-border-hover-color">
			<th scope="row"><?php _e('Button2 Hover Border Color', 'proofratings') ?></th>
			<td>
				<input class="proofratings-color-field" type="text" 
					name="proofratings_banner_badge[button2_hover_border_color]"
					value="<?php esc_attr_e($cta_banner->button2_hover_border_color) ?>">
			</td>
		</tr>
	</tbody>
</table>

<table class="form-table">
	<?php foreach (get_pages() as $page) : ?>
	<tr>
		<th scope="row"><?php echo $page->post_title ?></th>
		<td>	
		<?php
			$checked = !isset($cta_banner->pages[$page->ID]) || $cta_banner->pages[$page->ID] == 'yes'? 'checked' : '';
			printf('<input name="proofratings_banner_badge[on_pages][%s]" value="no" type="hidden">', $page->ID);
			printf(
				'<label><input class="checkbox-switch" name="proofratings_banner_badge[on_pages][%s]" value="yes" %s type="checkbox"></label>',
				$page->ID, $checked
			);
		?>
		</td>
	</tr>
	<?php endforeach; ?>
</table>