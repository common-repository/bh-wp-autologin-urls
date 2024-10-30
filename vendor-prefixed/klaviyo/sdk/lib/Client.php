<?php
/**
 * @license MIT
 *
 * Modified by Brian Henry on 26-May-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */
namespace BrianHenryIE\WP_Autologin_URLs\Klaviyo;

use BrianHenryIE\WP_Autologin_URLs\GuzzleHttp\Client as GuzzleClient;
use BrianHenryIE\WP_Autologin_URLs\Klaviyo\ApiException;
use BrianHenryIE\WP_Autologin_URLs\Klaviyo\Configuration;

use BrianHenryIE\WP_Autologin_URLs\Klaviyo\API\CampaignsApi;
use BrianHenryIE\WP_Autologin_URLs\Klaviyo\API\DataPrivacyApi;
use BrianHenryIE\WP_Autologin_URLs\Klaviyo\API\ListsSegmentsApi;
use BrianHenryIE\WP_Autologin_URLs\Klaviyo\API\MetricsApi;
use BrianHenryIE\WP_Autologin_URLs\Klaviyo\API\ProfilesApi;
use BrianHenryIE\WP_Autologin_URLs\Klaviyo\API\TemplatesApi;
use BrianHenryIE\WP_Autologin_URLs\Klaviyo\API\TrackIdentifyApi;




class Client {
    public $api_key = "API_KEY";
    public $wait_seconds;
    public $num_retries;
    public $Campaigns;
    public $DataPrivacy;
    public $ListsSegments;
    public $Metrics;
    public $Profiles;
    public $Templates;
    public $TrackIdentify;
    


    public function __construct($api_key, $num_retries = 3, $wait_seconds = 3) {

        if (gettype($num_retries) == 'NULL'){
            $num_retries = 3;
        } 

        if (gettype($wait_seconds) == 'NULL'){
            $wait_seconds = 3;
        } 

        $this->api_key = $api_key;
        $this->num_retries = $num_retries;
        $this->wait_seconds = $wait_seconds;

        $this->config = clone Configuration::getDefaultConfiguration();
        $this->config->setApiKey('api_key', $this->api_key);

        
        $this->Campaigns = new Subclient(
                new CampaignsApi(new GuzzleClient(),$this->config),
                $wait_seconds = 3,
                $num_retries = 3,
            );
        
        $this->DataPrivacy = new Subclient(
                new DataPrivacyApi(new GuzzleClient(),$this->config),
                $wait_seconds = 3,
                $num_retries = 3,
            );
        
        $this->ListsSegments = new Subclient(
                new ListsSegmentsApi(new GuzzleClient(),$this->config),
                $wait_seconds = 3,
                $num_retries = 3,
            );
        
        $this->Metrics = new Subclient(
                new MetricsApi(new GuzzleClient(),$this->config),
                $wait_seconds = 3,
                $num_retries = 3,
            );
        
        $this->Profiles = new Subclient(
                new ProfilesApi(new GuzzleClient(),$this->config),
                $wait_seconds = 3,
                $num_retries = 3,
            );
        
        $this->Templates = new Subclient(
                new TemplatesApi(new GuzzleClient(),$this->config),
                $wait_seconds = 3,
                $num_retries = 3,
            );
        
        $this->TrackIdentify = new Subclient(
                new TrackIdentifyApi(new GuzzleClient(),$this->config),
                $wait_seconds = 3,
                $num_retries = 3,
            );
        

    }
}