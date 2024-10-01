import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import $ from 'jquery';
window.$ = $;

import 'flowbite';
import {Modal} from 'flowbite';
window.Modal = Modal;
import { CopyClipboard } from 'flowbite';
window.CopyClipboard = CopyClipboard;

import DataTable from 'datatables.net-dt';
import 'datatables.net-dt/css/dataTables.dataTables.css';
import 'datatables.net-responsive';
window.DataTable = DataTable;

import Swal from 'sweetalert2';
window.Swal = Swal;
window.customSwal = Swal.mixin({
    customClass: {
        popup: 'bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-200',
        title: 'text-lg',
        text: 'text-md'
    }
});

import select2 from 'select2';
select2();
import '../css/styles.css';