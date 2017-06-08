var editorContent;
function initEditorContent() {
    if (editorContent instanceof CodeMirror) {
        editorContent.refresh();
    } else {
        var mixedMode = {
            name: "htmlmixed",
            scriptTypes: [{
                matches: /\/x-handlebars-template|\/x-mustache/i,
                mode: null
            },
                {
                    matches: /(text|application)\/(x-)?vb(a|script)/i,
                    mode: "vbscript"
                }]
        };
        editorContent = CodeMirror.fromTextArea(document.getElementById("backendbundle_post_content"), {
            mode: mixedMode,
            lineNumbers: true,
            matchBrackets: true,
            styleActiveLine: true,
            lineWrapping: true,
            foldGutter: true,
            gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
            extraKeys: {"Ctrl-Space": "autocomplete"},
            theme: "dracula"
        });
        editorContent.setSize('100%', 500);
    }
}

var editorMetas;
function initEditorMetas() {
    if (editorMetas instanceof CodeMirror) {
        editorMetas.refresh();
    } else {
        var mixedMode = {
            name: "htmlmixed",
            scriptTypes: [{
                matches: /\/x-handlebars-template|\/x-mustache/i,
                mode: null
            },
                {
                    matches: /(text|application)\/(x-)?vb(a|script)/i,
                    mode: "vbscript"
                }]
        };
        editorMetas = CodeMirror.fromTextArea(document.getElementById("backendbundle_post_optionalMetas"), {
            mode: mixedMode,
            lineNumbers: true,
            matchBrackets: true,
            styleActiveLine: true,
            lineWrapping: true,
            foldGutter: true,
            gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
            extraKeys: {"Ctrl-Space": "autocomplete"},
            theme: "dracula"
        });
        editorMetas.setSize('100%', 400);
    }
}

var editorCSS;
function initEditorCSS() {
    if (editorCSS instanceof CodeMirror) {
        editorCSS.refresh();
    } else {
        editorCSS = CodeMirror.fromTextArea(document.getElementById("backendbundle_post_customCss"), {
            mode: "css",
            lineNumbers: true,
            matchBrackets: true,
            styleActiveLine: true,
            lineWrapping: true,
            foldGutter: true,
            gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
            extraKeys: {"Ctrl-Space": "autocomplete"},
            theme: "dracula"
        });
        editorCSS.setSize('100%', 400);
    }
}

var editorJS;
function initEditorJS() {
    if (editorJS instanceof CodeMirror) {
        editorJS.refresh();
    } else {
        editorJS = CodeMirror.fromTextArea(document.getElementById("backendbundle_post_customJs"), {
            mode: "javascript",
            lineNumbers: true,
            matchBrackets: true,
            styleActiveLine: true,
            lineWrapping: true,
            foldGutter: true,
            gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
            extraKeys: {"Ctrl-Space": "autocomplete"},
            theme: "dracula"
        });
        editorJS.setSize('100%', 400);
    }
}

function saveEditorContentCords() {
    if (editorContent instanceof CodeMirror) {
        $('#editor-content-coords').val(JSON.stringify(editorContent.getDoc().getCursor()));
    }
}

function restoreEditorContentCords() {
    if (editorContent instanceof CodeMirror) {
        var coords = JSON.parse($('#editor-content-coords').val());
        var codeToInsert = $('#code-to-insert').html();
        editorContent.focus();
        editorContent.getDoc().setCursor(coords);
        insertStringIntoEditor(editorContent, codeToInsert);
        editorContent.refresh();
        resetFileSelection();
    }
}

function attachFile() {
    var file = $(this).find('img');

    var fileHTML = file.wrap('<div>').parent().html();
    file.unwrap();

    resetFileSelection();

    file.closest('.file').css('border-color', '#0A0');
    file.closest('.file').css('box-shadow', '0 0 10px rgba(0,200,0,0.5)');

    $('#code-to-insert').html(fileHTML);
}

function resetFileSelection() {
    $('.file').css('border-color', '#e7eaec');
    $('.file').css('box-shadow', 'none');
}

function insertStringIntoEditor(editor, str, pos) {

    var selection = editor.getSelection();

    if (selection.length > 0) {
        editor.replaceSelection(str);
    } else {

        var doc = editor.getDoc();
        var cursor = doc.getCursor();

        if (pos === null || typeof pos === 'undefined') {
            pos = {
                line: cursor.line,
                ch: cursor.ch
            };
        }

        doc.replaceRange(str, pos);

    }

}

function dismissAtach() {
    $('#code-to-insert').html("");
    resetFileSelection();
}

function uploadFileAJAX() {
    var formTag = $('form[name="backendbundle_postimage"]');
    var formdata = new FormData(formTag[0]);

    $.ajax({
        type: "POST",
        url: Routing.generate("post_upload_image"),
        processData: false,
        contentType: false,
        cache: false,
        // contentType: 'application/x-www-form-urlencoded',
        data: formdata
    }).done(function (html) {
        // alert(html);
        var parsedData = JSON.parse(html);
        if (typeof parsedData['errorMessage'] !== 'undefined') {
            if (typeof parsedData['errorMessage']['imgFile'] !== 'undefined' && parsedData['errorMessage']['imgFile'] != "") {
                $('#img-file-error').closest('.form-group').addClass('has-error');
                $('#img-file-error').removeClass('hidden');
                $('#img-file-error > li').html(parsedData['errorMessage']['imgFile']);
            } else {
                $('#img-file-error').closest('.form-group').removeClass('has-error');
                $('#img-file-error').addClass('hidden');
            }

            if (typeof parsedData['errorMessage']['name'] !== 'undefined' && parsedData['errorMessage']['name'] != "") {
                $('#img-name-error').closest('.form-group').addClass('has-error');
                $('#img-name-error').removeClass('hidden');
                $('#img-name-error > li').html(parsedData['errorMessage']['name']);
            } else {
                $('#img-name-error').closest('.form-group').removeClass('has-error');
                $('#img-name-error').addClass('hidden');
            }
        } else {
            $('#img-file-error').closest('.form-group').removeClass('has-error');
            $('#img-file-error').addClass('hidden');
            
            $('#img-name-error').closest('.form-group').removeClass('has-error');
            $('#img-name-error').addClass('hidden');

            insertDataIntoTemplate(parsedData);
        }
    });

    // reset form
    formTag[0].reset();
    return false;
}

function deleteFileAJAX() {
    var fileId = $(this).attr('data-file-id');

    $.ajax({
        type: "GET",
        url: Routing.generate("post_delete_image", {'id': fileId}),
        processData: false,
        contentType: false,
        cache: false,
        data: null
    }).done(function (html) {
        var parsedData = JSON.parse(html);
        removeDataFromTemplate(parsedData.id);
    });
    return false;
}

function insertDataIntoTemplate(dataFile) {
    var emptyTemplate = $('#file-template').html();
    var $template = $(emptyTemplate);

    $template.attr('id', 'file-box-' + dataFile.id);
    $template.find('.file-link .image img').attr('alt', dataFile.description);
    $template.find('.file-link .image img').attr('src', '/images/post/gallery/' + dataFile.imgName);
    $template.find('.file-link .file-name').append(dataFile.imgName);

    $template.find('a.delete-image').attr('data-file-id', dataFile.id);

    $('#image-gallery-item-list').append($template);

    refreshBindEvents();
}

function removeDataFromTemplate(fileId) {
    $('#file-box-' + fileId).remove();
    refreshBindEvents();
}

function refreshBindEvents() {
    $('.file-link').unbind('click').on('click', attachFile);

    $('.file-box a.delete-image').unbind('click').on('click', deleteFileAJAX);

    $('#image-gallery-attach').unbind('click').on('click', function (e) {
        restoreEditorContentCords();
        $('#image-gallery').modal('hide');
    });

    $('#image-gallery-upload').unbind('click').on('click', uploadFileAJAX);
}

$(document).ready(function () {

    //  codemirror
    // Only load editors if tab has been clicked
    initEditorContent();
    $('#post-tabs > li > a[href="#post-basic"]').on('shown.bs.tab', function (e) {
        initEditorContent();
    });
    $('#post-tabs > li > a[href="#post-optional-metas"]').on('shown.bs.tab', function (e) {
        initEditorMetas();
    });
    $('#post-tabs > li > a[href="#post-custom-css"]').on('shown.bs.tab', function (e) {
        initEditorCSS();
    });
    $('#post-tabs > li > a[href="#post-custom-js"]').on('shown.bs.tab', function (e) {
        initEditorJS();
    });

    // image gallery modal events
    $('#image-gallery').unbind('show.bs.modal').on('show.bs.modal', function (e) {
        saveEditorContentCords();
    });
    $('#image-gallery').unbind('hide.bs.modal').on('hide.bs.modal', dismissAtach);

    // refreshing bind events
    refreshBindEvents();
});
