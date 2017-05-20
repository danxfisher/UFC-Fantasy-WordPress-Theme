<?php
// class for all UFC API calls

class UfcAPI
{
  public function __construct() {

  }

  // get the latest news articles from UFC
  public function getNewsArticles() {
    $content = file_get_contents('http://ufc-data-api.ufc.com/api/v3/news');
    $content = json_decode($content);

    return $content;
  }

  // get all scheduled UFC events
  public function getAllEvents() {
    $content = file_get_contents('http://ufc-data-api.ufc.com/api/v3/events');
  	$content = json_decode($content);

    return $content;
  }

  // get UFC event by event id
  public function getEventByID($event_id) {
    $url = 'http://ufc-data-api.ufc.com/api/v3/events/' . $event_id . '/fights';
    $content = file_get_contents($url);
    $content = json_decode($content);

    return $content;
  }

  // get UFC event by title and date
  public function getEventByTitleAndDate() {

  }

  // get fights for UFC event
  public function getFightsForEvent($event_id) {
    $url = 'http://ufc-data-api.ufc.com/api/v3/events/' . $ufc_event_id . '/fights';
    $content = file_get_contents($url);
    $content = json_decode($content);

    return $content;
  }

}

?>
