var users;
var usuarios_cotejados = new Array();
var usuarios_seleccionados = new Array();

function search_function() {

    $("#usuarios_cotejados").empty();
    $("#alumnos").html(usuarios_seleccionados.length);
    usuarios_cotejados = new Array();

    var search = $("#search").val();
    collation(search);

    for (var i = 0; i < usuarios_cotejados.length; i++) {

        $("#usuarios_cotejados").append('<h3><a onclick="add(' + usuarios_cotejados[i].id + ')" href="#" class="badge badge-light">' + usuarios_cotejados[i].surname + ', ' + usuarios_cotejados[i].name + '</a></h3>');

    }

}

function collation(search) {

    var j = 0;


    for (var i = 0; i < users.length; i++) {

        var name = users[i].name.toLowerCase();
        var surname = users[i].surname.toLowerCase();

        var array = search.split(" ");
        if(array.length > 1){

            for(var k = 0; k<array.length; k++){
                if (name.includes(array[k]) || surname.includes(array[k])) {
                    usuarios_cotejados[j] = users[i];
                    j++;
                }
            }

        }else if (name.includes(search) || surname.includes(search)) {
            usuarios_cotejados[j] = users[i];
            j++;
        }

    }

    var filteredArray = usuarios_cotejados.filter(function(item, pos){
        return usuarios_cotejados.indexOf(item)== pos;
    });

    usuarios_cotejados = filteredArray;

}

function add(id) {


    for (var i = 0; i < usuarios_cotejados.length; i++) {

        if (usuarios_cotejados[i].id == id) {

            if (!esta_ya(id)) {

                $("#usuarios_seleccionados").append('<span style="font-size: 20px" id="user_' + id + '"><a onclick="remove(' + id + ')" href="#" class="badge badge-danger"><i class="far fa-trash-alt"></i> ' + usuarios_cotejados[i].surname + ', ' + usuarios_cotejados[i].name + '</a><br></span>');

                usuarios_seleccionados.push(usuarios_cotejados[i]);
                lista_usuarios_update();

                $("#search").val('');
                $("#search").focus();
                search_function();

            }


        }

    }

}

function esta_ya(id) {

    var res = false;

    for (var i = 0; i < usuarios_seleccionados.length; i++) {

        console.log("salta");

        if (usuarios_seleccionados[i].id == id) {
            res = true;
            break;
        }

    }

    return res;

}

function remove(id) {

    $("#user_" + id).remove();

    $("#search").focus();

    for (var i = 0; i < usuarios_seleccionados.length; i++) {

        if (usuarios_seleccionados[i].id == id) {

            usuarios_seleccionados.splice(i, 1);

        }

    }

    $("#alumnos").html(usuarios_seleccionados.length);
    lista_usuarios_update();

}

function lista_usuarios_update() {
    var ids = "";
    for (var i = 0; i < usuarios_seleccionados.length; i++) {

        ids += " " + usuarios_seleccionados[i].id;

    }
    console.log(ids);
    $("#lista_usuarios").val(ids);
}

