<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// (Android/IOS)API access key from Fire Base API's Console.
define( 'FCM_API_KEY', 'AAAADtvuby0:APA91bE1_IUwI-DQOXWZbJ-63Wqn9EttwS3-sZ0jCzXpnOAOJsFlxX87gaWVvVJg0dC0dPIKHmCkR2z1Lqovz7gIFeojtF3Dj9SCcG-uaRofjV5_U-2gzUUWpCeUmthnIyx311slWfAK');
// (Android/IOS)API access key from Fire Base API's Console.

class pushNotification 
{
	public function __construct(){
		$this->CI =& get_instance();
	}	

	public function sendPushNotification($reg_id, $payLoad,$pushNotificationData) {

			// firebase server url to send the curl request

	        $url = 'https://fcm.googleapis.com/fcm/send';
	        $headers = array(
	        	'Authorization: key='.FCM_API_KEY,
	        	'Content-Type: application/json'
	        );
	
	        $fields = array(
	            'registration_ids' => $reg_id,
	            'notification'=>$pushNotificationData,
	            'data' => $payLoad,
	        );
			
	    	//return $this->useCurl($url, $headers, json_encode($fields));
    }

    public function sendPushNotificationWeb($web_reg_id,$payLoadWeb,$pushNotificationDataWeb) {
    		// firebase server url to send the curl request

    		$url = 'https://fcm.googleapis.com/fcm/send';
	        $headers = array(
	        	'Authorization: key='.FCM_API_KEY,
	        	'Content-Type: application/json'
	        );

	        $fields = array(
	            'registration_ids' => $web_reg_id,
	            'data' => $notification
	        );



	        return $this->useCurl($url, $headers, json_encode($fields));
    }

    	// Curl 
	private function useCurl($url, $headers, $fields = null) {
			
	        // Initializing curl to open a connection.
	        $ch = curl_init();

	        if ($url) {
	            // Setting the curl url
	            curl_setopt($ch, CURLOPT_URL, $url);

	            //Setting Method as Post
	            curl_setopt($ch, CURLOPT_POST, true);

	            //adding headers
	            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	     
	            // Disabling SSL Certificate support temporarly
	            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	            //adding fields in json format
	            if ($fields) {
	                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	            }
	     
	            // finally executing the curl request
	            $result = curl_exec($ch);
	            print_r($result);

	            if ($result === FALSE) {
	                die('Curl failed: ' . curl_error($ch));
	            }
	     
	            // Close connection
	            curl_close($ch);
	            return $result;
        }
    }
}


?>