$(document).ready(() => {

    $(document).on('click', '.productItemClick', function () {
        let id = $(this).attr('productId');
        $("#productIdForDestination").val(id);
        $.ajax({
            url: "/products/" + id + "/destinations",
            type: 'GET',
            success: function (response) {
                let html = '';
                $.each(response, function (i, destination) {

                     html += `<div class="flex gap-2.5 p-2 border-b-1 border-gray-200 cursor-pointer " destinationId="${destination.id}">
                                    <i class="fas fa-edit destinationEdit text-[#989898] text-[16px]"></i>
                                    <i class="fas fa-trash text-[#989898] text-[16px]"></i>
                                    <span class="destinationItemClick w-100"  destinationId="${destination.id}">
                                        <p class="text-[#464646] ">${destination.destination_name}</p>
                                    </span>
                                </div>`;
                });

                $('#destination').html(html);
                $('#resortShipingContainer').html("");
                $('#cruiseItinerariesContainer').html("");

            },
            error: function (xhr) {
                console.error(xhr.responseText);

            }
        });
    });
    $(document).on('click', '.destinationItemClick', function () {
        let id = $(this).attr('destinationId');
        $("#destinationForResort").val(id);

        $.ajax({
            url: "/destinations/" + id + "/resortShips",
            type: 'GET',
            success: function (response) {
                let html = '';

                $.each(response, function (i, resort) {

                     html +=`<div class="flex gap-2.5 p-2 border-b-1 border-gray-200 cursor-pointer " resortId="${resort.id}">
                                <i  class="fas fa-edit resortEdit text-[#989898] text-[16px]"></i>
                                <i class="fas fa-trash text-[#989898] text-[16px]"></i>
                                <span class="resortItemClick w-100"  resortId="${resort.id}">
                                    <p class="text-[#464646] ">${resort.resort_ship_name}</p>
                                </span>
                            </div>`;
                });

                $('#resortShipingContainer').html(html);
                $('#cruiseItinerariesContainer').html("");
            },
            error: function (xhr) {
                console.error(xhr.responseText);

            }
        });
    });
    $(document).on('click', '.resortItemClick', function () {
        let id = $(this).attr('resortId');
        $("#resortForDestination").val(id);

        $.ajax({
            url: "/resortShips/" + id + "/cruiseItineraries",
            type: 'GET',
            success: function (response) {
                let html = '';

                $.each(response, function (i, cruise) {

                    html +=`<div class="flex gap-2.5 p-2 border-b-1 border-gray-200 cursor-pointer " cruiseId="${cruise.id}">
                                <i class="fas fa-edit cruiseEdit text-[#989898] text-[16px]"></i>
                                <i class="fas fa-trash  text-[#989898] text-[16px]"></i>
                                <p class="text-[#464646] ">${cruise.cruise_name}</p>
                            </div>`;
                });
                $('#cruiseItinerariesContainer').html(html);

            },
            error: function (xhr) {
                console.error(xhr.responseText);

            }
        });
    });

    $(document).on("click", ".destinationEdit", function (e) {
        e.stopPropagation();
        let destinationId = $(this).parent().attr("destinationId");
        $("#otherModalTitle").text("Manage Destination");

        let form = $("#othersForm");
        form.attr("action", "/destinations/" + destinationId);
        $("#nameOtherModal").val("");
        $("#saveOthersBtn").text("Update");
        $("#othersFormMethod").val("PUT");
        $.ajax({
            url: "/destinations/" + destinationId,
            type: "GET",
            success: function (destination) {
                $("#nameOtherModal").val(destination.destination_name);
                window.dispatchEvent(new CustomEvent("open-modal", {
                    detail: "add-others"
                }));
            }
        });

    });

    $(document).on("click", ".resortEdit ", function (e) {
        e.stopPropagation();
        let resortId = $(this).parent().attr("resortId");
        $("#otherModalTitle").text("Manage Resort / Ship");

        let form = $("#othersForm");
        form.attr("action", "/resorts/" + resortId);
        $("#nameOtherModal").val("");
        $("#saveOthersBtn").text("Update");
        $("#othersFormMethod").val("PUT");
        $.ajax({
            url: "/resorts/" + resortId,
            type: "GET",
            success: function (resort) {
                $("#nameOtherModal").val(resort.resort_ship_name);
                window.dispatchEvent(new CustomEvent("open-modal", {
                    detail: "add-others"
                }));
            }
        });
    });

    $(document).on("click", ".cruiseEdit", function (e) {
        e.stopPropagation();
        let cruiseId = $(this).parent().attr("cruiseId");
        $("#otherModalTitle").text("Manage Resort / Ship");

        let form = $("#othersForm");
        form.attr("action", "/cruises/" + cruiseId);
        $("#nameOtherModal").val("");
        $("#saveOthersBtn").text("Update");
        $("#othersFormMethod").val("PUT");
        $.ajax({
            url: "/cruises/" + cruiseId,
            type: "GET",
            success: function (cruise) {
                $("#nameOtherModal").val(cruise.cruise_name);
                window.dispatchEvent(new CustomEvent("open-modal", {
                    detail: "add-others"
                }));
            }
        });
    });

    $("#addAdministrationDestination").on('click', function () {
        let productId = $('#productIdForDestination').val();
        $("#otherModalTitle").text("Manage Destination");

        $('#othersForm').attr(
            'action',
            '/products/' + productId + '/destinations'
        );
        $("#othersFormMethod").val("POST");

        window.dispatchEvent(new CustomEvent('open-modal', {
            detail: 'add-others'
        }));
    })


    $("#addAdministrationProduct").on("click", function (e) {
        e.preventDefault();
        addProduct();
    })
    $("#saveProductBtn").on("click", function (e) {

        e.preventDefault();
        let form = $("#productForm");
        // $("#formMethod").val("POST");

        $.ajax({

            url: form.attr("action"),
            type: "POST",
            data: form.serialize(),

            success: function (response) {
                alert(response.message);
                form.trigger("reset");
                window.dispatchEvent(new CustomEvent("close-modal", {
                    detail: "add-product"
                }));
                location.reload();
            },

            error: function (xhr) {
                $("#productFormErrors ul").empty();
                 if (xhr.status === 422) {
                    $.each(xhr.responseJSON.errors, function (field, messages) {
                        messages.forEach(function (message) {
                            $("#productFormErrors").append(
                                "<li>" + message + "</li>"
                            );
                        });
                    });
                    $("#productFormErrors").removeClass("hidden");
                }
            }

        });
    });


    $("#saveOthersBtn").on('click', function () {
        let form = $("#othersForm");

        $.ajax({

            url: form.attr("action"),
            type: "POST",
            data: form.serialize(),

            success: function (response) {

                $("#nameOtherModal").val("");

                window.dispatchEvent(new CustomEvent("close-modal", {
                    detail: "add-others"
                }));
                switch(response.type) {
                    case "product":
                        $(".productItemClick[productid='" + response.product_id + "']").trigger("click");
                        $('#resortShipingContainer').html("");
                        $('#cruiseItinerariesContainer').html("");
                        break;

                    case "destination":
                        $(".destinationItemClick[destinationid='" + response.destination_id + "']").trigger("click");
                        $('#cruiseItinerariesContainer').html("");
                        break;


                    case "resortShip":
                        $(".resortItemClick[resortId='" + response.resortid  + "']").trigger("click");
                        break;

                }

            },
            error: function (xhr) {
                console.log(xhr.responseJSON);
            }
        });
    });


    $(document).on("click", ".productEdit", function (e) {

        e.stopPropagation();

        let productId = $(this).parent().attr("productId");
        $("#formMethod").val("PUT");

        let form = $("#productForm");
        form.trigger("reset");
        $("#productFormErrors")
            .addClass("hidden")
            .find("ul")
            .empty();

        $(".validation-error")
            .removeClass("border-red-500")
            .addClass("border-[#bdbdbd]");

        form.attr("action", "/products/" + productId);

        $("#saveProductBtn").text("Update");

        $.ajax({

            url: "/products/" + productId,
            type: "GET",

            success: function (product) {

                $("#product_name").val(product.product_name);
                $("#display_order").val(product.display_order);
                $("#product_type").val(product.product_type);
                $("#currency").val(product.currency);
                $("#tax").val(product.tax);

                $("#vendorBDM").val(product.vendorBDM);
                $("#bdm_phone_number").val(product.bdm_phone_number);
                $("#bdm_email").val(product.bdm_email);

                $("#phone_number").val(product.phone_number);
                $("#first_address_line").val(product.first_address_line);
                $("#second_address_line").val(product.second_address_line);

                $("#city").val(product.city);
                $("#state").val(product.state);
                $("#postal_code").val(product.postal_code);
                $("#country").val(product.country);

                $("#notes").val(product.notes);

                window.dispatchEvent(new CustomEvent("open-modal", {
                    detail: "add-product"
                }));

            },

            error: function (xhr) {

                console.log(xhr.responseJSON);

            }

        });

    });


    $("#addAdministrationResortShip").on('click', function () {
         let destinationId =  $('#destinationForResort').val();
        $("#otherModalTitle").text("Manage Resort / Ship");
        $('#othersForm').attr(
            'action',
            '/destinations/' + destinationId + '/resort-ships'
        );
        $("#othersFormMethod").val("POST");

        window.dispatchEvent(new CustomEvent('open-modal', {
            detail: 'add-others'
        }));

    })


    $("#addAdministrationCruise").on('click', function () {
         let resortId =  $('#resortForDestination').val();
        $("#otherModalTitle").text("Manage Cruise");
        $("#othersFormMethod").val("POST");

        $('#othersForm').attr(
            'action',
            '/resorts/' + resortId + '/cruises'
        );

        window.dispatchEvent(new CustomEvent('open-modal', {
            detail: 'add-others'
        }));
    })




});

function saveTextarea(content) {
    // const text = $('#displayTodoNoteHere').val();
    const caseId = $('#hiddenCaseId').val();

    $.ajax({
        url: '/save-textarea', // Laravel route
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            text: content,
            case_id: caseId
        },
        success: function(response) {
            $('#status').text('Saved!').fadeIn().delay(1000).fadeOut();
        },
        error: function(xhr) {
            $('#status').text('Error saving').fadeIn().delay(2000).fadeOut();
        }
    });
}



function addProduct() {
    let form = $("#productForm");
    form.trigger("reset");
    form.attr("action", "/products");
    $("#formMethod").val("POST");
    $("#saveProductBtn").text("Save");
}

