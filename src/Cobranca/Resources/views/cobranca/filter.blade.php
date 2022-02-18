<div class='card'>
    <div class='card-header'>
        <div class='float-start'>{{ __('Filtro de :title', ['title' => $title ?? 'cobrança']) }}</div>
        <div class='float-end'>
            <span class='m-3 mb-2 mt-4 mr-3 text-end text-success'><small>R$ </small><span id='valor'>...</span></span>
            <span class='btn btn-outline-secondary btn-sm' onclick="$(this).parent().parent().parent().find('.card-body').toggle()"><i class="fas fa-caret-down"></i></span>
        </div>
    </div>
    <form class='card-body' data-route="{{ $routeResumo }}" id='frm_filtro_cobranca' style='display:none'>
        <div class='row'>
            <div class='col-md-6 mb-3'>
                <div class='form-group'>
                    <label class='control-label'>{{ __('Data inicial') }}</label>
                    <input name='data_inicio' type='date' value="{{ request('data_inicio') ?: (new \Carbon\Carbon())->format('Y-m-d') }}" class='form-control' />
                </div>
            </div>

            <div class='col-md-6 mb-3'>
                <div class='form-group'>
                    <label class='control-label'>{{ __('Data final') }}</label>
                    <input name='data_final' type='date' value="{{ request('data_final') ?: (new \Carbon\Carbon())->format('Y-m-d') }}" class='form-control' />
                </div>
            </div>

            <div class='col-md-4 mb-3'>
                <div class='form-group'>
                    <label class='control-label'>{{ $nome ?? __('Cliente') }}
                    @if(request('nome'))
                    <a onclick="$(this).parent().parent().find('.select2-selection__placeholder').html('');$('#{{$ref}}').val('');$('[data-ref={{$ref}}]').val('');" href='#'>
                        (todos)</a>
                    @endif
                    </label>
                    <input name='nome' class='select2' data-ref="{{$ref}}" data-route="{{ $route }}" type='text' value="{{ request('nome') }}" class='form-control' />
                    <input name='entidade_id' type='hidden' id={{$ref}} value="{{ request('entidade_id') }}" />
                </div>
            </div>

            <div class='col-md-4 mb-3'>
                <div class='form-group'>
                    <label class='control-label'>Descrição</label>
                    <input name='descricao' type='text' value="{{ request('descricao') }}" class='form-control' />
                </div>
            </div>

            <div class='col-md-4 mb-3'>
                <div class='form-group'>
                    <label class='control-label'>Tipo</label>
                    <select name="type" class='form-control'>
                        <option value="0" {{request('type') == 0 ? 'selected' : ''}}>Pendentes / Vencidas</option>
                        <option value="1" {{request('type') == 1 ? 'selected' : ''}}>Pendentes</option>
                        <option value="2" {{request('type') == 2 ? 'selected' : ''}}>Pendentes / Pagas / Vencidas</option>
                        <option value="3" {{request('type') == 3 ? 'selected' : ''}}>Pendentes / Pagas</option>
                        <option value="4" {{request('type') == 4 ? 'selected' : ''}}>Pagas</option>
                        <option value="5" {{request('type') == 5 ? 'selected' : ''}}>Vencidas</option>
                    </select>
                </div>
            </div>
        </div>

        <button type='submit' class='btn btn-outline-secondary'>Buscar</button>
    </form>
</div>

<hr />

@section('js')
<script>
$(function(){
    $.get($('#frm_filtro_cobranca').data('route'), $('#frm_filtro_cobranca').serializeArray()).then(function(result){
        $('#valor').html(result.data.formatado);
    });
})
</script>
@endsection
