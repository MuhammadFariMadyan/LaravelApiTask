/* ------------------------------------------------------------------------------
 *
 *  # Basic datatables
 *
 *  Specific JS code additions for datatable_basic.html page
 *
 *  Version: 1.0
 *  Latest update: Aug 1, 2015
 *
 * ---------------------------------------------------------------------------- */

$(function () {

    // Enable Select2 select for the length option
    if ($(".selectpicker").attr('data-width') != null) {
        $('.selectpicker').selectpicker();
    } else {
        $('.selectpicker').selectpicker({
            width: 'auto'
        });
    }
});
