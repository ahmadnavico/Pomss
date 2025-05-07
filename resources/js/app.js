import './bootstrap';
import "preline";
import { HSSelect } from "preline";
import { HSStaticMethods } from "preline";
import "./../../vendor/power-components/livewire-powergrid/dist/powergrid";
import "./../../vendor/power-components/livewire-powergrid/dist/tailwind.css";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import Toastify from "toastify-js";


window.HSSelect = HSSelect;
window.HSStaticMethods = HSStaticMethods;
window.flatpickr = flatpickr;