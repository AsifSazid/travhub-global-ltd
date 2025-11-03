import Quill from 'quill';
import 'quill/dist/quill.snow.css'; // Quill's CSS for the "snow" theme

// Initialize Quill on DOMContentLoaded
document.addEventListener('DOMContentLoaded', function () {
    const editorDiv = document.getElementById('description-editor');
    if (editorDiv) {
        const quill = new Quill(editorDiv, {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'], // font styling
                    [{ 'font': [] }],                 // font pick
                    [{ 'align': [] }],                // paragraph alignment
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }], // lists
                ]
            }
        });

        // Copy HTML content to hidden textarea on form submit
        const form = editorDiv.closest('form');
        if (form) {
            form.addEventListener('submit', function () {
                const hiddenInput = document.getElementById('description');
                hiddenInput.value = editorDiv.querySelector('.ql-editor').innerHTML;
            });
        }
    }
});
