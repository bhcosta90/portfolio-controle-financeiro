require('./bootstrap');

$("body").on("click", ".btn-link-delete", function (event) {
    const el = $(this);
    event.preventDefault();

    Swal.fire({
        title: "Voce tem certeza?",
        text: $(el).parent().find("form").data('text'),
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            $(el).parent().find("form").submit();
        }
    });
});

if ($(".customer_name").length) {
    $(".customer_name").select2({
        placeholder: "",
        // minimumInputLength: 3,
        multiple: false,
        quietMillis: 500,
        ajax: {
            url: "/api/charge/customer",
            dataType: 'json',
            type: 'GET',
            data: function (params) {
                return {
                    search: params.term // search term
                };
            }
        },
    }).on("select2:select", function(e) {
        var data = e.params.data;
        $(".customer_name").val(data.text);
        $(".customer_name").parent().find('.select2-selection__placeholder').text(data.text);
    })
    $(".customer_name").parent().find('.select2-selection__placeholder').text($(".customer_name").val());
}
