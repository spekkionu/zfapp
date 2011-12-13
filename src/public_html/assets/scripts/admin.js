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
                .closest('.clearfix').removeClass(validClass).addClass(errorClass);
    },
    unhighlight: function(element, errorClass, validClass) {
      // Remove error classes to element and container
     $(element).removeClass(errorClass).closest('.clearfix').removeClass(errorClass);
    },
    submitHandler: function(form) {
      // Disable submit buttons
      $(form).find('input:submit').button('disable');
      // Submit Form
      form.submit();
    }
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