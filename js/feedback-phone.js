(function () {
	var phonePattern = /^\+7-\d{3}-\d{3}-\d{2}-\d{2}$/;
	var prefix = '+7-';

	function formatPhone(value) {
		var digits = String(value || '').replace(/\D/g, '');
		if (digits.charAt(0) === '7' || digits.charAt(0) === '8') {
			digits = digits.substring(1);
		}
		digits = digits.substring(0, 10);

		var result = prefix;
		if (digits.length > 0) {
			result += digits.substring(0, 3);
		}
		if (digits.length > 3) {
			result += '-' + digits.substring(3, 6);
		}
		if (digits.length > 6) {
			result += '-' + digits.substring(6, 8);
		}
		if (digits.length > 8) {
			result += '-' + digits.substring(8, 10);
		}
		return result;
	}

	function isValidPhone(value) {
		return phonePattern.test(value);
	}

	function showPhoneError(input, message) {
		var row = input.closest('.row');
		if (!row) {
			return;
		}
		row.classList.add('row--error');
		var parent = input.parentNode;
		var error = parent.querySelector('.field-error--js');
		if (!error) {
			error = document.createElement('span');
			error.className = 'field-error field-error--js';
			parent.appendChild(error);
		}
		error.textContent = message;
	}

	function clearPhoneError(input) {
		var row = input.closest('.row');
		if (row) {
			row.classList.remove('row--error');
		}
		var parent = input.parentNode;
		if (parent) {
			var error = parent.querySelector('.field-error--js');
			if (error) {
				error.parentNode.removeChild(error);
			}
		}
	}

	function initPhoneMask(input) {
		if (!input || input.dataset.phoneMask) {
			return;
		}
		input.dataset.phoneMask = '1';

		if (input.value) {
			input.value = formatPhone(input.value);
		}

		input.addEventListener('focus', function () {
			if (!input.value) {
				input.value = prefix;
			}
		});

		input.addEventListener('blur', function () {
			if (input.value === prefix || input.value === '+7') {
				input.value = '';
			}
			if (input.value && !isValidPhone(input.value)) {
				showPhoneError(input, 'Укажите телефон в формате +7-___-___-__-__.');
			}
		});

		input.addEventListener('keydown', function (e) {
			var pos = input.selectionStart;
			if ((e.key === 'Backspace' || e.key === 'Delete') && pos <= 3) {
				e.preventDefault();
			}
		});

		input.addEventListener('input', function () {
			var formatted = formatPhone(input.value);
			input.value = formatted;
			if (isValidPhone(formatted)) {
				clearPhoneError(input);
			}
			var len = formatted.length;
			try {
				input.setSelectionRange(len, len);
			} catch (e) {}
		});

		input.addEventListener('paste', function () {
			setTimeout(function () {
				var formatted = formatPhone(input.value);
				input.value = formatted;
				if (!isValidPhone(formatted)) {
					showPhoneError(input, 'Укажите телефон в формате +7-___-___-__-__.');
				}
			}, 0);
		});
	}

	function validatePhoneField(input) {
		if (!isValidPhone(input.value)) {
			showPhoneError(input, 'Укажите телефон в формате +7-___-___-__-__.');
			input.focus();
			return false;
		}
		clearPhoneError(input);
		return true;
	}

	function initFeedbackPhone() {
		var form = document.getElementById('ffeed');
		if (!form) {
			return;
		}

		var phone = form.querySelector('input[name=phone]');
		if (!phone) {
			return;
		}

		initPhoneMask(phone);

		form.addEventListener('submit', function (e) {
			if (!validatePhoneField(phone)) {
				e.preventDefault();
				return false;
			}
		});

		var sendButtons = document.querySelectorAll('a.btn-send');
		for (var i = 0; i < sendButtons.length; i++) {
			sendButtons[i].addEventListener('click', function (e) {
				if (!validatePhoneField(phone)) {
					e.preventDefault();
					return false;
				}
			});
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initFeedbackPhone);
	} else {
		initFeedbackPhone();
	}
})();
