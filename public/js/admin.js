function uuidv4() {
    return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
      (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    );
  }
  
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
    }).on('click', '.btn-link-edit', function(e){
        e.preventDefault();
        const id = uuidv4();
        const link = $(this).prop('href');

        const html = `
            <div class="modal" id='modal-${id}' tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">123</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <iframe id='iframe-${id}' src='${link}?iframe=${id}' style='width: 100%;border: 0;'></iframe>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
                </div>
            </div>
        `;

        const div = $("<div>", {html: html});
        $('body').append(div);
        $(`#modal-${id}`).modal('show');
    });
});
