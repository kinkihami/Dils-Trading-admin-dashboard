<?php 

require_once '../vendor/autoload.php'; 

function get_access_token($JSON_file_path)
{
    $client = new Google_Client();  
    
    try {
        $client->setAuthConfig($JSON_file_path);
        $client->addScope(Google_Service_FirebaseCloudMessaging::CLOUD_PLATFORM);

        $savedTokenJson = readSavedToken();
   
        if ($savedTokenJson) {
            // the token exists, set it to the client and check if it's still valid
            $client->setAccessToken($savedTokenJson);
            $accessToken = $savedTokenJson;
            if ($client->isAccessTokenExpired()) {
                // the token is expired, generate a new token and set it to the client
                $accessToken = generateToken($client);
                $client->setAccessToken($accessToken);
            }
        } else {
            // the token doesn't exist, generate a new token and set it to the client
            $accessToken = generateToken($client);
            $client->setAccessToken($accessToken);
        }
        

        $oauthToken = $accessToken["access_token"];

        return $oauthToken;

        
    } catch (Google_Exception $e) {}
   return false;
}

//Using a simple file to cache and read the toke, can store it in a databse also
function readSavedToken() {
  $tk = @file_get_contents('token.cache');
  if ($tk) return json_decode($tk, true); else return false;
}
function writeToken($tk) {
 file_put_contents("token.cache",$tk);
}

function generateToken($client)
{
    $client->fetchAccessTokenWithAssertion();
    $accessToken = $client->getAccessToken();

    $tokenJson = json_encode($accessToken);
    writeToken($tokenJson);

    return $accessToken;
}

function sendFCMNotification($firebase_account_jsonfile,$token,$title,$message) {
    $url = "https://fcm.googleapis.com/v1/projects";
    $url=$url."/dilstradingapp-e4411/messages:send";
    
    $access_token = get_access_token($firebase_account_jsonfile);

    $data=[];
    if($token=='topic'){
        
              //  echo 'inside topic messaging';

    $data = [
        'message' => [
            
            'notification' => [
              'title' => $title,
              'body' => $message
          ],
          'topic' => 'all',
          
        ]
    ];    
    }else{
     //   echo 'inside token';
        $data = [
        'message' => [
            
            'notification' => [
              "title" => $title,
              "body" => $message
          ],
          
         'token' => $token  //enable for token based message sending
        ]
    ];
    }
    
   // echo $access_token;
    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer " . $access_token,
            "Content-Type: application/json",
        ),
        CURLOPT_POSTFIELDS => json_encode($data),
    );

    $headers=array(
            "Authorization: Bearer " . $access_token,
            "Content-Type: application/json",
        );
        
    
    $ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
$result = curl_exec($ch);
$logFile = 'logfile.txt';

        // Data to log
        $logData = date('Y-m-d H:i:s') . " - User accessed the website.\n";
        
        // Check if the log file exists, if not, create it
        if (!file_exists($logFile)) {
            // Create the log file
            $fileHandle = fopen($logFile, 'w') or die("Unable to create log file!");
            fclose($fileHandle);
        }

      file_put_contents($logFile, $result, FILE_APPEND | LOCK_EX);
if ($result === FALSE) {
       die('FCM Send Error: ' . curl_error($ch));
   }
//echo $url;
// var_dump($result);
curl_close( $ch );
return $result;
}