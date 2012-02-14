// Setup Admin JS
jQuery(document).ready(function($){
  // Set metadata to be loaded from html5 data attibutes
  $.metadata.setType('attr','data');
  // Set validation defaults
  $.validator.setDefaults({
    meta: "validate",
    ignoreTitle: true,
    errorElement: 'li',
    errorClass: "error",
    errorPlacement: function(error, element) {
      // Add error messages to DOM
      var container = element.parent('div');
      var error_ul = container.find('ul.errors').eq(0);
      if(!error_ul.length){
        container.append( $('<ul class="errors"></ul>') );
        var error_ul = container.find('ul.errors').eq(0);
      }
      error_ul.html(error);
    },
    highlight: function(element, errorClass, validClass) {
      // Add error classes to element and container
      $(element).removeClass(validClass).addClass(errorClass)
                .closest('.control-group').removeClass(validClass).addClass(errorClass);
    },
    unhighlight: function(element, errorClass, validClass) {
      // Remove error classes to element and container
     $(element).removeClass(errorClass).closest('.control-group').removeClass(errorClass);
    },
    submitHandler: function(form) {
      // Disable submit buttons
      $(form).find('input:submit').addClass('disabled').attr('disabled','disabled');
      // Submit Form
      form.submit();
    }
  });
  $('.collapsable[data-cookie]').on('shown', function (e) {
    $el = $(this);
    if($el.data('cookie')){
      var cookieId = 'bootstrap.accordion:' + $el.data('cookie');
    }else{
      var cookieId = 'bootstrap.accordion';
    }
    $.cookie(cookieId, 'expanded', {path: 'admin'} );
  });
  $('.collapsable[data-cookie]').on('hidden', function (e) {
    $el = $(this);
    if($el.data('cookie')){
      var cookieId = 'bootstrap.accordion:' + $el.data('cookie');
    }else{
      var cookieId = 'bootstrap.accordion';
    }
    $.cookie(cookieId, 'collapsed', {path: 'admin'} );
  });
  // Javascript Validation
  $('form.validate').attr('novalidate', 'novalidate').validate();
  $(document).delegate('form.validate .btn.cancel', 'click', function(e){
    e.stopPropagation();
  });
  $("form.validate .form-element-password input:password").keyup(function() {
    $(this).valid();
  });
});