/*
Template Name: Ubold - Responsive Bootstrap 5 Admin Dashboard
Author: Techzaa
File: Quilljs init js
*/
// remove import Quill from "quill";

document.addEventListener('DOMContentLoaded', function () {
    // Snow theme
    if (document.getElementById('snow-editor')) {
        var quillSnow = new Quill('#snow-editor', {
            theme: 'snow',
            modules: {
                'toolbar': [[{ 'font': [] }, { 'size': [] }], ['bold', 'italic', 'underline', 'strike'], [{ 'color': [] }, { 'background': [] }], [{ 'script': 'super' }, { 'script': 'sub' }], [{ 'header': [false, 1, 2, 3, 4, 5, 6] }, 'blockquote', 'code-block'], [{ 'list': 'ordered' }, { 'list': 'bullet' }, { 'indent': '-1' }, { 'indent': '+1' }], ['direction', { 'align': [] }], ['link', 'image', 'video'], ['clean']]
            },
        });
    }

    // Bubble theme
    if (document.getElementById('bubble-editor')) {
        var quillBubble = new Quill('#bubble-editor', {
            theme: 'bubble'
        });
    }
});
