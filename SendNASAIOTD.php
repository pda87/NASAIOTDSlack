<?php
$slackWebhook = "MySlackWebhook";
$nasaAPIKey = "MyNASAAPIKey";

//Get NASA Image Of The Day
$url = "https://api.nasa.gov/planetary/apod?api_key=$nasaAPIKey";
$response = file_get_contents($url);
$iotdJSON = json_decode($response);

//Harvest NASA IOTD properties
$copyright = $iotdJSON->copyright;
$date = $iotdJSON->date;
$explanation = $iotdJSON->explanation;
$title = $iotdJSON->title;
$image = $iotdJSON->url;

//Build JSON for Slack request
$attachments = array([
  "fallback" => "Unable to fetch NASA IOTD",
  "pretext" => "NASA Image Of The Day " . $date . "\n Copyright: $copyright",
  "mrkdown" => "true",
  "title" => $title,
  "title_link" =>$image,
  "text" => $explanation,
  "image_url" => $image,
  "thumb_url" => $image
]);

$data = json_encode(
array(
"attachments" => $attachments
));

//Execute Slack Webhook
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $slackWebhook);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$result = curl_exec($ch);
echo $result;

?>