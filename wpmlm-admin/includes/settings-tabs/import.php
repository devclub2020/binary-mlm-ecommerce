<?php

class WPMLM_Settings_Tab_Import extends WPMLM_Settings_Tab
{
	private $file = false;
	private $step = 1;
	private $display_data = array();
	private $completed = false;

	public function __construct() {
		parent::__construct();

		$file = get_transient( 'wpmlm_settings_tab_import_file' );
		if ( $file )
			$this->file = $file;

		$this->step = empty( $_REQUEST['step'] ) ? 1 : (int) $_REQUEST['step'];
		if ( $this->step < 1 || $this->step > 3 )
			$this->step = 1;

		switch ( $this->step ) {
			case 2:
				$this->prepare_import_columns();
				break;
			case 3:
				$this->import_data();
				break;
		}

		$this->hide_submit_button();
	}

	private function prepare_import_columns() {
		$this->hide_update_message();
		ini_set( 'auto_detect_line_endings', 1 );
		$handle = @fopen( $this->file, 'r' );

		if ( ! $handle ) {
			$this->reset_state();
			return;
		}

		$first_row = @fgetcsv( $handle );
		$categories = get_terms( 'wpmlm_product_category', 'hide_empty=0' );

		$this->display_data = array(
			'columns'    => $first_row,
			'categories' => $categories,
		);
	}

	private function reset_state() {
		delete_transient( 'wpmlm_settings_tab_import_file' );
		$this->file = false;
		$this->completed = false;
		$this->display_data = array();
	}

	private function import_data() {
		ini_set( 'auto_detect_line_endings', 1 );
		$handle     = @fopen( $this->file, 'r' );
		if ( ! $handle ) {
			$this->reset_state();
			return;
		}

		$length     = filesize( $this->file );

		$column_map = array_flip( $_POST['value_name'] );
		extract( $column_map, EXTR_SKIP );

		while ( $row = @fgetcsv( $handle, $length, ',' ) ) {
			$product = array(
				'post_title'             => isset( $row[$column_name] ) ? $row[$column_name] : '',
				'content'                => isset( $row[$column_description] ) ? $row[$column_description] : '',
				'additional_description' => isset( $row[$column_additional_description] ) ? $row[$column_additional_description] : '',
				'price'                  => isset( $row[$column_price] ) ? str_replace( '$', '', $row[$column_price] ) : 0,
				'weight'                 => isset( $row[$column_weight] ) ? $row[$column_weight] : '',
				'weight_unit'            => isset( $row[$column_weight_unit] ) ? $row[$column_weight_unit] : '',
				'pnp'                    => null,
				'international_pnp'      => null,
				'file'                   => null,
				'image'                  => '0',
				'quantity_limited'       => isset( $row[$column_quantity_limited] ) ? $row[$column_quantity_limited] : '',
				'quantity'               => isset( $row[$column_quantity] ) ? $row[$column_quantity] : null,
				'special'                => null,
				'special_price'          => null,
				'display_frontpage'      => null,
				'notax'                  => null,
				'active'                 => null,
				'donation'               => null,
				'no_shipping'            => null,
				'thumbnail_image'        => null,
				'thumbnail_state'        => null,
				'meta'                   => array(
					'_wpmlm_price'            => isset( $row[$column_price] ) ? str_replace( '$', '', $row[$column_price] ) : 0,
					'_wpmlm_special_price'    => '',
					'_wpmlm_sku'              => isset( $row[$column_sku] ) ? $row[$column_sku] : '',
					'_wpmlm_stock'            => isset( $row[$column_quantity] ) ? $row[$column_quantity] : null,
					'_wpmlm_limited_stock'    => isset( $row[$column_quantity_limited] ) ? $row[$column_quantity_limited] : '',
					'_wpmlm_product_metadata' => array(
						'weight'      => isset( $row[$column_weight] ) ? $row[$column_weight] : '',
						'weight_unit' => isset( $row[$column_weight_unit] ) ? $row[$column_weight_unit] : '',
					)
				)
			);

			$product = wpmlm_sanitise_product_forms( $product );
			// status needs to be set here because wpmlm_sanitise_product_forms overwrites it :/
			$product['post_status'] = $_POST['post_status'];
			$product_id = wpmlm_insert_product( $product );
			wp_set_object_terms( $product_id , array( (int)$_POST['category'] ) , 'wpmlm_product_category' );
		}

		$this->reset_state();
		$this->completed = true;
		add_settings_error( 'wpmlm-settings', 'settings_updated', __( 'CSV file imported.', 'wpmlm' ), 'updated' );
	}

	public function callback_submit_options() {
		if ( isset( $_FILES['csv_file'] ) && isset( $_FILES['csv_file']['name'] ) && ($_FILES['csv_file']['name'] != '') ) {
			$this->hide_update_message();
			ini_set( 'auto_detect_line_endings', 1 );
			$file = $_FILES['csv_file'];
			$file_path = WPMLM_FILE_DIR . $file['name'];
			if ( move_uploaded_file( $file['tmp_name'], WPMLM_FILE_DIR . $file['name'] ) ) {
				set_transient( 'wpmlm_settings_tab_import_file', $file_path );
				return array( 'step' => 2 );
			}
		}

		if ( $this->completed )
			return array( 'step' => 1 );

		return array( 'step' => $this->step + 1 );
	}

	private function display_imported_columns() {
		extract( $this->display_data );
		?>
			<p><?php _e( 'For each column, select the field it corresponds to in \'Belongs to\'. You can upload as many products as you like.', 'wpmlm' ); ?></p>
			<div class='metabox-holder' style='width:90%'>
				<div style='width:100%;' class='postbox'>
					<h3 class='hndle'><?php _e('Product Status' , 'wpmlm' ); ?></h3>
					<div class='inside'>
						<table>
							<tr>
								<td style='width:80%;'>
									<?php _e( 'Select if you would like to import your products in as Drafts or Publish them right away.' , 'wpmlm' ); ?>
									<br />
								</td>
								<td>
									<select name='post_status'>
										<option value='publish'><?php _e( 'Publish', 'wpmlm'); ?></option>
										<option value='draft'  ><?php _e( 'Draft'  , 'wpmlm'); ?></option>
									</select>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<?php foreach ( $columns as $key => $datum ): ?>
					<div style='width:100%;' class='postbox'>
						<h3 class='hndle'><?php printf(__('Column (%s)', 'wpmlm'), ($key + 1)); ?></h3>
						<div class='inside'>
							<table>
								<tr>
									<td style='width:80%;'>
										<?php echo $datum; ?>
										<br />
									</td>
									<td>
										<select  name='value_name[<?php echo $key; ?>]'>
											<option <?php selected( $key, 0 ); ?> value='column_name'                  ><?php _e('Product Name'          , 'wpmlm'); ?></option>
											<option <?php selected( $key, 1 ); ?> value='column_description'           ><?php _e('Description'           , 'wpmlm'); ?></option>
											<option <?php selected( $key, 2 ); ?> value='column_additional_description'><?php _e('Additional Description', 'wpmlm'); ?></option>
											<option <?php selected( $key, 3 ); ?> value='column_price'                 ><?php _e('Price'                 , 'wpmlm'); ?></option>
											<option <?php selected( $key, 4 ); ?> value='column_sku'                   ><?php _e('SKU'                   , 'wpmlm'); ?></option>
											<option <?php selected( $key, 5 ); ?> value='column_weight'                ><?php _e('Weight'                , 'wpmlm'); ?></option>
											<option <?php selected( $key, 6 ); ?> value='column_weight_unit'           ><?php _e('Weight Unit'           , 'wpmlm'); ?></option>
											<option <?php selected( $key, 7 ); ?> value='column_quantity'              ><?php _e('Stock Quantity'        , 'wpmlm'); ?></option>
											<option <?php selected( $key, 8 ); ?> value='column_quantity_limited'      ><?php _e('Stock Quantity Limit'  , 'wpmlm'); ?></option>
										</select>
									</td>
								</tr>
							</table>
						</div>
					</div>
				<?php endforeach; ?>
				<label for='category'><?php _e('Please select a category you would like to place all products from this CSV into' , 'wpmlm' ); ?>:</label>
				<select id='category' name='category'>
					<?php foreach ( $categories as $category ): ?>
						<option value="<?php echo $category->term_id; ?>"><?php echo esc_html( $category->name ); ?></option>
					<?php endforeach; ?>
				</select>
				<input type="hidden" name="step" value="3" />
				<input type='submit' value='<?php echo esc_html_x( 'Continue', 'import csv', 'wpmlm' ); ?>' class='button-primary'>
			</div>
		<?php
	}

	private function display_default() {
		extract( $this->display_data );
		?>
			<?php _e( '<p>You can import your products from a comma delimited text file.</p><p>An example of a csv import file would look like this: </p><p>Description, Additional Description, Product Name, Price, SKU, weight, weight unit, stock quantity, is limited quantity</p>', 'wpmlm' ); ?>
			<input type='file' name='csv_file' />
			<?php submit_button( esc_html_x( 'Upload', 'import csv', 'wpmlm' ) ); ?>
		<?php
	}

	public function display() {
		switch ( $this->step ) {
			case 1:
				$this->display_default();
				break;
			case 2:
				$this->display_imported_columns();
				break;
			default:
				$this->display_default();
				break;
		}
	}
}