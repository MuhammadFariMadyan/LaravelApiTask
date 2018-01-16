function load_data(id, index) {
    var urlRequest;
    if (id == "") {
        urlRequest = "http://localhost/gasdrop/public/admin/loaddata/" + index;
    } else {
        urlRequest = "http://localhost/gasdrop/public/admin/loaddata/" + index + "/" + id;
    }
    $.ajax({
        url: urlRequest,
        type: 'get',
        complete: function () {
        },
        success: function (data) {
            $("#" + index).html(data);
            if ($.fn.selectpicker) {
                $("#" + index).selectpicker('refresh');
            } else if ($.fn.selectmenu) {
                $(".select-basic").selectmenu("destroy");
                $(".select-basic").selectmenu();
            }
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    });
}
function load_seldata(id, index, selId) {
    var urlRequest;
    if (id == "") {
        urlRequest = "http://localhost/gasdrop/public/admin/loadseldata/" + index + "/" + selId;
    } else {
        urlRequest = "http://localhost/gasdrop/public/admin/loadseldata/" + index + "/" + selId + "/" + id;
    }
    $.ajax({
        url: urlRequest,
        type: 'get',
        complete: function () {
        },
        success: function (data) {
            $("#" + index).html(data);
            if ($.fn.selectpicker) {
                $("#" + index).selectpicker('refresh');
            } else if ($.fn.selectmenu) {
                $(".select-basic").selectmenu("destroy");
                $(".select-basic").selectmenu();
            }
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })
    ;
}
