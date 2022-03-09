{!! form_start($form) !!}

<ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="dados-pessoais-tab" data-bs-toggle="tab" data-bs-target="#home"
            type="button" role="tab" aria-controls="home" aria-selected="true">Dados pessoais</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="dados-bancarios-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab"
            aria-controls="contact" aria-selected="false">Dados bancários</button>
    </li>
</ul>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="dados-pessoais-tab">
        <div class='row'>
            <div class='col-3'>{!! form_row($form->nome) !!}</div>
            <div class='col-3'>{!! form_row($form->tipo) !!}</div>
            <div class='col-3'>{!! form_row($form->documento) !!}</div>
            <div class='col-3'>{!! form_row($form->email) !!}</div>
        </div>
        <div class='row'>
            <div class='col-4'>{!! form_row($form->telefone) !!}</div>
            <div class='col-4'>{!! form_row($form->endereco) !!}</div>
            <div class='col-4'>{!! form_row($form->ativo) !!}</div>
        </div>
        {!! form_row($form->observacao) !!}

        {!! form_row($form->btn) !!}
    </div>
    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="dados-bancarios-tab">
        <div class='row'>
            <div class='col-4'>{!! form_row($form->banco_id) !!}</div>
            <div class='col-4'>{!! form_row($form->banco_agencia) !!}</div>
            <div class='col-4'>{!! form_row($form->banco_conta) !!}</div>
        </div>
    </div>
</div>

@section('js')
<script>
    $(function () {
        $('[name="tipo"]').on('change', function () {
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
