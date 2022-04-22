<?php

/**
 * Class to manage Fnugg API
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Mollie_Ski_Resort_Block
 * @subpackage Mollie_Ski_Resort_Block/services
 */

/**
 * Class to manage Fnugg API
 *
 * @package    Mollie_Ski_Resort_Block
 * @subpackage Mollie_Ski_Resort_Block/services
 * @author     Mollie
 */
class Fnugg_Api {

	/**
	 * The base url of Fnugg API.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $base_url    The base url of Fnugg API
	 */
	private string $base_url;

	/**
	 * Autocomplete endpoint in Fnugg API.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $autocomplete_endpoint    Autocomplete endpoint in Fnugg API
	 */
	private string $autocomplete_endpoint;

	/**
	 * Search endpoint in Fnugg API.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $search_endpoint    Search endpoint in Fnugg API
	 */
	private string $search_endpoint;

	/**
	 * Source Fields to request to Fnugg API.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $source_fields    Source Fields to request to Fnugg API
	 */
	private array $source_fields;

	/**
	 * Expire cache seconds.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      int $expire_cache_seconds    Expire cache seconds
	 */
	private int $expire_cache_seconds;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->base_url = 'https://api.fnugg.no';
		$this->autocomplete_endpoint = '/suggest/autocomplete';
		$this->search_endpoint = '/search';
		$this->source_fields = array(
			'name',
			'last_updated',
			'images.image_16_9_m',
			'region',
			'conditions.combined.top.symbol',
			'conditions.combined.top.temperature',
			'conditions.combined.top.wind',
			'conditions.combined.top.condition_description'
		);

		// 2 hours
		$this->expire_cache_seconds = 7200;

	}

	/**
	 * Register the REST API routes.
	 */
	public function init() {
		register_rest_route( 'mollie-ski-resort/v1', $this->autocomplete_endpoint, array(
			'methods' => WP_REST_Server::READABLE,
			'callback' => array( $this, 'get_autocomplete_suggestion' ),
			'sanitize_callback' => array( $this, 'sanitize_string' ),
			'args' => array(
				'search' => array(
					'required' => true,
					'type' => 'string',
				),
			),
		) );

		register_rest_route( 'mollie-ski-resort/v1', $this->search_endpoint, array(
			'methods' => WP_REST_Server::READABLE,
			'callback' => array( $this, 'get_resort_details' ),
			'sanitize_callback' => array( $this, 'sanitize_string' ),
			'args' => array(
				'search' => array(
					'required' => true,
					'type' => 'string',
				),
			),
		) );
	}

	/**
	 * Get the Autocomplete suggestion from Fnugg API.
	 *
	 * @param WP_REST_Request $request
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_autocomplete_suggestion( WP_REST_Request $request ) {
		$search = $request->get_param( 'search' );
		$transient_key = "get_autocomplete_suggestion_$search";

		$response = get_transient($transient_key);

		if (!$response) {
			$response = wp_remote_get($this->get_formatted_url($this->autocomplete_endpoint, $search));
			set_transient($transient_key, $response, $this->expire_cache_seconds);
		}

		if (!is_wp_error(rest_ensure_response( $response ))) {
			$response['body'] = json_decode($response['body']);
			return rest_ensure_response( $response );
		}

		return new WP_Error( 500, 'An unexpected error happened, please try again later.' );
	}

	/**
	 * Get resort details from Fnugg API.
	 *
	 * @param WP_REST_Request $request
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_resort_details( WP_REST_Request $request ) {
		$search = $request->get_param( 'search' );
		$transient_key = "get_resort_details_$search";

		$response = get_transient($transient_key);

		if (!$response) {
			$response = wp_remote_get($this->get_formatted_url($this->search_endpoint, $search, $this->source_fields));
			set_transient($transient_key, $response, $this->expire_cache_seconds);
		}

		if (!is_wp_error(rest_ensure_response( $response ))) {
			$response['body'] = $this->filter_resort_details($response['body']);
			return rest_ensure_response( $response );
		}

		return new WP_Error( 500, 'An unexpected error happened, please try again later.' );
	}

	/**
	 * Filter resort details from Fnugg API to return only needed fields.
	 *
	 * @param string $body
	 * @return string
	 */
	public function filter_resort_details( string $body ): array {
		$originalBodyDecoded = json_decode($body, 'true');

		return [
			'name' => $originalBodyDecoded['hits']['hits'][0]['_source']['name'],
			'region' => $originalBodyDecoded['hits']['hits'][0]['_source']['region'][0],
			'last_updated' => $originalBodyDecoded['hits']['hits'][0]['_source']['last_updated'],
			'image' => $originalBodyDecoded['hits']['hits'][0]['_source']['images']['image_16_9_m'],
			'symbol' => $originalBodyDecoded['hits']['hits'][0]['_source']['conditions']['combined']['top']['symbol'],
			'temperature_value' => $originalBodyDecoded['hits']['hits'][0]['_source']['conditions']['combined']['top']['temperature']['value'],
			'condition_description' => $originalBodyDecoded['hits']['hits'][0]['_source']['conditions']['combined']['top']['condition_description'],
			'wind' => $originalBodyDecoded['hits']['hits'][0]['_source']['conditions']['combined']['top']['wind'],
		];
	}

	/**
	 * Sanitize string to be used on Request to Fnugg API.
	 *
	 * @param string $search
	 * @return string
	 */
	public function sanitize_string( string $search ): string {
		return trim($search);
	}

	/**
	 * Format url to be used for Requests to Fnugg API.
	 *
	 * @param string|null $endpoint
	 * @param string|null $query
	 * @param array $fields
	 *
	 * @return string
	 */
	private function get_formatted_url( ?string $endpoint, ?string $query, array $fields = array() ): string {
		$url = $this->base_url;

		if ($endpoint) {
			$url .= $endpoint;
		}

		if ($endpoint && $query) {
			$url .= "/?q=$query";
		}

		if ($endpoint && $query && $fields && count($fields) > 0) {
			$url .= '&sourceFields=' . implode(',', $fields);
		}

		return $url;
	}

}
