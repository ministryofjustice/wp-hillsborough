<?php
/* Add Witness taxonomy */

function hillsborough_create_witness_taxonomy() {
    register_taxonomy(
            'witness', array(
        'evidence',
        'hearing'
            ), array(
        'label' => __('Witnesses'),
        'rewrite' => array('slug' => 'witness'),
        'hierarchical' => false,
            )
    );
}

add_action('init', 'hillsborough_create_witness_taxonomy');

/* Add additional field to witness taxonomy */
function hillsborough_witness_tax_add_meta() {
    ?>
    <div class="form-field">
        <label for="term_meta[letter]"><?php _e('Index Letter', 'hillsborough'); ?></label>
        <input type="text" name="term_meta[letter]" id="term_meta[letter]" value="">
        <p class="description"><?php _e('By default the witness is listed under the first letter of their surname, but this can be overridden here by supplying an alternative letter.', 'hillsborough'); ?></p>
    </div>
    <?php
}
add_action('witness_add_form_fields', 'hillsborough_witness_tax_add_meta', 10, 2);

function hillsborough_witness_tax_edit_meta($term) {
	// put the term ID into a variable
	$t_id = $term->term_id;
 
	// retrieve the existing value(s) for this meta field. This returns an array
	$term_meta = get_option( "taxonomy_$t_id" ); ?>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="term_meta[letter]"><?php _e( 'Index Letter', 'pippin' ); ?></label></th>
		<td>
			<input type="text" name="term_meta[letter]" id="term_meta[letter]" value="<?php echo esc_attr( $term_meta['letter'] ) ? esc_attr( $term_meta['letter'] ) : ''; ?>">
			<p class="description"><?php _e( 'By default the witness is listed under the first letter of their surname, but this can be overridden here by supplying an alternative letter.','pippin' ); ?></p>
		</td>
	</tr>
<?php
}
add_action( 'witness_edit_form_fields', 'hillsborough_witness_tax_edit_meta', 10, 2 );

function save_taxonomy_custom_meta( $term_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$t_id = $term_id;
		$term_meta = get_option( "taxonomy_$t_id" );
		$cat_keys = array_keys( $_POST['term_meta'] );
		foreach ( $cat_keys as $key ) {
			if ( isset ( $_POST['term_meta'][$key] ) ) {
				$term_meta[$key] = $_POST['term_meta'][$key];
			}
		}
		// Save the option array.
		update_option( "taxonomy_$t_id", $term_meta );
	}
}  
add_action( 'edited_witness', 'save_taxonomy_custom_meta', 10, 2 );  
add_action( 'create_witness', 'save_taxonomy_custom_meta', 10, 2 );