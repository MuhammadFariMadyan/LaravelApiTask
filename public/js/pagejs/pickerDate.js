/**
 * Created by jaymin on 10/10/16.
 */

/*
$(function () {

    // Basic options
    $('.pickadate').pickadate();// Basic options
    var days = 6;
    var date = new Date();
    var res = date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    $('.pickADateFromToday').pickadate({
        min: true,
        max: new Date(res)
    });

});

*/


$(function () {

    // Basic options
    $('.pickadate').pickadate();// Basic options
    var days = 6;
    var date = new Date();
    var res = date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));

    $('.pickADateFromToday').pickadate({
        format: 'mm/dd/yyyy',
        min: true
  //      max: new Date(res)

    });

});