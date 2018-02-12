<?php

$APP_ID = "83bb2d03";
$APP_KEY = "8b2dfc41c2f5f033eecbd040aeff48e4";

$imageData = $_REQUEST['imageData'];
$method = $_REQUEST['method'];
$subject_id = $_REQUEST['subject_id'];


 if(isset($imageData) && isset($method)){
    if($method == 'verify' && isset($subject_id)){
        echo checkImage($imageData, $subject_id);
    } else if($method == 'recognize') {
        echo recognizeImage($imageData);
    } else if($method == 'enroll' && isset($subject_id)) {
        echo enroll($imageData,$subject_id);
    } 
 } else if($method == 'load') {
    echo loadSubjectIds();
 } else {
     echo "no url";
 }
 

 //"subject_id":"venkat",

 function checkImage($imageData,$subject_id){
    global $APP_ID, $APP_KEY;
    $queryUrl = "http://api.kairos.com/verify";
    $imageObject = '{"image": "'.$imageData.'",
        "subject_id": "'.$subject_id.'",
        "gallery_name":"zydesoft"}';
    $request = curl_init($queryUrl);
    curl_setopt($request, CURLOPT_POST, true);
    curl_setopt($request,CURLOPT_POSTFIELDS, $imageObject);
    curl_setopt($request, CURLOPT_HTTPHEADER, array(
            "Content-type: application/json",
            "app_id:" . $APP_ID,
            "app_key:" . $APP_KEY
        )
    );
    curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($request);
    curl_close($request);
    return $response;
 }

 function recognizeImage($imageData){
    global $APP_ID, $APP_KEY;
    $queryUrl = "http://api.kairos.com/recognize";
    $imageObject = '{"image": "'.$imageData.'",
        "gallery_name":"zydesoft"}';
    $request = curl_init($queryUrl);
    curl_setopt($request, CURLOPT_POST, true);
    curl_setopt($request,CURLOPT_POSTFIELDS, $imageObject);
    curl_setopt($request, CURLOPT_HTTPHEADER, array(
            "Content-type: application/json",
            "app_id:" . $APP_ID,
            "app_key:" . $APP_KEY
        )
    );
    curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($request);
    curl_close($request);
    return $response;
 }

 function enroll($imageData,$subject_id){
    global $APP_ID, $APP_KEY;
    $queryUrl = "http://api.kairos.com/enroll";
    $imageObject = '{"image": "'.$imageData.'",
        "subject_id": "'.$subject_id.'",
        "gallery_name":"zydesoft"}';
    $request = curl_init($queryUrl);
    curl_setopt($request, CURLOPT_POST, true);
    curl_setopt($request,CURLOPT_POSTFIELDS, $imageObject);
    curl_setopt($request, CURLOPT_HTTPHEADER, array(
            "Content-type: application/json",
            "app_id:" . $APP_ID,
            "app_key:" . $APP_KEY
        )
    );
    curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($request);
    curl_close($request);
    return $response;
 }

 function loadSubjectIds(){
    global $APP_ID, $APP_KEY;
    $queryUrl = "http://api.kairos.com/gallery/view";
    $imageObject = '{"gallery_name":"zydesoft"}';
    $request = curl_init($queryUrl);
    curl_setopt($request, CURLOPT_POST, true);
    curl_setopt($request,CURLOPT_POSTFIELDS, $imageObject);
    curl_setopt($request, CURLOPT_HTTPHEADER, array(
            "Content-type: application/json",
            "app_id:" . $APP_ID,
            "app_key:" . $APP_KEY
        )
    );
    curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($request);
    curl_close($request);
    return $response;
 }

?>