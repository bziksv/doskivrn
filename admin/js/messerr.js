var creal = false;
function futu_alert(header, text, close, className) {
	if (!creal) {
		var futuAlertOuter = document.createElement('div');
		futuAlertOuter.className = 'futu_alert_outer';
		document.body.appendChild(futuAlertOuter);
		var futuAlertFrame = document.createElement('div');
		futuAlertFrame.className = 'frame';
		futuAlertOuter.appendChild(futuAlertFrame);
		
		var futuAlertsHolder = document.createElement('div');
		futuAlertsHolder.id = 'futu_alerts_holder';
		futuAlertsHolder.className = 'futu_alerts_holder';
		futuAlertFrame.appendChild(futuAlertsHolder);
		creal = true;
	}
	var futuAlert = document.createElement('div');
	futuAlert.className = 'futu_alert ' + className;
	$('.futu_alerts_holder').append(futuAlert);
	
	var futuAlertHeader = document.createElement('div');
	futuAlertHeader.className = 'futu_alert_header';
	futuAlert.appendChild(futuAlertHeader);
	
	futuAlertHeader.innerHTML = header;
	if (close) {
		var futuAlertCloseButton = document.createElement('a');
		futuAlertCloseButton.href = '#';
		futuAlertCloseButton.className = 'futu_alert_close_button';
		futuAlertCloseButton.onclick = function(ev) {
			if(!ev) {
				ev=window.event;
			}
			if (!document.all) ev.preventDefault(); else ev.returnValue = false;
			futuAlert.style.display = 'none';
		}
		futuAlert.appendChild(futuAlertCloseButton);
		
		var futuAlertCloseButtonIcon = document.createElement('img');
		futuAlertCloseButtonIcon.src = '/i/buttons/btn_close.gif';
		futuAlertCloseButton.appendChild(futuAlertCloseButtonIcon);
	}
	
	
	var futuAlertText = document.createElement('div');
	futuAlertText.className = 'futu_alert_text';
	futuAlert.appendChild(futuAlertText);

	
	futuAlertText.innerHTML = text;
	
	futuAlert.style.position = 'relative';
	futuAlert.style.top = '0';
	futuAlert.style.display = 'block';

	
	if (!close) {
		setTimeout(function () { futuAlert.style.display = 'none' }, 3000);
	}
}
function showerror(message){
	futu_alert("Ошибка!", message, false, 'error')
}
function showsave(message){
	futu_alert("Обновление", message, false, 'save')
}