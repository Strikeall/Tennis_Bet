<?php
error_reporting(E_ERROR | E_PARSE);
include('./php_files/simpleDom/simple_html_dom.php');
function get_content($url) {
    $options = array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_USERAGENT      => "Mozilla/5.0",
            CURLOPT_COOKIEFILE => "cookie.txt",
    );
    $get = curl_init( $url );
    curl_setopt_array( $get, $options );
    $htmlContent = curl_exec( $get );
    curl_close( $get );
    return $htmlContent;
}
function get_player($url) {
    $options = array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_USERAGENT      => "Mozilla/5.0",
            CURLOPT_COOKIEFILE => "cookie.txt",
    );
    $get = curl_init( $url );
    curl_setopt_array( $get, $options );
    $htmlContent = curl_exec( $get );
    curl_close( $get );
    return $htmlContent;
}
$date= $_POST['date'];
$date_heute = date("Y-m-d");
if ($date === $date_heute){
  $response = get_content('http://www.tennisergebnisse.net/tennis_livescore.php?t=np');
} else {
  $response = get_content('http://www.tennisergebnisse.net/' . $date .'/');
}

//echo htmlspecialchars($response);
//echo $response;
$games_count1 = substr_count($response,'beg');

$games_count = $games_count1 - 3;
$win_rate = 0;
$win_equal = 0;

$win_rate_interesting_game = 0;
$lose_rate_interesting_game = 0;

$interesting_games = 0;


echo '<br>';
echo '<div id="games_overview">';
  echo '<div class="games-headline">';
    echo '<h3>Matches</h3>';
    if ($games_count === -1){
      echo '<h4>Leider sind noch keine Spiele am ' . $date . ' verfügbar</h4>';
    } else {
      echo '<h4>Habe ' . $games_count . ' Spiele am ' . $date . ' gefunden</h4>';
    }
  echo '<img src="./img/hinzufuegen.svg"> ';
  echo '</div>';
echo '<div class="game_compare">';
$string = $response;
$dom = new DOMDocument;
$dom->loadHTML($string);
$xpath = new DOMXPath($dom);
for ($i = 0; $i <= $games_count*2; $i++) {
echo '<div class="match">';
  echo '<div class="games">';
   echo '<div class="player"><p class="playername">' . $xpath->query('//td[@class="match"]')->item($i)->nodeValue . '</p><img class="copy_name" src="img/copy.png" data-playername="' . $xpath->query('//td[@class="match"]')->item($i)->nodeValue . '" alt="copy Playername"></div>';
   $player_link = $xpath->query('//td[@class="match"]/a/@href')->item($i)->nodeValue;
    $player_content = get_player($player_link);
    $string_player = $player_content;
    $dom_player = new DOMDocument;
    $dom_player->loadHTML($string_player);
    $xpath_player = new DOMXPath($dom_player);
    echo '<img class="player_img" src=' . $xpath_player->query('//div[@class="player_photo"]/img/@src')->item(0)->nodeValue . '>';
    echo '<p class="game_wins">' . $xpath_player->query('//tr[@class="footer1"]/td')->item(1)->nodeValue . '</p>';
    echo '<p class="game_ever_played">' . $xpath_player->query('//tr[@class="footer"]/td')->item(1)->nodeValue . '</p>';
    echo '<p class="game_this_year">' . $xpath_player->query('//table[@class="table_stats"]/tr[2]/td')->item(1)->nodeValue . '</p>';
    echo '<p class="game_last_year">' . $xpath_player->query('//table[@class="table_stats"]/tr[3]/td')->item(1)->nodeValue . '</p>';
    echo '<p class="game_result_last">' . $xpath_player->query('//td[@class="w16"]/img/@alt')->item(0)->nodeValue . '</p>';
    echo '<p class="game_result_vor_last">' . $xpath_player->query('//td[@class="w16"]/img/@alt')->item(1)->nodeValue . '</p>';
    echo '<p class="game_result_vor_vor_last">' . $xpath_player->query('//td[@class="w16"]/img/@alt')->item(2)->nodeValue . '</p>';
    echo '<p class="game_result_vor_vor_vor_last">' . $xpath_player->query('//td[@class="w16"]/img/@alt')->item(3)->nodeValue . '</p>';
    echo '<p class="game_result_vor_vor_vor_vor_last">' . $xpath_player->query('//td[@class="w16"]/img/@alt')->item(4)->nodeValue . '</p>';
    echo '<p class="game_result_vor_vor_vor_vor_vor_last">' . $xpath_player->query('//td[@class="w16"]/img/@alt')->item(5)->nodeValue . '</p>';
    echo '<p class="top_rangliste">' . $xpath_player->query('//div[@class="player_stats"]/b')->item(4)->nodeValue . '</p>';
    echo '<a href="' . $player_link . '" class="weiter_zu_player" target="_blank" >Zum Spieler</a>';
    echo '</div>';

    /* Set variables to compare */
    $player_1_win_percent_split = $xpath_player->query('//tr[@class="footer1"]/td')->item(1)->nodeValue;
    $player_1_win_percent_split = explode(' ', $player_1_win_percent_split);
    $player_1_win_percent = $player_1_win_percent_split[0];
    $player_1_win_percent;

    $player_1_check_top = $xpath_player->query('//div[@class="player_stats"]/a')->item(0)->nodeValue;
    if ($player_1_check_top === 'Weltrangliste Herren' || $player_2_check_top === 'Weltrangliste Damen'){
      $player_1_top = $xpath_player->query('//div[@class="player_stats"]/b')->item(4)->nodeValue;
    } else {
      $player_1_top = 2000;
    }

    $player_1_games_ever_split = $xpath_player->query('//tr[@class="footer"]/td')->item(1)->nodeValue;
    $player_1_games_ever_split = explode ('/', $player_1_games_ever_split);
    $player_1_games_ever = $player_1_games_ever_split[0] + $player_1_games_ever_split[1];

    $player_1_wins_this_year_split = $xpath_player->query('//table[@class="table_stats"]/tr[2]/td')->item(1)->nodeValue;
    $player_1_wins_this_year_split = explode('/', $player_1_wins_this_year_split);
    $player_1_wins_this_year = $player_1_wins_this_year_split[0];
    $player_1_loses_this_year = $player_1_wins_this_year_split[1];

    $player_1_games_this_year = $player_1_wins_this_year + $player_1_loses_this_year;
    $player_1_win_rate_this_year = $player_1_games_this_year / $player_1_wins_this_year;
    $player_1_lose_rate_this_year = $player_1_games_this_year / $player_1_loses_this_year;

    $player_1_wins_last_year_split = $xpath_player->query('//table[@class="table_stats"]/tr[3]/td')->item(1)->nodeValue;
    $player_1_wins_last_year_split = explode('/', $player_1_wins_last_year_split);
    $player_1_wins_last_year = $player_1_wins_last_year_split[0];
    $player_1_loses_last_year = $player_1_wins_last_year_split[1];

    $player_1_games_last_year = $player_1_wins_last_year + $player_1_loses_last_year;
    $player_1_win_rate_last_year = $player_1_games_last_year / $player_1_wins_last_year;
    $player_1_lose_rate_last_year = $player_1_games_last_year / $player_1_loses_last_year;

    $player_1_resulst_last = $xpath_player->query('//td[@class="w16"]/img/@alt')->item(0)->nodeValue ;
    $player_1_resulst_vor_last = $xpath_player->query('//td[@class="w16"]/img/@alt')->item(1)->nodeValue;
    $player_1_resulst_vor_vor_last = $xpath_player->query('//td[@class="w16"]/img/@alt')->item(2)->nodeValue;
    $player_1_resulst_vor_vor_vor_last = $xpath_player->query('//td[@class="w16"]/img/@alt')->item(3)->nodeValue;
    $player_1_resulst_vor_vor_vor_vor_last = $xpath_player->query('//td[@class="w16"]/img/@alt')->item(4)->nodeValue;


   $i++;
   echo '<div class="games second">';
   echo '<div class="player"><p class="playername">' . $xpath->query('//td[@class="match"]')->item($i)->nodeValue . '</p><img class="copy_name" src="img/copy.png" data-playername="' . $xpath->query('//td[@class="match"]')->item($i)->nodeValue . '" alt="copy Playername"></div>';
   $player_link = $xpath->query('//td[@class="match"]/a/@href')->item($i)->nodeValue;
    $player_content = get_player($player_link);
    $string_player = $player_content;
    $dom_player = new DOMDocument;
    $dom_player->loadHTML($string_player);
    $xpath_player = new DOMXPath($dom_player);
    echo '<img class="player_img" src=' . $xpath_player->query('//div[@class="player_photo"]/img/@src')->item(0)->nodeValue . '>';
    echo '<p class="game_wins">' . $xpath_player->query('//tr[@class="footer1"]/td')->item(1)->nodeValue . '</p>';
    echo '<p class="game_ever_played">' . $xpath_player->query('//tr[@class="footer"]/td')->item(1)->nodeValue . '</p>';
    echo '<p class="game_this_year">' . $xpath_player->query('//table[@class="table_stats"]/tr[2]/td')->item(1)->nodeValue . '</p>';
    echo '<p class="game_last_year">' . $xpath_player->query('//table[@class="table_stats"]/tr[3]/td')->item(1)->nodeValue . '</p>';
    echo '<p class="game_result_last">' . $xpath_player->query('//td[@class="w16"]/img/@alt')->item(0)->nodeValue . '</p>';
    echo '<p class="game_result_vor_last">' . $xpath_player->query('//td[@class="w16"]/img/@alt')->item(1)->nodeValue . '</p>';
    echo '<p class="game_result_vor_vor_last">' . $xpath_player->query('//td[@class="w16"]/img/@alt')->item(2)->nodeValue . '</p>';
    echo '<p class="game_result_vor_vor_vor_last">' . $xpath_player->query('//td[@class="w16"]/img/@alt')->item(3)->nodeValue . '</p>';
    echo '<p class="game_result_vor_vor_vor_vor_last">' . $xpath_player->query('//td[@class="w16"]/img/@alt')->item(4)->nodeValue . '</p>';
    echo '<p class="game_result_vor_vor_vor_vor_vor_last">' . $xpath_player->query('//td[@class="w16"]/img/@alt')->item(5)->nodeValue . '</p>';
    echo '<p class="top_rangliste">' . $xpath_player->query('//div[@class="player_stats"]/b')->item(4)->nodeValue . '</p>';
    echo '<a href="' . $player_link . '" class="weiter_zu_player" target="_blank">Zum Spieler</a>';
    echo '</div>';

    /* Set variables to compare */
    $player_2_win_percent_split = $xpath_player->query('//tr[@class="footer1"]/td')->item(1)->nodeValue;
    $player_2_win_percent_split = explode(' ', $player_2_win_percent_split);
    $player_2_win_percent = $player_2_win_percent_split[0];
    $player_2_win_percent;

    $player_2_check_top = $xpath_player->query('//div[@class="player_stats"]/a')->item(0)->nodeValue;
    if ($player_2_check_top === 'Weltrangliste Herren' || $player_2_check_top === 'Weltrangliste Damen'){
      $player_2_top = $xpath_player->query('//div[@class="player_stats"]/b')->item(4)->nodeValue;
    } else {
      $player_2_top = 2000;
    }

    $player_2_games_ever_split = $xpath_player->query('//tr[@class="footer"]/td')->item(1)->nodeValue;
    $player_2_games_ever_split = explode ('/', $player_2_games_ever_split);
    $player_2_games_ever = $player_2_games_ever_split[0] + $player_2_games_ever_split[1];

    $player_2_wins_this_year_split = $xpath_player->query('//table[@class="table_stats"]/tr[2]/td')->item(1)->nodeValue;
    $player_2_wins_this_year_split = explode('/', $player_2_wins_this_year_split);
    $player_2_wins_this_year = $player_2_wins_this_year_split[0];
    $player_2_loses_this_year = $player_2_wins_this_year_split[1];

    $player_2_games_this_year = $player_2_wins_this_year + $player_2_loses_this_year;
    $player_2_win_rate_this_year = $player_2_games_this_year / $player_2_wins_this_year;
    $player_2_lose_rate_this_year = $player_2_games_this_year / $player_2_loses_this_year;

    $player_2_wins_last_year_split = $xpath_player->query('//table[@class="table_stats"]/tr[3]/td')->item(1)->nodeValue;
    $player_2_wins_last_year_split = explode('/', $player_2_wins_last_year_split);
    $player_2_wins_last_year = $player_2_wins_last_year_split[0];
    $player_2_loses_last_year = $player_2_wins_last_year_split[1];

    $player_2_games_last_year = $player_2_wins_last_year + $player_2_loses_last_year;
    $player_2_win_rate_last_year = $player_2_games_last_year / $player_2_wins_last_year;
    $player_2_lose_rate_last_year = $player_2_games_last_year / $player_2_loses_last_year;

    $player_2_resulst_last = $xpath_player->query('//td[@class="w16"]/img/@alt')->item(0)->nodeValue ;
    $player_2_resulst_vor_last = $xpath_player->query('//td[@class="w16"]/img/@alt')->item(1)->nodeValue;
    $player_2_resulst_vor_vor_last = $xpath_player->query('//td[@class="w16"]/img/@alt')->item(2)->nodeValue;
    $player_2_resulst_vor_vor_vor_last = $xpath_player->query('//td[@class="w16"]/img/@alt')->item(3)->nodeValue;
    $player_2_resulst_vor_vor_vor_vor_last = $xpath_player->query('//td[@class="w16"]/img/@alt')->item(4)->nodeValue;

    $player_1_points = 0;
    $player_2_points = 0;
    echo '<div class="game_result">';
      if ($player_1_win_percent > $player_2_win_percent) {
        echo "<p>Spieler 1 hat mehr Prozent der Spiele gewonnen</p>";
        $player_1_points++;
      } else {
        echo "<p>Spieler 2 hat mehr Prozent der Spiele gewonnen</p>";
        $player_2_points++;
      }
      if ($player_1_games_ever > $player_2_games_ever){
        echo "<p>Spieler 1 hat mehr Spiele gespielt</p>";
        if ($player_1_games_ever < 200){

        } else {
          $player_1_points++;
          $player_1_points++;
        }
      } else {
        echo "<p>Spieler 2 hat mehr Spiele gespielt</p>";
        if ($player_2_games_ever < 200){

        } else {
          $player_2_points++;
          $player_2_points++;
        }
      }
      if ($player_1_top < $player_2_top){
        echo "<p>Spieler 1 ist niedriger in der Top Rangliste</p>";
        $player_1_points++;
      } else {
        echo "<p>Spieler 2 ist niedriger in der Top Rangliste</p>";
        $player_2_points++;
      }
      if ($player_1_win_rate_this_year < $player_2_win_rate_this_year) {
        echo "<p>Spieler 1 hat mehr % der Spiele dieses Jahr gewonnen</p>";
        $player_1_points++;
      } else {
        echo "<p>Spieler 2 hat mehr % der Spiele dieses Jahr gewonnen</p>";
        $player_2_points++;
      }
      if ($player_1_lose_rate_this_year < $player_2_lose_rate_this_year) {
        echo "<p>Spieler 1 hat mehr % der Spiele dieses Jahr verloren</p>";

      } else {
        echo "<p>Spieler 2 hat mehr % der Spiele dieses Jahr verloren</p>";

      }
      if ($player_1_win_rate_last_year < $player_2_win_rate_last_year) {
        echo "<p>Spieler 1 hat mehr % der Spiele letztes Jahr verloren</p>";

      } else {
        echo "<p>Spieler 2 hat mehr % der Spiele letztes Jahr verloren</p>";

      }
      if ($player_1_win_rate_last_year < $player_2_win_rate_last_year) {
        echo "<p>Spieler 1 hat mehr % der Spiele letztes Jahr gewonnen</p>";
        $player_1_points++;
      } else {
        echo "<p>Spieler 2 hat mehr % der Spiele letztes Jahr gewonnen</p>";
        $player_2_points++;
      }
      $player_1_match_points = 0;
      $player_2_match_points = 0;
      if ($player_1_resulst_last === 'Gewonnen'){
        $player_1_match_points++;
      }
      if ($player_2_resulst_last === 'Gewonnen'){
        $player_2_match_points++;
      }
      if ($player_1_resulst_vor_last === 'Gewonnen'){
        $player_1_match_points++;
      }
      if ($player_2_resulst_vor_last === 'Gewonnen'){
        $player_2_match_points++;
      }
      if ($player_1_resulst_vor_vor_last === 'Gewonnen'){
        $player_1_match_points++;
      }
      if ($player_2_resulst_vor_vor_last === 'Gewonnen'){
        $player_2_match_points++;
      }
      if ($player_1_resulst_vor_vor_vor_last === 'Gewonnen'){
        $player_1_match_points++;
      }
      if ($player_2_resulst_vor_vor_vor_last === 'Gewonnen'){
        $player_2_match_points++;
      }
      if ($player_1_resulst_vor_vor_vor_vor_last === 'Gewonnen'){
        $player_1_match_points++;
      }
      if ($player_2_resulst_vor_vor_vor_vor_last === 'Gewonnen'){
        $player_2_match_points++;
      }



      if ($player_1_match_points > $player_2_match_points){
        $player_1_points++;
      } else {
        $player_2_points++;
      }




      if ($player_1_points > $player_2_points){
        echo "<p>Spieler 1 wird gewinnen </p>";
        echo '<p>Punkte Spieler 1 </p>' . $player_1_points;
        echo "<p>Punkte Spieler 2 </p>" . $player_2_points;
      } elseif ($player_1_points == $player_2_points) {
        echo "<p>Spieler 1 und 2 sind gleich gut</p>";
        echo '<p>Punkte Spieler 1 </p>' . $player_1_points;
        echo "<p>Punkte Spieler 2 </p>" . $player_2_points;
      } else {
        echo "<p>Spieler 2 wird gewinnen </p>";
        echo '<p>Punkte Spieler 1 </p>' . $player_1_points;
        echo "<p>Punkte Spieler 2 </p>" . $player_2_points;
      }
      if ($player_1_points - $player_2_points >= 6){
        echo '<div class="interesting_game" ></div>';
        $interesting_games++;
        $win_rate_interesting_game++;
      } elseif ($player_2_points - $player_1_points >= 6){
        echo '<div class="interesting_game" ></div>';
        $interesting_games++;
        $lose_rate_interesting_game++;
      }
      if ($player_1_points > $player_2_points){
        $win_rate++;
      }
      if ($player_1_points == $player_2_points){
        $win_equal++;
      }

    echo '</div>';
echo '</div>';
}

echo '</div>';
echo '</div>';

if ($date == $date_heute){
  echo '<div class="game_results">';
  echo 'Es wurden ' . $interesting_games . ' interessante Spiele gefunden';
  echo '</div>';
}

if ($date < $date_heute){
  $win_rate_interesting_game_percent = round($win_rate_interesting_game * 100 / $interesting_games, 2);
  echo '<div class="game_results">';
  echo 'Bei Interssanten Spielen wurden ' . $win_rate_interesting_game . ' von ' . $interesting_games . ' richtig getippt -> ' . $win_rate_interesting_game_percent . '%';
  echo '<br>';
  echo $lose_rate_interesting_game . ' waren falsch';
  echo '<br>';
  echo $interesting_games . ' Spiele insgesamt';
  echo '</div>';
}
if ($date < $date_heute){
  $win_rate = $win_rate -1;
  $games_count2 = $games_count - $win_equal;
  $win_rate_all_games_percent= round($win_rate * 100 / $games_count2,2);
  echo '<div class="game_results">';
  echo 'Es wurden ' . $win_rate . ' von ' . $games_count2 . ' richtig getippt -> ' . $win_rate_all_games_percent . '%';
  echo '<br>';
  echo $win_equal . ' waren ausgeglichen';
  echo '<br>';
  echo $games_count . ' Spiele insgesamt';
  echo '</div>';
}

?>
