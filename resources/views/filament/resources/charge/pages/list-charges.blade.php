<div>
    <x-filament-panels::page>
        <nav
            class="fi-tabs flex max-w-full gap-x-1 overflow-x-auto mx-auto rounded-xl bg-white p-2 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10"
            role="tablist">
            <button type="button"
                    wire:click="changeMonth('subMonth')"
                    class="fi-tabs-item group flex items-center gap-x-2 rounded-lg px-3 py-2 text-sm font-medium outline-none transition duration-75 hover:bg-gray-50 focus:bg-gray-50 dark:hover:bg-white/5 dark:focus:bg-white/5"
                    role="tab">

                <span
                    class="fi-tabs-item-label transition duration-75 text-gray-500 group-hover:text-gray-700 group-focus:text-gray-700 dark:text-gray-400 dark:group-hover:text-gray-200 dark:group-focus:text-gray-200">
        {{ __('Mês anterior') }}
    </span>
            </button>

            <button type="button"
                    class="fi-tabs-item group flex items-center gap-x-2 rounded-lg px-3 py-2 text-sm font-medium outline-none transition duration-75 fi-active fi-tabs-item-active bg-gray-50 dark:bg-white/5"
                    aria-selected="aria-selected" role="tab">
                <span class="fi-tabs-item-label transition duration-75 text-primary-600 dark:text-primary-400">
        {{ __($this->day->format('F')) }}
                    @if($this->day->format('Y') != date('Y'))
                        / {{$this->day->format('Y')}}
                    @endif
    </span>
            </button>

            <button type="button"
                    wire:click="changeMonth('addMonth')"
                    class="fi-tabs-item group flex items-center gap-x-2 rounded-lg px-3 py-2 text-sm font-medium outline-none transition duration-75 hover:bg-gray-50 focus:bg-gray-50 dark:hover:bg-white/5 dark:focus:bg-white/5"
                    role="tab">
                <span
                    class="fi-tabs-item-label transition duration-75 text-gray-500 group-hover:text-gray-700 group-focus:text-gray-700 dark:text-gray-400 dark:group-hover:text-gray-200 dark:group-focus:text-gray-200">
        {{ __('Próximo mês') }}
    </span>
            </button>
        </nav>
        {{ $this->table }}
    </x-filament-panels::page>
</div>
