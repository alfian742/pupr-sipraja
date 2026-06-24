function initCKEditor(selector) {
    document.querySelectorAll(selector).forEach((element) => {
        ClassicEditor
            .create(element, {
                language: 'id',
                toolbar: [
                    'undo', 'redo', '|',
                    'heading', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'link', 'mediaEmbed', 'insertTable', 'blockQuote', 'horizontalLine', '|',
                    'alignment', '|',
                    'bulletedList', 'numberedList', 'outdent', 'indent', '|',
                    'removeFormat'
                ],
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                        { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                        { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                        { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
                    ]
                },
                table: {
                    contentToolbar: [
                        'tableColumn',
                        'tableRow',
                        'mergeTableCells'
                    ]
                },
                link: {
                    addTargetToExternalLinks: true,
                    defaultProtocol: 'https://'
                },
                mediaEmbed: {
                    previewsInData: true
                }
            })
            .catch(error => {
                console.error(error);
            });
    });
}

initCKEditor('.ckeditor');