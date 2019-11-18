/* eslint-disable no-restricted-syntax */
const regexEmail = /^([a-zA-Z0-9_.+-])+@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
const inputSelector = 'textarea, input:not([type="hidden"]):not([type="submit"]), select';
const REQUIRED = 'required';
const FORMAT = 'format';
const EQUALS = 'equals';
const LINKED = 'linked';
const RULE = 'rule';


/**
 * validates if an email is valid
 *
 * @param  {string} email
 * @return {boolean} true if the email is valid
 */
function isEmailValid(email) {
  return regexEmail.test(email);
}

/**
 * returns true if the value match with the regex rule
 *
 * @param  {string} value to be evaluated
 * @param  {string} rule regular expresion
 * @return {boolean} true if the value match with the evaluation rule
 */
function evaluateRule(value, rule) {
  const regex = new RegExp(rule);
  return regex.test(value.trim());
}

/**
 * Evaluates if a formulary is valid
 *
 * @param  {string} formSelector query selector for the form
 * @return {boolean} true if the form is valid to be submited
 */
function validate(formSelector) {
  let formValid = true;
  const form = document.querySelector(formSelector);
  const inputs = form.querySelectorAll(inputSelector);

  if (inputs) {
    for (let i = 0; i < inputs.length; i++) {
      let invalid = false;
      let errorType = '';
      const input = inputs[i];

      if (input.type === 'radio' && input.attributes.required) {
        const inputChecked = form.querySelector(`input[name="${input.name}"]:checked`);
        if (inputChecked === null) {
          invalid = true;
          errorType = REQUIRED;
        }
      } else if (input.type === 'checkbox' && input.attributes.required) {
        const inputChecked = form.querySelector(`input[name="${input.name}"]:checked`);
        if (inputChecked === null) {
          invalid = true;
          errorType = REQUIRED;
        }
      } else if (input.name === 'confirmPassword' && input.value.length > 0) {
        const inputPass = form.querySelector('input[name="newPassword"]');
        if (inputPass && inputPass.value.length > 0) {
          invalid = invalid || !(input.value === inputPass.value);
        }
      } else if (input.attributes.required && input.value.length === 0) {
        invalid = true;
        errorType = invalid ? REQUIRED : '';
      } else if (input.type === 'email' && !isEmailValid(input.value)) {
        invalid = true;
        errorType = FORMAT;
      } else if (input.type === 'select-one' && input.value.length === 0) {
        invalid = true;
        errorType = FORMAT;
      } else if (input.type === 'button' && input.value === 'Selecciona') {
        invalid = true;
        errorType = FORMAT;
      }

      if (!invalid && input.dataset.rule && input.attributes.required) {
        if (!evaluateRule(input.value, input.dataset.rule)) {
          invalid = true;
          errorType = RULE;
        }
      }

      if (input.dataset.linked) {
        const selector = input.dataset.linked;
        const elements = form.querySelectorAll(`input[data-linked="${selector}"]`);
        if (elements && elements.length > 1 && input.value.length < 1) {
          for (const element of elements) {
            if (element.value.length > 0) {
              invalid = true;
              errorType = LINKED;
            }
          }
        }
      }

      if ((input.dataset.equals && input.attributes.required) || (input.dataset.equals && input.dataset.linked)) {
        const selector = input.dataset.equals;
        const elements = form.querySelectorAll(`input[data-equals="${selector}"]`);
        if (elements && elements.length > 1) {
          for (const element of elements) {
            if (element.value !== elements[0].value) {
              invalid = true;
              errorType = EQUALS;
            }
          }
        }
      }

      if (input.dataset.linked) {
        const selector = input.dataset.linked;
        const elements = form.querySelectorAll(`input[data-linked="${selector}"]`);
        if (elements && elements.length > 1 && input.value.length < 1) {
          for (const element of elements) {
            if (element.value.length > 0) {
              invalid = true;
              errorType = LINKED;
            }
          }
        }
      }

      if ((input.dataset.equals && input.attributes.required) || (input.dataset.equals && input.dataset.linked)) {
        const selector = input.dataset.equals;
        const elements = form.querySelectorAll(`input[data-equals="${selector}"]`);
        if (elements && elements.length > 1) {
          for (const element of elements) {
            if (element.value !== elements[0].value) {
              invalid = true;
              errorType = EQUALS;
            }
          }
        }
      }

      const parent = input.parentElement;
      const errorRequired = form.querySelector(`[data-error="${input.name}"][data-error-type="required"]`);
      const errorFormat = form.querySelector(`[data-error="${input.name}"][data-error-type="format"]`);
      const errorEquals = form.querySelector(`[data-error="${input.name}"][data-error-type="equals"]`);
      const errorLinked = form.querySelector(`[data-error="${input.name}"][data-error-type="linked"]`);
      const errorRule = form.querySelector(`[data-error="${input.name}"][data-error-type="rule"]`);
      if (parent.classList.contains('form_group')) {
        parent.classList.remove('error');
      }
      if (errorRequired) {
        errorRequired.classList.add('hide');
      }
      if (errorFormat) {
        errorFormat.classList.add('hide');
      }
      if (errorEquals) {
        errorEquals.classList.add('hide');
      }
      if (errorLinked) {
        errorLinked.classList.add('hide');
      }
      if (errorRule) {
        errorRule.classList.add('hide');
      }
      if (input.type === 'radio' || input.type === 'checkbox' || input.type === 'select-one') {
        input.parentNode.parentNode.classList.remove('error');
      }

      if (invalid) {
        formValid = false;
        if (parent.classList.contains('form_group')) {
          parent.classList.add('error');
        }
        if (errorType === REQUIRED && errorRequired) {
          errorRequired.classList.remove('hide');
        } else if (errorType === FORMAT && errorFormat) {
          errorFormat.classList.remove('hide');
        } else if (errorType === EQUALS && errorEquals) {
          errorEquals.classList.remove('hide');
        } else if (errorType === LINKED && errorLinked) {
          errorLinked.classList.remove('hide');
        } else if (errorType === RULE && errorRule) {
          errorRule.classList.remove('hide');
        }

        if (input.type === 'radio' || input.type === 'checkbox' || input.type === 'select-one') {
          input.parentNode.parentNode.classList.add('error');
        }
      }
    }
  }

  return formValid;
}

const formValidate = {
  validate,
  isEmailValid,
};

export default formValidate;
