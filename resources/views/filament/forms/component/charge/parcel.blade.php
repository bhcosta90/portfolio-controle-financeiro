<div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative text-center">
    {{ __('Valor da parcela: :value', ['value' => money($this->value() * 100)]) }}
</div>
