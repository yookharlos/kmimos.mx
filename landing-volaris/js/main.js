$(window).load(function() {
	// $('#section-1').height( $(window).height() - 1  );
	// $('#section-6').height( $(window).height() - 1  );	
   video_iframe();
});

$(window).resize(function(){
  video_iframe();
});

function video_iframe(){

  if($(window).width()>800){  
    $('iframe').width( ($(window).width() * 50) / 100 );
    $('iframe').height( ($(window).height() * 55) / 100 );
  }
  if($(window).width()<799){  
    $('iframe').width( ($(window).width() * 60) / 100 );
    $('iframe').height( ($(window).height() * 55) / 100 );
  }
  if($(window).width()<500){  
    $('iframe').width( ($(window).width() * 90) / 100 );
    $('iframe').height( ($(window).height() * 55) / 100 );
  }


}

var wow = new WOW(
  {
    boxClass:     'wow',      // animated element css class (default is wow)
    animateClass: 'animated', // animation css class (default is animated)
    offset:       0,          // distance to the element when triggering the animation (default is 0)
    mobile:       true,       // trigger animations on mobile devices (default is true)
    live:         true,       // act on asynchronously loaded content (default is true)
    callback:     function(box) {
      // the callback is fired every time an animation is started
      // the argument that is passed in is the DOM node being animated
    },
    scrollContainer: null // optional scroll container selector, otherwise use window
  }
);
wow.init();

$('#newsletter').on('click', function(){

  if( $('#email').val() != ''){

    $.ajax( "/landing/newsletter.php?source=landing_volaris&email="+$('#email').val() )
    .done(function(data) {


      if( data == 1){
        $('#mensaje').html('<i class="fa fa-envelope" aria-hidden="true"></i> Datos guardados');
      }else if( data == 2){
        $('#mensaje').html('<i class="fa fa-envelope" aria-hidden="true"></i> Formato de email invalido');
      }else if( data == 3){
        $('#mensaje').html('<i class="fa fa-envelope" aria-hidden="true"></i> Ya estas registrado en la lista, Gracias!');
      }else{
        $('#mensaje').html('<i class="fa fa-envelope" aria-hidden="true"></i> Error al guardar los datos');
      }

      $('#loading').addClass('hidden');
      $('#email').attr('value', '');
      $("#msg-content").addClass('fadeInDown animated');
      $("#msg-content").css('display', 'inline-block');

      setTimeout(function() {
        $("#msg-content").removeClass('fadeInDown animated');
        $("#msg-content").removeClass('fadeOut animated');
        $("#msg-content").fadeOut(1500);
      },3000);



    })
    .fail(function() {});

  }

});