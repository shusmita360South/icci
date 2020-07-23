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

    $('.project-list .filter-select').on('change', function() {
        var filterClass =$(this).val();
        if (filterClass == "") {
            $('.project-item').show();
        } else {
            $('.project-item').hide();
            $('.project-item.'+filterClass).show();
        }  
        var otherSelectBox = $(".project-list .filter-select").not(this);
            
        if (filterClass != "") {
            otherSelectBox.addClass('otherSelectBox');
            $(this).removeClass('otherSelectBox');
            setTimeout(function(){
                $('.otherSelectBox').prop('selectedIndex',0);
                
            }, 100);
        }

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