$(document).ready(function () {
    init();
});

function init() {
    // resetting canvas map
    var canvas = document.getElementById("mallMapCanvas");

    if (canvas.getContext) {
        var ctx = canvas.getContext("2d");
        
        // Draw images
        var img = new Image();   // Create new img element
        img.src = $('#map_image').prop('value');
        ctx.drawImage(img, 0, 0);

        // Obtain last coordinates
        $lastMapX = $("#backendbundle_business_mallMapX").prop('value');
        $lastMapY = $("#backendbundle_business_mallMapY").prop('value');

        if ($lastMapX != "" && $lastMapY != "") {
            // Locating the marker
            var mk = new Image();   // Create new img element
            mk.src = $("#map_marker").prop("value"); // Set source path
            ctx.drawImage(mk, $lastMapX - 15, $lastMapY - 27);
        }

        //locating businesses in mall map
        $coords = $("#map_coords").prop("value");
        if($coords != "0"){
            locateBusinesses(canvas, $coords);
        }
    }
}

function resetCanvas() {
    // resetting canvas map
    var canvas = document.getElementById("mallMapCanvas");

    if (canvas.getContext) {
        var ctx = canvas.getContext("2d");

        // Draw images
        var img = new Image();   // Create new img element
        img.src = $('#map_image').prop('value');
        ctx.drawImage(img, 0, 0);
    }
}

function resetCoords() {
    // resetting X & Y & answers order
    $("#backendbundle_business_mallMapX").prop('value', "");
    $("#backendbundle_business_mallMapY").prop('value', "");
}

function mapPosition(event, element) {
    resetCanvas();
    resetCoords();

    // resetting canvas map
    var canvas = document.getElementById("mallMapCanvas");

    if (canvas.getContext) {
        var ctx = canvas.getContext("2d");

        // Obtain mouse click position
        var offset = $(element).offset();
        var cur_x = event.pageX - offset.left;
        var cur_y = event.pageY - offset.top;

        // Asign click coordinates to text fields
        $("#backendbundle_business_mallMapX").prop('value', Math.round(cur_x));
        $("#backendbundle_business_mallMapY").prop('value', Math.round(cur_y));

        // Locating the marker
        var mk = new Image();   // Create new img element
        mk.src = $("#map_marker").prop("value"); // Set source path
        ctx.drawImage(mk, cur_x - 15, cur_y - 27);
    }
}

function locateBusinesses(canvas, $coords) {
    var ctx = canvas.getContext("2d");

    var coords = eval($coords);
    for(var i = 0; i < coords.length; i++){

        // Locating the marker
        var mk = new Image();   // Create new img element
        mk.src = $("#map_marker").prop("value"); // Set source path
        ctx.drawImage(mk, coords[i][0] - 15, coords[i][1] - 27);
    }

    // export map to Base64
    var mapB64 = canvas.toDataURL("image/jpg");
    $("#mallMapImage").prop("src", mapB64);
}