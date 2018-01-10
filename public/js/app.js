$('#login').on('click', function () {
  $('#login_modal').modal({});
});

$('#login_modal').on('show.bs.modal', function () {
  $('#user_login').on('click', function () {
    var formData = new FormData(document.querySelector('#login_form'));
    sendAjax({
      url: '/login',
      data: formData,
      success: function (response) {
        if (response.errors !== undefined && response.errors.length > 0) {
          displayErrors($('#login_modal'), response.errors);
        } else {
          $('#user_modal').modal('close');
          location.reload();
        }
      }
    });
  });
});

/**
 * Display errors from response
 * @param element
 * @param errors
 */
function displayErrors(element, errors) {
  for (var i = 0; i < errors.length; i++) {
    var errorRow = '<div class="alert-danger">' + errors[i].message + '</div>';
    element.find('.modal_errors').append(errorRow);
  }
}

/**
 * Ajax request send function
 * @param options
 * @returns {boolean}
 */
function sendAjax(options) {
  if (options.url === undefined) {
    console.error('No ajax url specified!');
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