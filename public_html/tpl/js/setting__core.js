$(document).ready(function () {
  
  'use strict';
  
  // Скрыть прелоадер при загрузке страницы
  $('.preloader').fadeOut(1000);
  
  // Маска для телефона
  jQuery(function ($) {
    $('.phone').mask('+7 (999) 999-99-99');
  });
  
  // Lozad
  //var el       = document.elem.getElementsByClassName('js-lozad');
  //var observer = lozad(el); // passing a `NodeList` (e.g. `document.querySelectorAll()`) is also valid
  //observer.observe();
  
  var observer = lozad('.js-lozad');
  observer.observe();
  
});