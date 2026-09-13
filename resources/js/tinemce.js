import tinymce from 'tinymce';

import 'tinymce/icons/default';
import 'tinymce/themes/silver';
import 'tinymce/models/dom';

import 'tinymce/skins/ui/oxide/skin';
import 'tinymce/skins/ui/oxide/content';
import 'tinymce/skins/content/default/content';

import 'tinymce/plugins/image';
import 'tinymce/plugins/link';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/code';


tinymce.init({
    selector: '#editor',
    // Добавляем лицензию GPL
    license_key: 'gpl',
    height: 500,

    plugins: 'image link lists code',

    toolbar:
        'undo redo | blocks | ' +
        'bold italic underline | ' +
        'alignleft aligncenter alignright | ' +
        'bullist numlist | ' +
        'link image | code',

    menubar: false,

    image_title: true,

    automatic_uploads: false,
    tinymceai_token_provider: async () => {
        await fetch(`https://demo.api.tiny.cloud/1/l2w0fozrnh6we40n11yla1lv7v5cre56nvum4qrph0uxwqva/auth/random`, { method: "POST", credentials: "include" });
        return { token: await fetch(`https://demo.api.tiny.cloud/1/l2w0fozrnh6we40n11yla1lv7v5cre56nvum4qrph0uxwqva/jwt/tinymceai`, { credentials: "include" }).then(r => r.text()) };
    },
    content_style: `
            body {
                font-family: Arial, sans-serif;
                font-size: 16px;
            }
        `
});

const form = document.querySelector('#topic-form');
const content = document.querySelector('#content');

form.addEventListener('submit', function () {
    content.value = tinymce.get('editor').getContent();
});
