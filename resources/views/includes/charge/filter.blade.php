<form class='card-body'>
    <div class='row'>
        <div class='col-6'>
            <div class='form-group'>
                <label class='control-label'>{{ __('Start date') }}</label>
                <input name='date_start' type='date' value="{{ request('date_start') ?: (new \Carbon\Carbon())->firstOfMonth()->format('Y-m-d') }}" class='form-control' />
            </div>
        </div>

        <div class='col-6'>
            <div class='form-group'>
                <label class='control-label'>{{ __('Final date') }}</label>
                <input name='date_finish' type='date' value="{{ request('date_finish') ?: (new \Carbon\Carbon())->firstOfMonth()->addMonth()->lastOfMonth()->format('Y-m-d') }}" class='form-control' />
            </div>
        </div>

        <div class='col-6'>
            <div class='form-group'>
                <label class='control-label'>{{ __('Customer name') }}</label>
                <input name='customer_name' type='text' value="{{ request('customer_name') }}" class='form-control' />
            </div>
        </div>

        <div class='col-6'>
            <div class='form-group'>
                <label class='control-label'>&nbsp;</label>
                <select name="future" class='form-control'>
                    <option value="0" {{request('future') == 0 ? 'selected' : ''}}>{{ __('Only this month') }}</option>
                    <option value="1" {{request('future') == 1 ? 'selected' : ''}}>{{ __('With the charges of the future')}}</option>
                </select>
            </div>
        </div>
    </div>

    <button type='submit' class='btn btn-outline-secondary'>Buscar</button>

    @empty(request()->future ?? 0)
        <span class='text-muted'><small>contém {{$future}} cobrança(s) no futuro</small></span>
    @endif
</form>
