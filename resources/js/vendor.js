function numberFormat(div) {
    $(div)
        .find("input.value.negative")
        .mask("000.000,00", {
            reverse: true,
            translation: {
                0: {
                    pattern: /-|\d/,
                    recursive: true,
                },
            },
            onChange: function (value, e) {
                e.target.value = value
                    .replace(/^-\./, "-")
                    .replace(/^-,/, "-")
                    .replace(/(?!^)-/g, "");
            },
        });

    $(div)
        .find("input.value.positive")
        .mask("000.000,00", {
            reverse: true,
            translation: {
                0: {
                    pattern: /\d/,
                    recursive: true,
                },
            },
            onChange: function (value, e) {
                e.target.value = value
                    .replace(/^-\./, "-")
                    .replace(/^-,/, "-")
                    .replace(/(?!^)-/g, "");
            },
        });
}

function selectSelect2(div) {
    $(div).find("select.select2").select2();
}

function tooltip(div) {
    $(div).find('[data-toggle="tooltip"]').tooltip();
}

$("body").on("click", ".btn-link-delete", function (event) {

    const el = $(this);
    event.preventDefault();

    Swal.fire({
        title: "Voce tem certeza?",
        text: $(el).parent().find("form").data("text"),
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

function inputSelect2(div) {
    $(div)
        .find("input.select2")
        .each(function (_, el) {
            $(el)
                .select2({
                    placeholder: "",
                    minimumInputLength: 2,
                    multiple: false,
                    quietMillis: 500,
                    ajax: {
                        url: $(el).data("route"),
                        dataType: "json",
                        type: "GET",
                        data: function (params) {
                            return {
                                search: params.term, // search term
                            };
                        },
                    },
                })
                .on("select2:open", () => {
                    if ($(el).data("empty") !== undefined) {
                        const linkRemove = $("<a>", {
                            html: "Remover",
                            href: "#",
                            class: "m-2 btn btn-outline-secondary btn-sm mt-0",
                        }).on("click", function () {
                            $(el).val("").trigger("change");
                            linkRemove.remove();
                        });

                        if ($(el).val()) {
                            $(".select2-results:not(:has(a))").append(
                                linkRemove
                            );
                        }
                    }
                })
                .on("select2:select", function (e) {
                    let data = e.params.data;
                    let ret = null;
                    if (data.selected == true) {
                        $(el).val(data.text);
                        $(el)
                            .parent()
                            .find(".select2-selection__placeholder")
                            .text(data.text);
                        if ((ref = $(el).data("ref")) !== undefined) {
                            if ((ret = $(`#${ref}`)).length) {
                                ret.val(data.id);
                            }
                        }
                    }
                });

            $(el)
                .parent()
                .find(".select2-selection__placeholder")
                .text($(this).val());
        });
}

function mascaraTelefone(div) {
    $(div).find(".telefone").mask("(99) 99999-9999");
}

$(function () {
    $.fn.select2.defaults.set("theme", "bootstrap-5");
    $.fn.select2.defaults.set("language", "pt-BR");
    numberFormat($("#app > .py-4"));
    selectSelect2($("#app > .py-4"));
    inputSelect2($("#app > .py-4"));
    tooltip($("#app > .py-4"));
    mascaraTelefone($("#app > .py-4"));
});
