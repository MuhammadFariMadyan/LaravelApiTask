/* ------------------------------------------------------------------------------
*
*  # Styled checkboxes, radios and file input
*
*  Specific JS code additions for form_checkboxes_radios.html page
*
*  Version: 1.0
*  Latest update: Aug 1, 2015
*
* ---------------------------------------------------------------------------- */

$(function() {


    // Switchery
    // ------------------------------

    // Initialize multiple switches
    if (Array.prototype.forEach) {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.switchery'));
        elems.forEach(function(html) {
            var switchery = new Switchery(html);
        });
    }
    else {
        var elems = document.querySelectorAll('.switchery');
        for (var i = 0; i < elems.length; i++) {
            var switchery = new Switchery(elems[i]);
        }
    }

    // Colored switches
    var primary = document.querySelector('.switchery-primary');
    var switchery = new Switchery(primary, { color: '#2196F3' });

    var danger = document.querySelector('.switchery-danger');
    var switchery = new Switchery(danger, { color: '#EF5350' });

    var warning = document.querySelector('.switchery-warning');
    var switchery = new Switchery(warning, { color: '#FF7043' });

    var info = document.querySelector('.switchery-info');
    var switchery = new Switchery(info, { color: '#00BCD4'});


});
