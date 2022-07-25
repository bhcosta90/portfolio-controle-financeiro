$(function () {
    // Swal.fire({
    //     title: 'Do you want to save the changes?',
    //     showDenyButton: true,
    //     showCancelButton: true,
    //     confirmButtonText: 'Save',
    //     denyButtonText: `Don't save`,
    //   }).then((result) => {
    //     /* Read more about isConfirmed, isDenied below */
    //     if (result.isConfirmed) {
    //       Swal.fire('Saved!', '', 'success')
    //     } else if (result.isDenied) {
    //       Swal.fire('Changes are not saved', '', 'info')
    //     }
    //   })

    $("body").on("click", ".btn-form-delete", function () {
        const el = $(this);

        Swal.fire({
            title: el.data('title'),
            text: el.data('body'),
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: el.data('yes'),
            cancelButtonText: el.data('not'),
        }).then(function (result) {
            if (result.value) {
                $(el).parent().find('form')[0].submit();
            }
        });
    });
});
