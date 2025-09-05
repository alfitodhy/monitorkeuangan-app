import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import $ from "jquery";
window.$ = window.jQuery = $;

import "datatables.net"; // inti datatables
import "datatables.net-responsive"; // plugin responsive

// CSS bisa diimport di JS juga
import "datatables.net-dt/css/dataTables.dataTables.css";
import "datatables.net-responsive-dt/css/responsive.dataTables.css";

// opsional: bahasa Indonesia
import language from "datatables.net-plugins/i18n/id.mjs";
window.dtLangId = language;

