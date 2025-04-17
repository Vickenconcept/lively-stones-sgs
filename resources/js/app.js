import './bootstrap';


import 'flowbite'
import notification from './notification';


import Toastify from 'toastify-js'
import 'toastify-js/src/toastify.css';

// Import Toastify JS
window.Toastify = Toastify;

import Alpine from 'alpinejs';
Alpine.data('notification', notification);