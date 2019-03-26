// The main class
var AlertBox = function(option) {
	this.show = function(type, msg) {
	  if (msg === ''  || typeof msg === 'undefined' || msg === null) {
		throw '"msg parameter is empty"';
	  }
	  else {
		var alertArea = document.querySelector('#alert-area');
		var alertBox = document.createElement('DIV');
		var alertContent = document.createElement('DIV');
		var alertClose = document.createElement('A');
		var alertClass = this;
		alertContent.classList.add('alert-content');
		alertContent.innerHTML = msg;
		alertClose.classList.add('alert-close');
		alertClose.setAttribute('href', '#');
		alertBox.classList.add('alert-box');
		switch (type) {
			case 'success':
                alertBox.classList.add('success-box');
				break;
            case 'info':
                alertBox.classList.add('info-box');
                break;
            case 'danger':
                alertBox.classList.add('danger-box');
                break;
            case 'warning':
                alertBox.classList.add('warning-box');
                break;
        }

		alertBox.appendChild(alertContent);
		if (!option.hideCloseButton || typeof option.hideCloseButton === 'undefined') {
		  alertBox.appendChild(alertClose);
		}
		alertArea.appendChild(alertBox);
		alertClose.addEventListener('click', function(event) {
		  event.preventDefault();
		  alertClass.hideclose(alertBox);
		});
		if (!option.persistent) {
		  var alertTimeout = setTimeout(function() {
			alertClass.hide(alertBox);
			clearTimeout(alertTimeout);
		  }, option.closeTime);
		}
	  }
	};

    this.hideclose = function(alertBox) {
        alertBox.classList.add('hide');
        var disperseTimeout = setTimeout(function() {
            alertBox.parentNode.removeChild(alertBox);
            clearTimeout(disperseTimeout);
        }, 1);
    };
  
	this.hide = function(alertBox) {
	  alertBox.classList.add('hide');
	  var disperseTimeout = setTimeout(function() {
		alertBox.parentNode.removeChild(alertBox);
		clearTimeout(disperseTimeout);
	  }, 500);
	};
  };
  
  // Sample invoke
  var alertNonPersistent = document.querySelector('#alertNonPersistent');
  var alertPersistent = document.querySelector('#alertPersistent');
  var alertShowMessage = document.querySelector('#alertShowMessage');
  var alertHiddenClose = document.querySelector('#alertHiddenClose');
  var alertMessageBox = document.querySelector('#alertMessageBox');
  var alertbox = new AlertBox({
	closeTime: 5000,
	persistent: false,
	hideCloseButton: false
  });
  var alertboxPersistent = new AlertBox({
	closeTime: 5000,
	persistent: true,
	hideCloseButton: false
  });
  var alertNoClose = new AlertBox({
	closeTime: 5000,
	persistent: false,
	hideCloseButton: true
  });
	
	// Comment Out

  // alertNonPersistent.addEventListener('click', function() {
	// alertbox.show(alertMessageBox.value);
	// alertMessageBox.value = '';
  // });
  //
  // alertPersistent.addEventListener('click', function() {
	// alertboxPersistent.show(alertMessageBox.value);
	// alertMessageBox.value = '';
  // });
  
  // alertShowMessage.addEventListener('click', function() {
	// alertbox.show('Hello! This is a message.');
  // });
  
  // alertHiddenClose.addEventListener('click', function() {
	// alertNoClose.show('Hello! I have hidden my close button.');
  // });