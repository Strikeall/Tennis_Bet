<!DOCTYPE html>
<head>
<title>Tennis Bet</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link href="/style.css" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
</head>



<header>
  <div class="header_nav">
    <div class="logo">
      <a class="logo_img_text" href="#"><img src="img/glasses.png" alt="logo">Watch your bet</a>
    </div>
    <div class="nav_area">
      <a class="nav_point" href="#">Login</a>
    </div>
  </div>
</header>
<body>
  <div class="search-overview">
    <div class="search_box">
      <h2>Vergleiche Tennisspiele</h2>
      <input type="date" id="search_date" class="inputfield-date" value="">
      <a id="searchbutton" onclick="search_matches()" class="button">Suchen</a>
      <div id="loader" title="1">
        <p>Das kann einen Moment dauern</p>
        <svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
       width="40px" height="40px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
        <path d="M25.251,6.461c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615V6.461z">
      <animateTransform attributeType="xml"
        attributeName="transform"
        type="rotate"
        from="0 25 25"
        to="360 25 25"
        dur="0.6s"
        repeatCount="indefinite"/>
      </path>
        </svg>
      </div>
    </div>
  </div>
  <div id="get_games"></div>

</body>

<footer>


</footer>
<script>
const node = document.getElementById('search_date');
node.addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
        event.preventDefault();

        search_matches();

    }
});

function search_matches(){
  document.getElementById('get_games').innerHTML = "";
  document.getElementsByClassName('search-overview')['0'].style.height = "70vh"
  document.getElementById('loader').style.display = "flex";

  var post_date = document.getElementById('search_date').value;
  $.ajax({
  url: "get_player.php",
  type: "POST",
  data: {date: post_date},
  success: function(response) {
    document.getElementById('loader').style.display = "none";
    document.getElementsByClassName('search-overview')['0'].style.height = "30vh"
    document.getElementById('get_games').innerHTML = response;
    // Matches ausklappen
    $(".games-headline img").click(function(){
      $(".game_compare").toggleClass("active");
      $(this).toggleClass('active');
    });
    // Copy Player copy_name
    $(".copy_name").click(function(){
      var copy_text = $(this).data('playername');
      navigator.clipboard.writeText(copy_text).then(function() {
      }, function(err) {
      });
    });

  },
  error: function(xhr) {
    alert('error');
  }
});
}




</script>
