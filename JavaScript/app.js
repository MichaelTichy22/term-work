// $(document).ready(function () {
//     $(function () {
//         var form = $('#postForm');
//         var messages = $('#postFormMessages');
//
//         $(form).submit(function (event) {
//             event.preventDefault();
//
//             var formData = $(form).serialize();
//             formData = formData+'&ajax=1';
//
//             $.ajax({
//                 type: 'POST',
//                 url: $(form).attr('action'),
//                 data: formData,
//             }).done(function (response) {
//                 $(messages).html(response);
//                 if($('#submit-create').length !== 0){
//                     $('#title').val('');
//                     $('#annotation').val('');
//                     $('#content').val('');
//                 }
//             }).fail(function (data) {
//                 $(messages).text('Chyba při odesílání formuláře');
//             })
//         })
//     });
//
//     $(function () {
//         var form = $('#loginForm');
//         var messages = $('#loginFormMessages');
//
//
//         $(form).submit(function (event) {
//             event.preventDefault();
//
//             var formData = $(form).serialize();
//             formData = formData+'&ajax=1';
//             console.log(formData);
//
//             $.ajax({
//                 type: 'POST',
//                 url: $(form).attr('action'),
//                 data: formData,
//             }).done(function (response) {
//                 if(response === 'success'){
//                     window.location.href = '/home/index';
//                 }
//                 $(messages).html(response);
//             }).fail(function (data) {
//                 $(messages).text('Chyba při odesílání formuláře');
//             })
//         })
//     });
//
//     $(function () {
//         var form = $('#registerForm');
//         var messages = $('#registerFormMessages');
//
//         $(form).submit(function (event) {
//             event.preventDefault();
//
//             var formData = $(form).serialize();
//             formData = formData+'&ajax=1';
//             console.log(formData);
//
//             $.ajax({
//                 type: 'POST',
//                 url: $(form).attr('action'),
//                 data: formData,
//             }).done(function (response) {
//                 $(messages).html(response);
//                 $('#username').val('');
//                 $('#email').val('');
//                 $('#password').val('');
//             }).fail(function (data) {
//                 $(messages).text('Chyba při odesílání formuláře');
//             })
//         })
//     });
//
//     $(function () {
//         var form = $('#categoryForm');
//         var messages = $('#categoryFormMessages');
//
//         $(form).submit(function (event) {
//             event.preventDefault();
//
//             var formData = $(form).serialize();
//             formData = formData+'&ajax=1';
//             console.log(formData);
//
//             $.ajax({
//                 type: 'POST',
//                 url: $(form).attr('action'),
//                 data: formData,
//             }).done(function (response) {
//                 $(messages).html(response);
//                 if($('#submit-create').length !== 0){
//                     $('#name').val('');
//                 }
//             }).fail(function (data) {
//                 $(messages).text('Chyba při odesílání formuláře');
//             })
//         })
//     });
//     $(function () {
//         var form = $('#commentForm');
//         var messages = $('#commentFormMessages');
//
//         $(form).submit(function (event) {
//             event.preventDefault();
//
//             var formData = $(form).serialize();
//             formData = formData+'&ajax=1';
//             console.log(formData);
//
//             $.ajax({
//                 type: 'POST',
//                 url: $(form).attr('action'),
//                 data: formData,
//             }).done(function (response) {
//                 if($('#submit-create').length !== 0){
//                     $('#subject').val('');
//                     $('#content').val('');
//
//                     var values = response.split(',');
//                     $(messages).html(values[0]);
//                     $('#comments-block').append(
//                     '<div id="comment-"'+values[1]+'>' +
//                         ' <h3>'+values[2]+'</h3>' +
//                         ' <div><i>Autor: '+values[5]+' ('+values[6]+')</i></div>' +
//                         ' <div><i>Vytvořeno: '+values[4]+'</i></div>' +
//                         ' <p>'+values[3]+'</p>' +
//                         '<div><a href="/comment/delete/'+values[1]+'">Smazat</a>' +
//                         ' <a href="/comment/edit/'+values[1]+'/'+values[7]+'">Editovat</a>' +
//                         '</div></div>'
//                     );
//                 }else{
//                     window.location.href = '/post/show/'+response;
//                 }
//             }).fail(function (data) {
//                 if (data.responseText !== '') {
//                     $(messages).html(data.responseText);
//                 } else {
//                     $(formMessages).text('Chyba při odesílání formuláře');
//                 }
//             })
//         })
//     });
// });