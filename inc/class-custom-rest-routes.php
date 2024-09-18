<?php 

class Register_Rest_Routes
{
    private string $namespace;
    private string $workshops_locations_route;
    private string $workshop_list_route;
    private string $eu_control_route;

    public function __construct()
    {
        $this->namespace = 'theme/v1';
        $this->workshops_map_settings = '/workshops-map-settings';
		$this->workshops_locations_route = '/workshops-locations';
        $this->workshop_list_route = '/workshop-list';
        $this->eu_control_route = '/eu-control/(?P<license_plate>[a-zA-Z0-9_.-]*)';
    }

	public function register_rest_routes()
    {
        register_rest_route( $this->namespace, $this->workshops_map_settings, [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => [$this, 'get_workshops_map_settings'],
            'permission_callback' => [$this, 'permission_check']
        ]);

        register_rest_route( $this->namespace, $this->workshops_locations_route, [
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => [$this, 'get_workshops_locations'],
			'permission_callback' => [$this, 'permission_check']
		]);

        register_rest_route( $this->namespace, $this->workshop_list_route, [
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => [$this, 'get_workshop_list'],
			'permission_callback' => [$this, 'permission_check']
		]);

        register_rest_route( $this->namespace, $this->eu_control_route, [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => [$this, 'eu_control'],
            'permission_callback' => [$this, 'permission_check']
        ]);
    }

    public function permission_check($request)
    {
        return true;
    }

    public function get_workshops_map_settings()
    {
        $default_settings = get_field('map_default_settings', 'options') ?? [];
        $location_settings = get_field('map_settings_for_location', 'options') ?? [];

        $location_settings_array = [];
        if($location_settings) {
            foreach($location_settings as $location) {
                $location_name = strtolower($location['location']);
                $location_settings_array[$location_name] = [
                    'search_radius' => $location['search_radius'] ?? '50',
                    'zoom' => $location['zoom_level'] ?? '10',
                ];
            }
        }

        return rest_ensure_response( new WP_REST_Response( [
            'default_settings' => $default_settings,
            'location_settings' => $location_settings_array
        ]));
    }

    public function get_workshops_locations()
    {
        $locations = [];
        $labels = [];

        $workshops = get_posts([
            'post_type' => 'workshops',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ]);

        foreach($workshops as $workshop) {
            $workshop_id = $workshop->ID;
            $address = get_field('workshop_address', $workshop_id);
            $location = [
                'lat' => floatval($address['latitude']),
                'lng' => floatval($address['longitude']),
            ];

            $locations[] = $location;
            $labels[] = Helper::load_template_part('blocks/workshops-map/template-parts/workshop-info-modal', '', ['workshop_id' => $workshop->ID]);
        }

        return rest_ensure_response( new WP_REST_Response( [
			'locations' => $locations,
			'info' => $labels
		]));
    }

    public function get_workshop_list()
    {
        $workshop_cards = [];

        $workshops = get_posts([
            'post_type' => 'workshops',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ]);

        foreach($workshops as $workshop) {
            $workshop_cards[] = Helper::load_template_part('blocks/workshops-map/template-parts/workshop-info-card', '', [
                'workshop_id' => $workshop->ID,
                'workshop_title' => get_the_title($workshop->ID),
                'today' => date('N')
            ]);
        }

        return rest_ensure_response( new WP_REST_Response( [
			'workshop_cards' => $workshop_cards,
		]));
    }

    public function eu_control( $request )
    {
        $regnr = $request['license_plate'];

        $url = 'https://www.vegvesen.no/ws/no/vegvesen/kjoretoy/felles/datautlevering/enkeltoppslag/kjoretoydata?kjennemerke=' . $regnr;

        $svv_authorization = get_field('svv_authorization', 'options') ?? '';

        $options = array(
          'http'=>array(
            'method'=>"GET",
            'header'=>"Accept-language: en\r\n" .
                      "Cookie: foo=bar\r\n" .  // check function.stream-context-create on php.net
                      "User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" . // i.e. An iPad 
                      "SVV-Authorization: ". $svv_authorization ."\r\n"
          )
        );

        $context = stream_context_create($options);
        $html = file_get_contents($url, false, $context);

        if ( $html === False )

          $answer['error'] = 'Det oppstod en ukjent feil ved søk';

        if ( !empty( $html ) ) {

          $data = json_decode($html);
          $dataH = get_object_vars($data);
          $dataJ = $dataH['kjoretoydataListe'][0]->godkjenning->tekniskGodkjenning->tekniskeData->generelt->handelsbetegnelse[0];
          $merke = $dataH['kjoretoydataListe'][0]->godkjenning->tekniskGodkjenning->tekniskeData->generelt->merke[0]->merke .' '. $dataH['kjoretoydataListe'][0]->godkjenning->tekniskGodkjenning->tekniskeData->generelt->handelsbetegnelse[0];
          $regnumber = $dataH['kjoretoydataListe'][0]->kjoretoyId->kjennemerke;
          $understellsnummer = $dataH['kjoretoydataListe'][0]->kjoretoyId->understellsnummer;
          $regdato = $dataH['kjoretoydataListe'][0]->forstegangsregistrering->registrertForstegangNorgeDato;
          $sistGodkjent = $dataH['kjoretoydataListe'][0]->periodiskKjoretoyKontroll->sistGodkjent;
          $kontrollfrist = $dataH['kjoretoydataListe'][0]->periodiskKjoretoyKontroll->kontrollfrist;

          $answer['success'] = array (
            "Merke"                   => $merke,
            "Reg. nummer"             => $regnumber,
            "Understellsnummer"       => $understellsnummer,
            "Reg. dato"               => $regdato,
            "Sist godkjent"           => $sistGodkjent,
            "Frist neste godkjenning" => $kontrollfrist
          );

        }

        return json_encode($answer);
    }
}

function register_theme_rest_routes()
{
	if ( class_exists( 'Register_Rest_Routes' ) ) {
		$controller = new Register_Rest_Routes();
		$controller->register_rest_routes();
	}
}

add_action( 'rest_api_init', 'register_theme_rest_routes' );