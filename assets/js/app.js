/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');

require('@fortawesome/fontawesome-free/css/all.min.css');

require('bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
//const $ = require('jquery');

import $ from 'jquery';

require('@fortawesome/fontawesome-free/js/all.js');

import * as bootstrap from 'bootstrap';
import 'bootstrap-datepicker';

if ($('.js-datePicker') !== undefined) {
  $('.js-datePicker').datepicker();
}
