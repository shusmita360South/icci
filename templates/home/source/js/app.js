$(document).ready(function() {
    
    
    // ===========================================================
    // Contact form Validation
    // ===========================================================
    $("#contact-form").validate();


   

    // ===========================================================
    // magnific popup
    // Video popup
    // ===========================================================
    

    $('.video-btn').magnificPopup({
      type: 'iframe',
      
      
      iframe: {
         markup: '<div class="mfp-iframe-scaler">'+
                    '<div class="mfp-close"></div>'+
                    '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>'+
                    '<div class="mfp-title">Some caption</div>'+
                  '</div>'
      },
      callbacks: {
        markupParse: function(template, values, item) {
         values.title = item.el.attr('title');
        }
      }
  
  
    });
   
   
    // ===========================================================
    // animation on url hash element
    // ===========================================================
    
    $(".scroll-animation").on('click', function(event) {

        // Make sure this.hash has a value before overriding default behavior
        if (this.hash !== "") {

            // Store hash
            var hash = this.hash;

            // Using jQuery's animate() method to add smooth page scroll
            // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
            $('html, body').animate({
                scrollTop: $(hash).offset().top
                }, 500, function() {

                // Add hash (#) to URL when done scrolling (default click behavior)
                window.location.hash = hash;
            });
            return false;
        } // End if
    });
    if ($(window.location.hash).length > 0) {
        var hash = window.location.hash;

        window.location.hash = "";

        $('html, body').animate({
            scrollTop: $(hash).offset().top - 0
        }, 500, function() {
            // Add hash (#) to URL when done scrolling (default click behavior)
            //window.location.hash = hash;
        });
    }   

    

    

    //=============================================================
    //================Mobile menu===============================
    //=============================================================

    $('.uk-offcanvas .deeper').on('click', function(event) {

        $(this).toggleClass('expanded');
        $(this).find('.nav-child').toggleClass('hide');
    });


    //=============================================================
    //================Project Filter==============================
    //=============================================================

    $('#filter-btn .filter-select-catid').on('change', function() {
        var filterClass =$(this).val();
        $('.filter-original .uk-subnav li#catid-'+filterClass).trigger( "click" );
        $('.filter span#catid-'+filterClass).trigger( "click" );
    });

    $('#filter-btn .filter-select-type').on('change', function() {
        var filterClass =$(this).val();
        $('.filter-original .uk-subnav li#type-'+filterClass).trigger( "click" );
        $('.filter span#type-'+filterClass).trigger( "click" );
    });

    $('#filter-btn .filter-select-days').on('change', function() {
        var filterClass =$(this).val();
        $('.filter-original .uk-subnav li#day-'+filterClass).trigger( "click" );
        $('.filter span#day-'+filterClass).trigger( "click" );
    });
    
   
    //=============================================================
    //===============Gallery slider==============================
    //=============================================================
    $('.image-gallery .owl-carousel').owlCarousel({
        loop:true,
        margin:40,
        responsiveClass:true,
        nav: true,
        responsive:{
            0:{
                items:1,
                nav:true
            },
            600:{
                items:2,
                nav:true
            },
            800:{
                items:2,
                nav:true
            },
            1000:{
                items:2,
                nav:true,
                loop:false
            }
        }
    });

    //=============================================================
    //===============event slider==============================
    //=============================================================
    var $slider = $('.slick');
    var $progressBar = $('.progress');
    var $progressBarLabel = $( '.slider__label' );
      
      $slider.on('beforeChange', function(event, slick, currentSlide, nextSlide) {   
        var calc = ( (nextSlide) / (slick.slideCount-1) ) * 100;
        
        $progressBar
          .css('background-size', calc + '% 100%')
          .attr('aria-valuenow', calc );
        
        $progressBarLabel.text( calc + '% completed' );
    });
    $('.slick').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,
        infinite: true,
    });
     
      
    $('form.filter span').on('click', function() {
        var filterClass = $(this).data('value');
        $('.filter-class').text(filterClass);
        $('.slick').slick('slickUnfilter');
        $('.slick').slick('slickFilter', filterClass);
    });
      
  




});

// ===========================================================
// Image object-fit solution for IE
// ===========================================================

function msieversion() {
    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE ");
    
    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
        if ( ! Modernizr.objectfit ) {
            $('.item-image').each(function () {
                var $container = $(this),
                    imgUrl = $container.find('img').prop('src');
                if (imgUrl) {
                    $container
                        .css('background-image', 'url(' + imgUrl + ')')
                        .addClass('compat-object-fit');
                }
            });
           
        }
    }
    return false;
} 
$(document).ready(msieversion);
function isMobile(width) {
    if(width == undefined){
        width = 719;
    }
    if(window.innerWidth <= width) {
        return true;
    } else {
        return false;
    }
}
