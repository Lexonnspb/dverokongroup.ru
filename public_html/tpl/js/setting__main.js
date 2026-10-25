$(document).ready(function () {
  
  'use strict';
  
  /**
   * countTo
   */
  //(function () {
  //
  //  $('#js-ads').on('inview', function () {
  //    $('.js-count').countTo();
  //  });
  //
  //})();
  
  $('#js-ads').on('inview', function () {
    $('.js-count').countTo();
    console.log('inview');
  });
  
  
  /**
   * js-discount
   */
  (function () {
  
    $('#js-discount').on('inview', function () {
      $('.js-discount').countTo();
    });
  
    $('#js-discountGet').click(function () {
    
      var discountModal  = UIkit.modal('#discount', {
            //offset: 50
          }),
          discount       = $('.js-discount'),
          diascountTotal = $(discount).text();
    
      $(discount).countTo('stop');
    
      $('.discount-block').removeClass('uk-hidden');
      $('.discount-total').text(diascountTotal);
      $('#discount-total').val(diascountTotal);
    
      discountModal.show();
    });
  
  })();
  
});
