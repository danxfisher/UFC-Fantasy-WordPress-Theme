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
  $('[class^="col-xs-5 fight-"]').on('click', function(event){
    $(this).addClass("bet-fighter-selected").siblings().removeClass("bet-fighter-selected");

    var target_id = this.id;
    var split = target_id.split('-');
    var fight_id = split[0];
    var selection = split[1];
    console.log('target_id: ' + target_id);
    console.log('fight_id: ' + fight_id);
    console.log('selection: ' + selection);
    $('input[id=' + fight_id + ']').val(selection);
  });
});
