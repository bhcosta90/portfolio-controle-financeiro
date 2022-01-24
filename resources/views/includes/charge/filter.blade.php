<form class='card-body'>
    <div class='row'>
        <div class='col-4'>
            <div class='form-group'>
                <label class='control-label'>{{ __('Start date') }}</label>
                <input name='date_start' type='date' value="{{ request('date_start') ?: (new \Carbon\Carbon())->firstOfMonth()->format('Y-m-d') }}" class='form-control' />
            </div>
        </div>

        <div class='col-4'>
            <div class='form-group'>
                <label class='control-label'>{{ __('Final date') }}</label>
                <input name='date_finish' type='date' value="{{ request('date_finish') ?: (new \Carbon\Carbon())->firstOfMonth()->addMonth()->lastOfMonth()->format('Y-m-d') }}" class='form-control' />
            </div>
        </div>

        <div class='col-4'>
            <div class='form-group'>
                <label class='control-label'>{{ __('Customer name') }}</label>
                <input name='customer_name' type='text' value="{{ request('customer_name') }}" class='form-control' />
            </div>
        </div>
    </div>

    <button type='submit' class='btn btn-outline-secondary'>Buscar</button>
</form>
