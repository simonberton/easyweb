const Swal = require('sweetalert2')
import 'sweetalert2/src/sweetalert2.scss'

function onRemoveDone(event) {
  const request = event.currentTarget;
  const response = JSON.parse(request.response);

  switch (request.status) {
    case 500:
    case 403:
    case 404:
      showAlert(response.msg);
      break;
    case 200:
      if (response.id) {
        const selector = `.js-genericDelete_wrapper[data-id="${response.id}"]`;
        document.querySelector(selector).classList.add('js-genericDelete_remove');
      }
      showAlert({
        title: response.title,
        text: response.msg,
        icon: 'success',
      }, () => {
        const elm = document.querySelector('.js-genericDelete_remove');
        if (elm) {
          elm.classList.add('animated', 'fadeOutRight');
          setTimeout(() => {
            document.querySelector('.js-genericDelete_remove').remove();
          }, 800);
        }
      });
      break;
    case 302:
      showAlert({
        title: response.title,
        text: response.msg,
        icon: 'success',
      });
      if (response.href) {
        setTimeout(() => {
          window.location = response.href;
        }, 2000);
      }
      break;
    default:
      break;
  }
}

function sendDeletePost(value) {
  const elm = document.querySelector(`.js-genericDelete[data-id="${value}"]`);
  if (elm) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', elm.dataset.url);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest', 'Content-Type', 'application/json');
    xhr.onload = onRemoveDone;
    xhr.onerror = onRemoveDone;
    xhr.send(JSON.stringify({ id: elm.dataset.id }));
  }
}

function showAlert(options = null, callback = null) {

  if (options && callback) {
    Swal.fire(options).then(callback);
  } else if (options) {
    Swal.fire(options);
  }
}

function remove(event) {
  const {title, message, id} = event.currentTarget.dataset;

  showAlert({
    icon: 'warning',
    dangerMode: true,
    title,
    text: message,
    buttons: {
      cancel: window.cmsTranslations.cancel,
      confirm: {
        text: window.cmsTranslations.delete.confirm,
        value: id,
        closeModal: false,
      },
    },
  }, (value) => {
    sendDeletePost(id);
  });
}

document.querySelectorAll('.js-genericDelete').forEach(input => input.addEventListener('click', remove));
