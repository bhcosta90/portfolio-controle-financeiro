{{-- {!! form($form)!!} --}}
<div id='frm_cobranca'>
    {!! form_start($form) !!}

    <div class='row'>
        <div class='col-md-6'>{!! form_row($form->fornecedor) !!}{!! form_row($form->entidade_id) !!}</div>
        <div class='col-md-6'>{!! form_row($form->descricao) !!}</div>
        <div class='col-md-6'>{!! form_row($form->forma_pagamento_id) !!}</div>
        <div class='col-md-6'>{!! form_row($form->conta_bancaria_id) !!}</div>
    </div>

    <div class='row'>
        <div class='col-md-6'>{!! form_row($form->data_emissao) !!}</div>
        <div class='col-md-6'>{!! form_row($form->data_vencimento) !!}</div>
    </div>

    <div class='row'>
        <div class='col-md-6'>{!! form_row($form->frequencia_id) !!}</div>
        <div class='col-md-6'>{!! form_row($form->valor_cobranca) !!}</div>
    </div>

    <fieldset>
        <legend style='cursor:pointer' onclick="$(this).parent().find('.content').slideToggle()">
            <div class='float-start'>Parcelar cobrança</div>
            <div class='float-end'><i class="fa-solid fa-caret-down"></i></div>
        </legend>
        <hr />
        <div style='display:{{ count(old('parcelas') ?? []) > 1 ? 'block' : 'none' }}' class='content'>
            <div class='row'>
                <div class='col-md-6'>{!! form_row($form->frequencia_parcela) !!}</div>
                <div class='col-md-6'>{!! form_row($form->total_parcela) !!}</div>
            </div>
            <div id="calcular_parcela" data-route="{{route('api.cobranca.frequencia.calcular', ['tenant' => tenant()])}}" data-prototype="{{ form_row($form->parcelas->prototype()) }}">
                <div style='display:{{ count(old('parcelas') ?? []) > 1 ? 'block' : 'none' }}'>
                    {!! form_row($form->parcelas) !!}
                </div>
            </div>
            <hr />
        </div>
    </fieldset>
    {!! form_row($form->observacao) !!}


    {!! form_row($form->btn) !!}

    {!! form_end($form, false) !!}
</div>

@section('js')
<script>
$(function(){
    $('#data_vencimento, #valor_cobranca, #total_parcela, #frequencia_parcela').on('change', function(){
        $('#calcular_parcela').trigger('calcular')
    }).on('keyup', function(){
        $('#calcular_parcela').trigger('calcular')
    });

    $('#calcular_parcela').on('calcular', function(){
        const el = $(this);
        if($('#total_parcela').val() > 1 && $('#valor_cobranca').val() != 0 && $('#valor_cobranca').val() != '0,00'){

            $('#frm_cobranca .btn-action').prop('disabled', true);
            $('#frm_cobranca form').prop('disabled', true);

            $.get($(this).data('route'), {
                "vencimento": $('#data_vencimento').val(),
                "valor": $('#valor_cobranca').val(),
                "parcel": $('#total_parcela').val(),
                "frequencia": $('#frequencia_parcela').val(),
            }).then(function(response){
                const container = $('#calcular_parcela');
                container.html("");
                response.data.formatado.map(function(item){
                    const count = container.children().length;
                    const proto = container.data('prototype').replace(/__NAME__/g, count);
                    const div = $("<div>", {html : proto});
                    container.append(div);
                    console.log(item)
                    $(div).find(".value").val(item.value.real)
                    $(div).find(".date").val(item.util)
                })

                numberFormat($(container));

                $('#frm_cobranca .btn-action').prop('disabled', false);
                $('#frm_cobranca form').prop('disabled', false);
            });
        } else {
            $(el).html("");
        }
    });
})
</script>
@endsection
