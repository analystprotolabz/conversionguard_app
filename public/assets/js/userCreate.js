$(document).ready(function () {

  window.addEventListener("DOMContentLoaded", (event) => {
    // toaster intilisation
    toastr.options = {
      closeButton: false,
      debug: false,
      newestOnTop: false,
      progressBar: true,
      positionClass: "toast-top-right",
      preventDuplicates: false,
      showDuration: "500",
      hideDuration: "500",
      timeOut: "4000",
      showEasing: "swing",
      hideEasing: "linear",
      showMethod: "fadeIn",
      hideMethod: "fadeOut",
    };
  });

  jQuery(document).on('click', '#submit', function (e) {
    e.preventDefault();
    const domain = jQuery('#domain').val();
    const first_name = jQuery('#first_name').val();
    const email = jQuery('#email').val();
    const phone = jQuery('#phone_number').val();
    var chk_policy = $("#policy").prop('checked');

    $(".error").remove();
    if (first_name.length < 1) {
      // alert("tested..")
      $('#first_name').after('<span class="error">This field is required</span>');
    }
    if (domain.length < 1) {
      $('#domain').after('<span class="error">This field is required</span>');
    }
    if (phone.length < 1) {
      $('#phone_number').after('<span class="error">This field is required</span>');
    }
    if (!chk_policy) {
      $('#policy').after('<span class="error">Please agree the terms and conditions before submit the form</span>');
    }

    if (email.length < 1) {
      $('#email').after('<span class="error">This field is required</span>');
    }
    // else {
    //   var regEx = /^[A-Z0-9][A-Z0-9._%+-]{0,63}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/;
    //   var validEmail = regEx.test(email);
    //   if (!validEmail) {
    //     $('#email').after('<span class="error">Enter a valid email</span>');
    //   }
    // }

    jQuery.ajax({
      method: "POST",
      url: "/userCreate",
      data: {
        domain: domain, first_name: first_name, email: email, phone: phone
      },
      beforeSend: function () {
        jQuery('.loader').addClass('__showloader');
      },
      success: function (response) {
        jQuery('.loader').removeClass('__showloader');
        console.log("shop_id =>" + response.shop_id);
        if (response.status == 1) {
          toastr["success"](response.success_message, "Success");
          window.location = "/pixel-page";
        }
        else if (response.status == 2) {
          toastr["error"](response.error_message, "Error");
        }
        else {
          toastr["error"](response.error_message, "Error");
        }

      }
    });
  });

  // product page pixel enable call 
    $("#product-page").click(function () {
      if ($(this).is(':checked')) {
        alert("checked");
        $(this).attr('value', 'true');
        $.ajax({
          url: '/productSnippetCreate',
          type: 'get',
          data: {},
          beforeSend: function () {
            jQuery('.loader').addClass('__showloader');
          },
          success: function (response) {
            jQuery('.loader').removeClass('__showloader');
            toastr[response.status](response.message);
          },
        });
      } else {
        $(this).attr('value', 'false');
        $.ajax({
          url: '/productSnippetDelete',
          type: 'get',
          data: {},
          beforeSend: function () {
            jQuery('.loader').addClass('__showloader');
          },
          success: function (response) {
            jQuery('.loader').removeClass('__showloader');
            toastr[response.status](response.message);
          },
        });
      }
    });

  // category page pixel enable call 
  $("#category-page").click(function() {
    if ($(this).is(':checked')) {
      $(this).attr('value', 'true');
      $.ajax({
        url: '/categorySnippetCreate',
        type: 'get',
        data: {},
        beforeSend: function () {
          jQuery('.loader').addClass('__showloader');
        },
        success: function (response) {
          jQuery('.loader').removeClass('__showloader');
          toastr[response.status](response.message);
        },
      });
    } else {
      $(this).attr('value', 'false');
      $.ajax({
        url: '/categorySnippetDelete',
        type: 'get',
        data: {},
        beforeSend: function () {
          jQuery('.loader').addClass('__showloader');
        },
        success: function (response) {
          jQuery('.loader').removeClass('__showloader');
          toastr[response.status](response.message);
        },
      });
    }
  });

 // checkout page pixel enable call 
 $("#checkout").click(function() {
  if ($(this).is(':checked')) {
    $(this).attr('value', 'true');
    $.ajax({
      url: '/checkoutSnippetCreate',
      type: 'get',
      data: {},
      beforeSend: function () {
        jQuery('.loader').addClass('__showloader');
      },
      success: function (response) {
        jQuery('.loader').removeClass('__showloader');
        toastr[response.status](response.message);
      },
    });
  } else {
    $(this).attr('value', 'false');
    $.ajax({
      url: '/checkoutSnippetDelete',
      type: 'get',
      data: {},
      beforeSend: function () {
        jQuery('.loader').addClass('__showloader');
      },
      success: function (response) {
        jQuery('.loader').removeClass('__showloader');
        toastr[response.status](response.message);
      },
    });
  }
});

 //page view pixel enable call 

 $("#page-view").click(function() {
  if ($(this).is(':checked')) {
    $(this).attr('value', 'true');
    $.ajax({
      url: '/pageViewSnippetCreate',
      type: 'get',
      data: {},
      beforeSend: function () {
        jQuery('.loader').addClass('__showloader');
      },
      success: function (response) {
        jQuery('.loader').removeClass('__showloader');
        toastr[response.status](response.message);
      },
    });
  } else {
    $(this).attr('value', 'false');
    $.ajax({
      url: '/pageViewSnippetDelete',
      type: 'get',
      data: {},
      beforeSend: function () {
        jQuery('.loader').addClass('__showloader');
      },
      success: function (response) {
        jQuery('.loader').removeClass('__showloader');
        toastr[response.status](response.message);
      },
    });
  }
});


 //global guard pixel enable call 

 $("#global-guard").click(function() {
  if ($(this).is(':checked')) {
    $(this).attr('value', 'true');
    $.ajax({
      url: '/globalGuardSnippetCreate',
      type: 'get',
      data: {},
      beforeSend: function () {
        jQuery('.loader').addClass('__showloader');
      },
      success: function (response) {
        jQuery('.loader').removeClass('__showloader');
        toastr[response.status](response.message);
      },
    });
  } else {
    $(this).attr('value', 'false');
    $.ajax({
      url: '/globalGuardSnippetDelete',
      type: 'get',
      data: {},
      beforeSend: function () {
        jQuery('.loader').addClass('__showloader');
      },
      success: function (response) {
        jQuery('.loader').removeClass('__showloader');
        toastr[response.status](response.message);
      },
    });
  }
});


});
