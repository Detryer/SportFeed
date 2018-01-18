var loginModal = $('#login_modal');
var registerModal = $('#register_modal');
var loginButton = $('#login');
var registerButton = $('#register');

loginButton.on('click', function () {
  loginModal.modal({});
});

registerButton.on('click', function () {
  registerModal.modal({});
});

loginModal.on('show.bs.modal', function () {
  $('#user_login').on('click', function () {
    var formData = new FormData(document.querySelector('#login_form'));
    if(!hasErrors(loginModal)) {
      login(formData);
    }
  });
});

registerModal.on('show.bs.modal', function () {
  $('#password').on('keyup change', function () {
    checkRepeatPassword(registerModal);
  });
  $('#password_repeat').on('keyup change', function () {
    checkRepeatPassword(registerModal);
  });
  $('#user_register').on('click', function () {
    var formData = new FormData(document.querySelector('#register_modal'));
    if(!hasErrors(registerModal)) {
      register(formData);
    }
  });
});

/**
 * Clear form after close
 */
$('.form-modal').on('hide.bs.modal', function () {
  $(this).find('form')[0].reset();
  clearErrors();
});

/**
 * Login user
 * @param data
 */
function login(data) {
  sendAjax({
    url: '/login',
    data: data,
    success: function (response) {
      if (response.errors && response.errors.length > 0) {
        displayErrors(loginModal, response.errors);
      }
      else {
        $('#user_modal').modal('close');
        location.reload();
      }
    }
  });
}

/**
 * Register user
 * @param data
 */
function register(data) {
  sendAjax({
    url: '/register',
    data: data,
    success: function (response) {
      if (response.errors !== undefined && response.errors.length > 0) {
        displayErrors(registerModal, response.errors);
      }
      else {
        login(data);
      }
    }
  });
}

/**
 * Display errors from response
 * @param element
 * @param errors
 */
function displayErrors(element, errors) {
  var errorsBlock = element.find('.modal_errors');
  for (var i = 0; i < errors.length; i++) {
    var type = errors[i].type || errors[i].message;
    var hasSameError = type !== '' && errorsBlock.find('[data-type=' + type + ']').length > 0;
    if (!hasSameError) {
      var errorRow = '<div class="alert alert-danger" data-type="' + type + '">' + errors[i].message + '</div>';
      errorsBlock.append(errorRow);
    }
  }
}

/**
 * Clear form's errors
 * @param element
 */
function clearErrors(element) {
  if (element === undefined) {
    $('.modal_errors').html('');
  } else {
    element.find('.modal_errors').html('');
  }
}

/**
 * Check if form has errors
 * @param element
 */
function hasErrors(element) {
  var errorsCount = element.find('.modal_errors').children().length;
  return errorsCount > 0;
}

/**
 * Ajax request send function
 * @param options
 * @returns {boolean}
 */
function sendAjax(options) {
  if (options.url === undefined) {
    console.error('Internal server error 500');
    return false;
  }
  $.ajax({
    type: options.type || "POST",
    url: options.url,
    data: options.data || {},
    dataType: 'JSON',
    processData: false,
    success: options.success || null,
    error: options.error || null
  });
}

/**
 * Check for password and password repeat match
 * @param element
 */
function checkRepeatPassword(element) {
  var form = $(element).find('form');
  var password = form.find('#password').val();
  var passwordRepeat = form.find('#password_repeat').val();
  if (password !== passwordRepeat && passwordRepeat.length !== 0) {
    displayErrors(form, [{
      message: 'Passwords does not match!',
      type: 'passwords_does_not_match'
    }]);
  }
  else {
    clearErrors(form);
  }
}