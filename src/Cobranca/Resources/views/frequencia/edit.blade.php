@extends('cobranca::layouts.master')

@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='float-start'>Editar forma de pagamento</div>
    </div>

    <div class='card-body'>
        {!! form($form)!!}
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
