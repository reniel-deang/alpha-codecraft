import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import $ from 'jquery';
window.$ = $;

import 'flowbite';

// import { DataTable } from 'simple-datatables';
// window.DataTable = DataTable;

import DataTable from 'datatables.net-dt';
import 'datatables.net-dt/css/dataTables.dataTables.css';
import 'datatables.net-responsive';

window.DataTable = DataTable;


import Swal from 'sweetalert2';
window.Swal = Swal;