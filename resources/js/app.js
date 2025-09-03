import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

flatpickr(".datetimepicker", {
    enableTime: true,
    dateFormat: "m-d-Y H:i",
    time_24hr: true,
    // defaultDate: new Date(),
});
