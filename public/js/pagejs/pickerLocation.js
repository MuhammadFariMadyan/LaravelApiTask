/**
 * Created by jaymin on 10/10/16.
 */
$(function () {
    // Binding UI with the widget
    $('#us2').locationpicker({
        location: {latitude: 44.1219256, longitude: 15.2357175},
        radius: 300,
        scrollwheel: false,
        inputBinding: {
            latitudeInput: $('#us2-lat'),
            longitudeInput: $('#us2-lon'),
            radiusInput: $('#us2-radius'),
            locationNameInput: $('#us2-address')
        }
    });
});