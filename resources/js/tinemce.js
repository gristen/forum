import tinymce from 'tinymce';

import 'tinymce/icons/default';
import 'tinymce/themes/silver';
import 'tinymce/models/dom';

import 'tinymce/skins/ui/oxide-dark/skin';
import 'tinymce/skins/ui/oxide-dark/content';
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

    /* тёмное оформление редактора — под тему форума */
    skin: 'oxide-dark',

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

                margin: 14px 16px;

                background-color: #101218;
                color: #e7e9ee;
            }

            a {
                color: #a277ff;
            }

            blockquote {
                margin: 0 0 0 6px;
                padding-left: 14px;

                border-left: 3px solid #8b5cf6;

                color: #b8bdc8;
            }

            code {
                padding: 2px 5px;

                background-color: #222731;
                border-radius: 4px;
            }

            ::selection {
                background: rgba(139, 92, 246, .35);
                color: #fff;
            }
        `
});

const form = document.querySelector('#topic-form');
const content = document.querySelector('#content');
if (form) {
    form.addEventListener('submit', function () {
        content.value = tinymce.get('editor').getContent();
    });
}
