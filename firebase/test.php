<?php      
$logFile = 'logfile.txt';

        // Data to log
        $logData = date('Y-m-d H:i:s') . " - User accessed the website.\n";
        
        // Check if the log file exists, if not, create it
        if (!file_exists($logFile)) {
            // Create the log file
            $fileHandle = fopen($logFile, 'w') or die("Unable to create log file!");
            fclose($fileHandle);
        }

      file_put_contents($logFile, 'inside cron job', FILE_APPEND | LOCK_EX);

 include "message.php";
$firebase_account_jsonfile="dilstradingapp-e4411-firebase-adminsdk-6zmr6-3a5127bfaf.json";

 // Send notification
$response = sendFCMNotification($firebase_account_jsonfile,'fAKFeo0JSUO-kEfl3u_v_q:APA91bGn172uVQ31RJ2xxOkgzLCU2Zx6YCN1aU8kffxK3hwOWVSsJfzbg-_Ef7HhV_fHBTrsqzKzGsZXFWYdwMb1iehruHiBxrPAl0tV97Q1cvVsT4GiqQr0XasQOIiJ_uzlC50cL0bq', 'Alert!!!!', 'New order has been placed');

