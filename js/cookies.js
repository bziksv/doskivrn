(function () {
	var COOKIE_NAME = 'doskivrn_cookie_consent';
	var COOKIE_DAYS = 365;

	function getCookie(name) {
		var match = document.cookie.match(new RegExp('(^|;\\s*)' + name + '=([^;]*)'));
		return match ? decodeURIComponent(match[2]) : null;
	}

	function setCookie(name, value, days) {
		var date = new Date();
		date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
		document.cookie = name + '=' + encodeURIComponent(value) +
			'; expires=' + date.toUTCString() +
			'; path=/' +
			'; SameSite=Lax';
	}

	function init() {
		var banner = document.getElementById('cookie-consent');
		var btn = document.getElementById('cookie-consent-accept');

		if (!banner || !btn) {
			return;
		}

		if (getCookie(COOKIE_NAME)) {
			banner.classList.add('cookie-consent--hidden');
			return;
		}

		btn.addEventListener('click', function () {
			setCookie(COOKIE_NAME, '1', COOKIE_DAYS);
			banner.classList.add('cookie-consent--hidden');
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
