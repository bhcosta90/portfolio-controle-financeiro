<div class='card'>
    <div class='card-header'>
        <div class='float-start'>{{ __('Filtro de :title', ['title' => $title ?? 'cobrança']) }}</div>
        <div class='float-end'><span class='btn btn-outline-secondary btn-sm open-body'><i class="fas fa-caret-down"></i></span></div>
    </div>
    <form class='card-body' style='display:none'>
        <div class='row'>
            <div class='col-md-6 mb-3'>
                <div class='form-group'>
                    <label class='control-label'>{{ __('Start date') }}</label>
                    <input name='date_start' type='date' value="{{ request('date_start') ?: (new \Carbon\Carbon())->firstOfMonth()->format('Y-m-d') }}" class='form-control' />
                </div>
            </div>

            <div class='col-md-6 mb-3'>
                <div class='form-group'>
                    <label class='control-label'>{{ __('Final date') }}</label>
                    <input name='date_finish' type='date' value="{{ request('date_finish') ?: (new \Carbon\Carbon())->firstOfMonth()->lastOfMonth()->format('Y-m-d') }}" class='form-control' />
                </div>
            </div>

            <div class='col-md-6 mb-3'>
                <div class='form-group'>
                    <label class='control-label'>{{ $customer ?? __('Nome') }}</label>
                    <input name='customer_name' type='text' value="{{ request('customer_name') }}" class='form-control' />
                </div>
            </div>

            <div class='col-md-6 mb-3'>
                <div class='form-group'>
                    <label class='control-label'>&nbsp;</label>
                    <select name="type" class='form-control'>
                        <option value="0" {{request('type') == 0 ? 'selected' : ''}}>{{ __('Com as :title vencidas dos meses anteriores', ['title' => $title ?? 'cobrança']) }}</option>
                        <option value="1" {{request('type') == 1 ? 'selected' : ''}}>{{ __('Sem as :title vencidas dos meses anteriores', ['title' => $title ?? 'cobrança']) }}</option>
                    </select>
                </div>
            </div>
        </div>

        <button type='submit' class='btn btn-outline-secondary'>Buscar</button>
    </form>
</div>

<hr />

@empty(count($data))
    <div class='alert alert-info text-center'>{{ $empty }}</div>
@endif

@foreach($data as $rs)
<div class='card mb-1'>
    <div class='card-header {{$rs->charge->is_due && empty($rs->charge->parcel_total) ? 'text-danger' : '' }}'>
        <div class='float-start'>
            <h6 class='mb-0 {{$rs->charge->is_due && empty($rs->charge->parcel_total) ? '' : '' }}'>
                {{ $rs->charge->customer_name }} - {{ $rs->charge->resume }}
                @empty($rs->charge->parcel_total)
                    <small> - R$ {{ Str::numberEnToBr($rs->charge->value)}}</small>
                @endif
            </h6>
            @empty($rs->charge->parcel_total)
                <span class='{{$rs->charge->is_due && empty($rs->charge->parcel_total) ? '' : 'text-muted' }}'><small>{{ __('Vencimento:') }}</small> {{ Str::date($rs->charge->due_date) }}</span>
                @empty($rs->charge->recurrency_id)
                <span class='fst-italic {{$rs->charge->is_due && empty($rs->charge->parcel_total) ? '' : '' }}'>- {{$rs->charge->getTypeOptionsAttribute($type)}}</span>
                @else
                <span class='fst-italic {{$rs->charge->is_due && empty($rs->charge->parcel_total) ? '' : '' }}'>- <small>{{ __('Frequência: ') }}</small>{{$rs->charge->recurrency->name}}</span>
                @endif
            @else
                @php
                $dataParcel = $rs->parcelsActive;
                $totalParcelas = 0;
                $quantidade = count($dataParcel);

                foreach($dataParcel as $rsParcel){
                    $totalParcelas += Str::truncate($rsParcel->value);
                }

                if ($totalParcelas > $rs->charge->value) {
                    $totalParcelas = $rs->charge->value;
                }
                @endphp
                <small class='text-muted'>{{ __('Cobrança parcelada em: :totalx no valor total de: R$:valor, ainda falta pagar o total de :totalParcelas parcela(s) no valor de: :faltaPagar', [
                    'total' => $rs->charge->parcel_total,
                    'valor' => Str::numberEnToBr($rs->charge->value),
                    'totalParcelas' => $quantidade,
                    'faltaPagar' => Str::numberEnToBr($totalParcelas),
                ]) }}
                </small>
            @endif
        </div>
        <div class='float-end'>
            @empty($rs->charge->parcel_total)
            <div class="btn-group" role="group" aria-label="Basic example">
                {!! btnLinkEditIcon(route('charge.edit', $rs->charge->uuid)) !!}
                {!! btnLinkDelIcon(route('charge.destroy', $rs->charge->uuid)) !!}
                {!! btnLinkIcon(route('charge.pay.create', $rs->charge->uuid), 'fas fa-check', '', 'btn-sm btn-outline-success') !!}
            </div>
            @else
                <span class='btn btn-outline-secondary btn-sm open-table'><i class="fas fa-caret-down"></i></span>
            @endif
        </div>
    </div>
    @if($rs->charge->parcel_total)
    <table class='table mb-0 table-parcel table-responsive-md' style='display:none'>
        <tr>
            <th>Parcela</th>
            <th>Vencimento</th>
            <th>Valor</th>
            <th class='min'></th>
            <th class='min'></th>
            <th class='min'></th>
        </tr>

        @foreach($dataParcel as $rsParcel)
            <tr class='{{ $rsParcel->is_due ? "text-danger" : '' }}'>
                <td>{{ __($rsParcel->resume, ['actual' => $rsParcel->parcel_actual]) }}</td>
                <td>{{ Str::date($rsParcel->due_date) }}</td>
                <td>{{ Str::numberEnToBr($rsParcel->value) }}</td>
                <td>{!! btnLinkEditIcon(route('charge.edit', $rsParcel->uuid)) !!}</td>
                <td>{!! btnLinkDelIcon(route('charge.destroy', $rsParcel->uuid)) !!}</td>
                <td>{!! btnLinkIcon(route('charge.pay.create', $rsParcel->uuid), 'fas fa-check', '', 'btn-sm btn-outline-success') !!}</td>
            </tr>
        @endforeach
    </table>
    @endif
</div>
@endforeach

@if(is_object($data) && class_basename(get_class($data)) == 'LengthAwarePaginator')
    {{-- Collection is paginated, so render that --}}
    {!! $data->appends(request()->except(['token']))->render() !!}
@endif

@section('js')
<script>
$(function(){
    $('.open-table').on('click', function(){
        $(this).parents('.card').find('.table-parcel').toggle()
    });

    $('.open-body').on('click', function(){
        $(this).parents('.card').find('.card-body').toggle()
    });
})
</script>
@endsection
