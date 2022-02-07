/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

const { default: axios } = require('axios');

// window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// const app = new Vue({
//     el: '#app',
// });


$("body").on("click", ".btn-link-delete", function(event){
    const el = $(this);
    event.preventDefault();

    Swal.fire({
        title: "Voce tem certeza?",
        text: "Você não pode reverter essa ação depois!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, delete isso!",
        cancelButtonText: "Não, cancele isso!"
    }).then((result) => {
        if (result.isConfirmed) {
            $(el).parent().find('form').submit();
        }
    });
});

if ($("#account_resume").length) {
    const type = $("[name='type']").val();

    axios.get(`/charge/total?type=${type}`).then((json) => {
        $("#expense_total").html(json.data.cost.format.total);
        $("#expense_payable").html(json.data.cost.format.due_value);

        $("#incoming_total").html(json.data.income.format.total);
        $("#incoming_payable").html(json.data.income.format.due_value);

        $("#my_account_total").html(json.data.account.format.total);
        $("#my_account_pay").html(json.data.calculate.format.total);

        let classAccount = "text-danger";
        let classCalculate = "text-danger";

        if (json.data.account.total > 0) {
            classAccount = "text-success";
        } else if (json.data.account.total === 0) {
            classAccount = "text-warning";
        }

        if (json.data.calculate.total > 0) {
            classCalculate = "text-success";
        } else if (json.data.calculate.total === 0) {
            classCalculate = "text-warning";
        }

        $("#my_account_total").parent().addClass(classAccount);
        $("#my_account_pay").parent().addClass(classCalculate);
    });
}

if ($(".nome_cliente").length) {
    $(document).ready(function(){
        $(".nome_cliente").select2({
            placeholder: "Fornecedor",
            minimumInputLength: 3,
            multiple: false,
            quietMillis: 100,
            ajax: {
                url: "/charge/customers",
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
            $(".nome_cliente").val(data.text);
            $(".nome_cliente").parent().find('.select2-selection__placeholder').text(data.text);
        })
        $(".nome_cliente").parent().find('.select2-selection__placeholder').text($(".nome_cliente").val());
    });

}
