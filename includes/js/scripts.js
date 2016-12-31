$(document).ready(function(){
  // overlay menu ==============================================================
  $('.menu-button').on('click', function(){
    $('#menu-overlay').fadeIn();
  });

  $('#close-menu').on('click', function(){
    $('#menu-overlay').fadeOut();
  });

  $('#close-bet-success').on('click', function(){
    $('#ufc-bets-success').fadeOut(600);
  });



  // betting page ==============================================================

  // on click change background
  $('[class^="fight-"]').on('click', function(event){
    $(this).addClass("bet-fighter-selected").siblings().removeClass("bet-fighter-selected");
    var target_id = event.target.id;
    var split = target_id.split('-');
    var fight_id = split[0];
    var selection = split[1];
    $('input[id=' + fight_id + ']').val(selection);
  });
});
