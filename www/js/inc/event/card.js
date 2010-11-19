js.module("inc.event.card");
js.include("jquery.ui.tabs");
/*
$(function() {
  $('div.tabs').each(function() {
    $(this).find('div.tab').each(function(i) {
      $(this).click(function(){
        $st = window.scrollY;
        $(this).addClass('current').siblings().removeClass('current')
          .parents('div.card_tabs_box').find('div.box').hide().end().find('div.box:eq('+i+')').fadeIn(150);
        window.scrollTo(0, $st);
      });
    });
  });
})
*/