/**
 * Collapsible Container plugin
 * @uses jQuery-UI accordion
 *
 */
(function( $ ){

  $.fn.collapsible = function(options) {

    var settings = $.extend({
      collapsible: true,
      autoHeight: true,
      persist: false,
      cookieId: 'jquery.collapsible'
    }, options);

    return this.each(function() {
      var $el = $(this);
      var opt = $.extend(settings, {
        collapsible: true,
        change: function(event, ui){
          if(settings.persist || $el.data('persist') || $el.hasClass('persist')){
            var state = ($el.accordion('option', 'active') === false) ? 'collapsed' : 'expanded';
            if($el.data('cookie')){
              var cookieId = settings.cookieId + ':' + $el.data('cookie');
            }else{
              var cookieId = settings.cookieId;
            }
            $.cookie(cookieId, state, settings.cookieOptions );
          }
        }
      });
      if($el.data('collapsed') || $el.hasClass('collapsed')){
        opt.active = false
      }
      if(settings.persist || $el.data('persist') || $el.hasClass('persist')){
        // use cookies to persist state
        if($el.data('cookie')){
          var cookieId = settings.cookieId + ':' + $el.data('cookie');
        }else{
          var cookieId = settings.cookieId;
        }
        var stored = $.cookie(cookieId);
        if(stored === 'collapsed'){
          opt.active = false;
        }else if(stored === 'expanded'){
          opt.active = null;
        }
      }
      $el.accordion(opt);
    });


  };
})( jQuery );