<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DashboardController extends Controller {

  const AVAILABLE_TYPES = ['nfl', 'nba', 'nhl'];

  // MySportsFeeds API credentials:
  const MSF_API_USERNAME = 'hiqo';

  const MSF_API_PASSWORD = '2303391';

  /**
   * @Route("/", name="dashboard")
   * @Route("/boards/{type}")
   * @param string $type
   *
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function index($type = NULL) {
    if (!in_array($type, self::AVAILABLE_TYPES)) {
      return $this->redirect('/boards/nfl');
    }
    $section = empty($type) ? 'football' : $type;
    return $this->listGames($section);
  }

  /**
   * Render games view
   *
   * @param $section
   *
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function listGames($section) {
    $liveGames = $this->getGamesFromAPI($section, 'live');
    $upcomingGames = $this->getGamesFromAPI($section, 'upcoming');
    return $this->render('dashboard.html.twig', compact('liveGames', 'upcomingGames', 'section'));
  }

  /**
   * Prepare games data for view
   *
   * @param string $game
   * @param string $type
   *
   * @return \Symfony\Component\HttpFoundation\Response
   */
  private function getGamesFromAPI($game, $type) {
    $matches = json_decode($this->getAPIData($game, $type));
    return $matches;
  }

  /**
   * Get games data by API call
   *
   * @param $game
   * @param $type
   *
   * @return mixed
   */
  private function getAPIData($game, $type) {
    $currentDate = date('Ymd');
    if ($type === 'live') {
      $url = "http://api.mysportsfeeds.com/v1.1/pull/{$game}/current/scoreboard.json?fordate={$currentDate}?status=in-progress";
    }
    else {
      $url = "http://api.mysportsfeeds.com/v1.1/pull/{$game}/current/full_game_schedule.json?status=unplayed";
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_ENCODING, "gzip");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      "Authorization: Basic " . base64_encode(self::MSF_API_USERNAME . ":" . self::MSF_API_PASSWORD),
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
  }
}