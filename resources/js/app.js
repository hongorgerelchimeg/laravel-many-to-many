const { default: Axios } = require('axios');

require('./bootstrap');
import 'bootstrap';

const confirmationOverlay = document.querySelector('#confirmation-overlay');
if (confirmationOverlay) {
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.closest('tr').dataset.id;
            const confirmationForm = confirmationOverlay.querySelector('form');
            const strAction = confirmationForm.dataset.base.replace('*****', id);
            confirmationForm.action = strAction;
            confirmationOverlay.classList.remove('d-none');
        })
    });

    const btnNo = document.querySelector('#btn-no');
    btnNo.addEventListener('click', function() {
        confirmationForm.action = '';
        confirmationOverlay.classList.add('d-none');
    });

}

const btnSlugger = document.querySelector('#btn-slugger');
if (btnSlugger) {
    btnSlugger.addEventListener('click', function() {
        const eleSlug = document.querySelector('#slug');
        const title = document.querySelector('#title').value;

        Axios.post('/admin/slugger', {
            originalStr: title,
        })
            .then(function (response) {
                console.log(response);
                eleSlug.value = response.data.slug;
            })
    });
}
