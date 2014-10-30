$(document).ready( function() {

  $('#register_user').click( function(event) {

  }); //end of register_user.click()

  $('#testlogin').click( function(event) {
    $('#login_input_email').val("ebusbee@bookup.com");
    $('#login_input_password').val("Candles");
    $('#login_user').click();
  }); //end of testlogin.click()

}); //end of document.ready()
