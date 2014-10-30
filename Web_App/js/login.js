$(document).ready( function() {

  $('#register_user').click( function(event) {

  }); //end of register_user.click()

  $('#testlogin').click( function(event) {
    $('#email').val("marf@bark.com");
    $('#password').val("marflebark");
    $('#login_button').click();
  }); //end of testlogin.click()

}); //end of document.ready()
