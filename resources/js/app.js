import { Toast } from 'bootstrap';

import 'quill/dist/quill.snow.css';
import './tinemce.js';
import './echo';

document.addEventListener('DOMContentLoaded', () => {
    const toastElement = document.getElementById('successToast');

    if (toastElement) {
        const toast = new Toast(toastElement, {
            delay: 3000
        });

        toast.show();
    }

    document.querySelectorAll('[data-theme-switcher]').forEach((select) => {
        select.addEventListener('change', () => select.form.submit());
    });
});
