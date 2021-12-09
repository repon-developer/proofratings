<?php
/**
 * File containing the class Proofratings_Locations_Table.
 *
 * @package proofratings-manager
 * @since   1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registered sites WP List table
 * @since 1.0.1
 */
class Proofratings_Locations_Table extends WP_List_Table  {
	/**
	 * Entry per page
	 * @since  1.0.1
	 */
	var $per_page = 15;

	/**
	 * Total Items
	 * @since  1.0.1
	 */
	var $total_items = 0;

	/**
	 * Constructor.
	 * @since  1.0.1
	 */
	public function __construct() {
        $this->per_page = $this->get_items_per_page( 'locations_per_page', 15 );
		parent::__construct(array('singular' => 'singular_form', 'plural' => 'locations_table', 'ajax' => false));
	}
	

	/**
     * Get locations
     * @since 1.0.1
     */
	public function get_locations() {
		global $wpdb;
		$offset = ($this->per_page * ($this->get_pagenum() - 1));

		$locations = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->proofratings ORDER BY location ASC LIMIT %d, %d", $offset, $this->per_page));

		array_walk($locations, function(&$location){
			$reviews = maybe_unserialize( $location->reviews );
			if ( !is_array($reviews) ) {
				$reviews = [];
			}

			$location->reviews = $reviews;

			$location->connected = 2;
			$location->widgets = 2;
		});

		
		$status_text = __('~', 'proofratings');
		if ( sizeof(array_unique(wp_list_pluck( $locations, 'status'))) == 1 ) {
			$status_text = $locations[0]->status;
		}

		array_unshift($locations, (object) array(
			'id' => 'overall',
			'location' => __('ALL LOCATIONS (OVERALL)', 'proofratings'),
			'connected' => 3,
			'widgets' => 4,
			'status' => $status_text
		));

		$this->total_items = $wpdb->get_var("SELECT count(*) FROM $wpdb->proofratings");
		return $locations;
	}

	/**
     * Prepare the items for the table to process
     * @since 1.0.1
     */
    public function prepare_items() {
        global $wpdb;

        $sortable = $this->get_sortable_columns(); 
        $this->_column_headers = array($this->get_columns());

		$this->items = $this->get_locations();
        
        $this->set_pagination_args( array(
            'total_items' => $this->total_items,
            'per_page'    => $this->per_page
        ) );
    }

	/**
     * set bulk action for table
     * @since 1.0.1
     */
    public function get_bulk_actions() {
        $actions = [
            'bulk-delete' => __('Delete', 'proofratings-manager'),
        ];

        return $actions;
    }

	function get_columns() {
		return [
			'cb' => '<input type="checkbox" />',
			'edit' => __('Edit', 'proofratings'),
			'location' => __('Location', 'proofratings'),
			'connected' => __('# of Sites Connected', 'proofratings'),
			'widgets' => __('# of Widgets', 'proofratings'),
			'status' => __('Status', 'proofratings'),
			'action' => __('Action', 'proofratings-manager'),
		];
	}

	/**
     * Define what data to show on each column of the table
     * @param  String $column_name - Current column name
     * @since 1.0.6
     */
    public function column_default( $location, $column_name ) {
		switch ($column_name) {
			case 'location':
			case 'connected':
			case 'widgets':
			case 'status':
				return $location->$column_name;
			
			default:
				return print_r($location->$column_name, true);
		}
    }

	/**
     * checkbox column 
     * @since 1.0.6
     */
    function column_cb( $location ) {
        return sprintf('<input type="checkbox" name="locations[]" value="%d" />', $location->id);
    }

	/**
     * Edit column 
     * @since 1.0.6
     */
    function column_edit( $location ) {
		$permalink = add_query_arg( 'location', $location->id, menu_page_url('proofratings-locations', false));
        return sprintf('<a class="dashicons dashicons-edit" href="%s"></a>', $permalink);
    }

	/**
     * Location column 
     * @since 1.0.6
     */
    function column_location( $location ) {
		$permalink = add_query_arg( 'location', $location->id, menu_page_url('proofratings-locations', false));
        return sprintf('<a href="%s">%s</a>', $permalink, $location->location);
    }

	/**
     * Action column 
     * @since 1.0.6
     */
    function column_action( $location ) {
		$permalink = add_query_arg( ['id' => $location->id, '_nonce' => wp_create_nonce( 'delete-location' )], menu_page_url('proofratings-locations', false));

		if ( !empty($_GET['s']) ) {
			$permalink = add_query_arg( ['s' => $_GET['s']], $permalink );
		}

		if ( $this->get_pagenum() > 1 ) {
			$permalink = add_query_arg( ['paged' => $this->get_pagenum()], $permalink );
		}
		
		$actions[] = sprintf('<a class="dashicons dashicons-trash" href="%s"></a>', $permalink);
        return implode(' ', $actions);
    }
}