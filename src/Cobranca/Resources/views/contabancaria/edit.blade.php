@extends('cobranca::layouts.master')

@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='float-start'>Editar conta bancária</div>
    </div>

    <div class='card-body'>
        {!! form_start($form)!!}
        <div class='row'>
            <div class='col-6'>{!! form_row($form->banco_id) !!}</div>
            <div class='col-6'>{!! form_row($form->agencia) !!}</div>
            <div class='col-6'>{!! form_row($form->conta) !!}</div>
            <div class='col-6'>{!! form_row($form->tipo) !!}</div>
        </div>

        <div class='row'>
            <div class='col-4'>{!! form_row($form->tipo_documento) !!}</div>
            <div class='col-4'>{!! form_row($form->documento) !!}</div>
            <div class='col-4'>{!! form_row($form->ativo) !!}</div>
        </div>

        {!! form_row($form->btn) !!}
        {!! form_end($form, false)!!}
    </div>
</div>
@endsection

@section('js')
<script>
    $(function () {
        $('[name="tipo_documento"]').on('change', function () {
            const tipo = $(this).val();
            const tipoLower = tipo.toLowerCase();
            $('[for="documento"]').html($(this).data(tipoLower));
            $('[name="documento"]').unmask()
            switch (tipoLower) {
                case 'pf':
                    $('[name="documento"]').mask("999.999.999-99")
                    break;
                case 'pj':
                    $('[name="documento"]').mask("99.999.999/9999-99")
                    break;
            }
        }).trigger('change');
    })
</script>
@endsection
