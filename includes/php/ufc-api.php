<?php
// class for all UFC API calls

class UfcAPI
{
  public function __construct() {

  }

  // get the latest news articles from UFC
  public static function getNewsArticles() {
    $content = file_get_contents('http://ufc-data-api.ufc.com/api/v3/news');
    $content = json_decode($content);

    return $content;
  }

  // get all scheduled UFC events
  public static function getAllEvents() {
    $content = file_get_contents('http://ufc-data-api.ufc.com/api/v3/events');
  	$content = json_decode($content);

    return $content;
  }

  // get UFC event by event id
  public static function getEventByID($event_id) {
    $url = 'http://ufc-data-api.ufc.com/api/v3/events/' . $event_id . '/fights';
    $content = file_get_contents($url);
    $content = json_decode($content);

    return $content;
  }

  // get UFC event by title and date
  public static function getEventByTitleAndDate() {

  }

  // get fights for UFC event
  public static function getFightsForEvent($event_id) {
    $url = 'http://ufc-data-api.ufc.com/api/v3/events/' . $event_id . '/fights';
    $content = file_get_contents($url);
    $content = json_decode($content);

    return $content;
  }

}

?>
