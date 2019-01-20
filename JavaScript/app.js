$(document).ready(function () {
    var menuActive = false;

    $(document).on('click', '.menu-icon', function (e) {
        e.preventDefault();

        if (menuActive === false) {
            $('.menu-button').css('display', 'block');
            menuActive = true;
        }else{
            $('.menu-button').css('display', 'none');
            menuActive = false;
        }
    });

    $(window).resize(function() {
        if ($(window).width() > 1100) {
            $('.menu-button').css('display', 'inline-block');
            menuActive = true;
        }
        else {
            $('.menu-button').css('display', 'none');
            menuActive = false;
        }
    });
    
    $(document).on('click', '.btn-red', function (e) {
        e.preventDefault();
        var deleteChoice = confirm('Opravdu chcete smazat tuto položku?');
        if (deleteChoice === true) {
            window.location.href = $(e.target).prop('href');
        }
    })
});