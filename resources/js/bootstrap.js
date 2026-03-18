import axios from 'axios';
// resources/js/bootstrap.js
import 'bootstrap/dist/css/bootstrap.min.css';
import * as bootstrap from 'bootstrap';

window.axios = axios;
window.axios.defaults.headers.common ['X-Requested-With'] = 'XMLHttpRequest';
