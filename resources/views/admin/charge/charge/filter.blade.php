<div class="card">
    <div class="card-header">
        <div class="float-left">Filtro de cobrança</div>
        <div class="float-right">
            <span class="m-3 mb-2 mt-4 mr-3 text-end text-success"><small>R$ </small><span id="valor">{{ str()->numberEnToBr($total) }}</span></span>
            <span class="btn btn-outline-secondary btn-sm" onclick="$(this).parent().parent().parent().find('.card-body').toggle()"><i class="fas fa-caret-down"></i></span>
        </div>
    </div>
    <form class="card-body" style="display:none">
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="form-group">
                    <label class="control-label">Data inicial</label>
                    <input name="date_start" type="date" value="{{request('date_start') ?? \Carbon\Carbon::now()->firstOfMonth()->format('Y-m-d')}}" class="form-control">
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="form-group">
                    <label class="control-label">Data final</label>
                    <input name="date_finish" type="date" value="{{request('date_finish') ?? \Carbon\Carbon::now()->lastOfMonth()->format('Y-m-d')}}" class="form-control">
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="form-group">
                    <label class="control-label">{{ $title ?? "Relação comercial" }}</label>
                    <input name="name" value="{{ request('name') }}" class="form-control" />
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="form-group">
                    <label class="control-label">Descrição</label>
                    <input name="description" value="{{ request('description') }}" type="text" value="" class="form-control">
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="form-group">
                    <label class="control-label">Tipo</label>
                    <select name="type" class="form-control">
                        <option value="0" {{ request('type') == '0' ? "selected" : ""}}>Pendentes / Vencidas</option>
                        <option value="1" {{ request('type') == '1' ? "selected" : ""}}>Pendentes</option>
                        <option value="2" {{ request('type') == '2' ? "selected" : ""}}>Pendentes / Pagas / Vencidas</option>
                        <option value="3" {{ request('type') == '3' ? "selected" : ""}}>Pendentes / Pagas</option>
                        <option value="4" {{ request('type') == '4' ? "selected" : ""}}>Pagas</option>
                        <option value="5" {{ request('type') == '5' ? "selected" : ""}}>Vencidas</option>
                    </select>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-outline-secondary">Buscar</button>
    </form>
</div>