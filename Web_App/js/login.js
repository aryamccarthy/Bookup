$(document).ready( function() {

  $('#register').click( function(event) {
    event.preventDefault();
    console.log("Hello!");
    // Validate fields. If it's wrong, this will run.
    if (! $('#login')[0].checkValidity()) {
      console.log('invalid');
      $('#login_button').click();
    }
    else {
      var email = $('#email').val();
      var password = $('#password').val();
      checkIfUsernameTaken(email, password);
    }
  }); //end of register.click()

}); //end of document.ready()

window.onload = function() {
  if($('#loginerrors').data('err')) {
    var errtext = $('#loginerrors').data('err');
    sweetAlert({
      title: "Whoops.",
      text: errtext,
      type: "error",
    });
  }
};

function checkIfUsernameTaken(email, password) {
  $.ajax({
    type: 'GET',
    url: rootURL + '/' + 'userExists' + '/' + email,
    dataType: "json",
    success: function (data) {
      console.log('got response');
      if (data.success === false || data.exists === true) {
        DBErrorNotifier(data);
        return;
      }
      else {
        console.log('user does not exist');
        addUser(email, password);
      }
    },
    error: APIErrorHandler
  });
}

function addUser(email, password) {
  console.log('adding user');
  console.log(email + ' ' + password);
  $.ajax({
    type: 'POST',
    url: rootURL + "/addUser",
    data: {email: email, password: password},
    success: function (data) {
      if (data.success === false) {
        DBErrorNotifier(data);
        return;
      }
      else {
        // PERFORM LOGIN-Y THINGS HERE.
        console.log('user added');
        $('#login_button').click();
      }
    },
    error: APIErrorHandler
  });
}