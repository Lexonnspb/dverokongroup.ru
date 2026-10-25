var AjaxForm = {
	initialize: function (afConfig) {
		$(document).on('submit', afConfig['formSelector'], function (e) {
			$(this).ajaxSubmit({
				dataType:        'json',
				data:            {pageId: afConfig['pageId']},
				url:             afConfig['actionUrl'],
				beforeSerialize: function (form) {
					form.find(':submit').each(function () {
						if (!form.find('input[type="hidden"][name="' + $(this).attr('name') + '"]').length) {
							$(form).append(
								$('<input type="hidden">').attr({
									name:  $(this).attr('name'),
									value: $(this).attr('value')
								})
							);
						}
					});
				},
				beforeSubmit:    function (fields, form) {
					//noinspection JSUnresolvedVariable
					if (typeof(afValidated) != 'undefined' && afValidated == false) {
						return false;
					}
					form.find('.uk-form-danger').html('');
					form.find('.uk-form-danger').removeClass('uk-form-danger');
					form.find('input,textarea,select,button').attr('disabled', true);
					return true;
				},
				success:         function (response, status, xhr, form) {
					form.find('input,textarea,select,button').attr('disabled', false);
					response.form = form;
					$(document).trigger('af_complete', response);
					if (!response.success) {
						AjaxForm.Message.error(response.message);
						if (response.data) {
							var key, value, focused;
							for (key in response.data) {
								if (response.data.hasOwnProperty(key)) {
									if (!focused) {
										form.find('[name="' + key + '"]').focus();
										focused = true;
									}
									value = response.data[key];
									form.find('.error_' + key).html(value).addClass('uk-form-danger');
									form.find('[name="' + key + '"]').addClass('uk-form-danger');
								}
							}
						}
					}
					else {
						AjaxForm.Message.success(response.message);
						form.find('.uk-form-danger').removeClass('uk-form-danger');
						form.find('.uk-form-success').removeClass('uk-form-success');
						form[0].reset();
						//noinspection JSUnresolvedVariable
						if (typeof(grecaptcha) != 'undefined') {
							//noinspection JSUnresolvedVariable
							grecaptcha.reset();
						}
					}
				}
			});
			e.preventDefault();
			return false;
		});
		
		$(document).on('keypress change', '.uk-form-danger', function () {
			var key = $(this).attr('name');
			$(this).removeClass('uk-form-danger');
			$('.error_' + key).html('').removeClass('uk-form-danger');
		});
		
		$(document).on('reset', afConfig['formSelector'], function () {
			$(this).find('.uk-form-danger').html('');
		});
	}
	
};

//noinspection JSUnusedGlobalSymbols
AjaxForm.Message = {
	success: function (message) {
		if (message) {
			UIkit.notification({
				message: message,
				status:  'success',
				pos:     'top-center',
				timeout: 5000
			});
			//UIkit.modal('.uk-modal')[0].hide();
			$(function() {
        var modal = UIkit.modal('.uk-form-modal');
        modal.hide();
      });
		}
	},
	error:   function (message) {
		if (message) {
			UIkit.notification({
				message: message,
				status:  'danger',
				pos:     'top-center',
				timeout: 3000
			});
		}
	},
	info:    function (message) {
		if (message) {
			UIkit.notification({
				message: message,
				status:  'info',
				pos:     'top-center',
				timeout: 3000
			});
		}
	}
	
};
