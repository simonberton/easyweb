import formValidator from './formValidator';
import $ from "jquery";

function showSuccessMessage() {
  const form = document.getElementById('js-contact-form');
  for (let i = 0; i < form.length; i++) {
    switch (form[i].type.toLowerCase()) {
      case 'input':
      case 'email':
      case 'tel':
      case 'textarea':
      case 'text':
        form[i].value = '';
        break;
      case 'file':
        document.querySelector('label[for="file"] span').innerText = document.getElementById('file').placeholder;
        break;
      case 'radio':
      case 'checkbox':
        if (form[i].checked) {
          form[i].checked = false;
        }
        break;
      default:
        break;
    }
  }
  document.querySelector('.js-formSubmitSuccess').style.display = 'block';
}

function showErrorMessage(errorMessage) {
  let message = '';
  if (typeof errorMessage === 'string') {
    message = errorMessage;
  }
  if (isObject(errorMessage) && Array.isArray(errorMessage.errors)) {
    message = '';
    errorMessage.errors.forEach((error) => {
      const name = `${errorMessage.name}[${error.key}]`;
      document.getElementsByName(name).forEach((elm) => {
        elm.parentElement.classList.add('error');
      });
      message += error.error[0];
    });
  }
  document.querySelectorAll('.js-formSubmitError').forEach((elm) => {
    elm.style.display = 'block';
    elm.innerHTML = message;
  });
}

function OnReady(event) {
  const httpRequest = event.currentTarget;
  if (httpRequest.readyState === XMLHttpRequest.DONE) {
    const response = JSON.parse(httpRequest.response);
    if (httpRequest.status === 200 && response.result === 'success') {
      showSuccessMessage();
    } else {
      showErrorMessage(response.errors);
    }
  }
}

function sendRequest(event) {
  const httpRequest = new XMLHttpRequest();
  httpRequest.open('POST', event.action);
  httpRequest.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
  httpRequest.onreadystatechange = OnReady;
  httpRequest.send(new FormData(event));
}

function sendFormulary(event) {
  event.preventDefault();
  const isValid = formValidator.validate(`#${event.target.id}`);
  document.querySelector('.js-formSubmitSuccess').style.display = 'none';
  document.querySelectorAll(`#${event.target.id} .error`).forEach((elm) => {
    elm.classList.remove('error');
  });
  document.querySelectorAll('.js-formSubmitError').forEach((elm) => {
    elm.style.display = 'none';
  });
  if (!isValid) {
    document.querySelectorAll('.js-formSubmitError').forEach((elm) => {
      elm.style.display = 'block';
    });
  } else {
    sendRequest(event.target);
  }
}

const form = document.getElementById('js-contact-form');
if (form !== undefined) {
  form.addEventListener('submit', sendFormulary);
}
