<div
    x-data="autofirmaModal(@js($encodedData), @js($pluginConfig))"
    x-init="init()"
    wire:ignore.self
>
    {{-- Estado: idle --}}
    <div x-show="status === 'idle'" class="flex flex-col items-center gap-4 py-4">
        <x-heroicon-o-pencil-square class="h-12 w-12 text-primary-500" />
        <p class="text-sm text-gray-600 dark:text-gray-400">
            {{ __('autofirma-plugin::autofirma-plugin.modal.ready') }}
        </p>
        <x-filament::button
            color="primary"
            wire:loading.attr="disabled"
            x-on:click="sign()"
        >
            {{ __('autofirma-plugin::autofirma-plugin.modal.sign_button') }}
        </x-filament::button>
    </div>

    {{-- Estado: loading --}}
    <div x-show="status === 'loading'" class="flex flex-col items-center gap-4 py-4">
        <x-filament::loading-indicator class="h-8 w-8 text-primary-500" />
        <p class="text-sm text-gray-600 dark:text-gray-400">
            {{ __('autofirma-plugin::autofirma-plugin.modal.waiting') }}
        </p>
    </div>

    {{-- Estado: signed --}}
    <div x-show="status === 'signed'" class="flex flex-col items-center gap-4 py-4">
        <x-heroicon-o-check-circle class="h-12 w-12 text-success-500" />
        <p class="text-sm font-medium text-success-600 dark:text-success-400">
            {{ __('autofirma-plugin::autofirma-plugin.modal.signed') }}
        </p>
    </div>

    {{-- Estado: error --}}
    <div x-show="status === 'error'" class="flex flex-col items-center gap-4 py-4">
        <x-heroicon-o-exclamation-circle class="h-12 w-12 text-danger-500" />
        <p class="text-sm text-danger-600 dark:text-danger-400" x-text="errorMessage"></p>
        <x-filament::button color="gray" x-on:click="status = 'idle'">
            {{ __('autofirma-plugin::autofirma-plugin.modal.retry') }}
        </x-filament::button>
    </div>
</div>
