<div x-data="{ toast: @entangle('toast') }" class="space-y-4">

    <template x-if="toast">
        <div>
            <x-toast x-bind:type="toast.type">
                <span x-text="toast.message">

                </span>
            </x-toast>
        </div>
    </template>

    <!-- FORM (wire:submit.prevent) -->
    <form wire:submit.prevent="submit" class="space-y-4" enctype="multipart/form-data">

            <x-validation-errors />
        <div>
            <x-label class="mb-1">Año</x-label>
            <x-input type="text" wire:model="anio" class="border rounded px-3 py-2 w-full" readonly />
        </div>


        <div>
            <x-label class="mb-1">Nombre</x-label>
            <x-input type="text" wire:model.defer="nombre" class="border rounded px-3 py-2 w-full" />
            {{-- @error('nombre') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror --}}
        </div>

        <div>
            <x-label class="mb-1">Apellido</x-label>
            <x-input type="text" wire:model.defer="apellido" class="border rounded px-3 py-2 w-full" />
        </div>

        <div>
            <x-label class="mb-1">Email</x-label>
            <x-input type="email" wire:model.defer="email" class="border rounded px-3 py-2 w-full" />
            
        </div>

        <div>
            <x-label class="mb-1">Teléfono</x-label>
            <x-input type="number" wire:model.defer="telefono" class="border rounded px-3 py-2 w-full" />
        </div>

        <div>
            <x-label class="mb-1">CUI</x-label>
            <x-input type="text" wire:model.defer="cui" class="border rounded px-3 py-2 w-full" maxlength="13" />
        </div>

        <div>
            <x-label class="mb-1">Domicilio</x-label>
            <x-input type="text" wire:model.defer="domicilio" class="border rounded px-3 py-2 w-full" />
        </div>

        <div>
            <x-label class="block text-sm font-medium mb-1">Observaciones (opcional)</x-label>
            <x-textarea wire:model.defer="observaciones" class="border rounded px-3 py-2 w-full" rows="3"></x-textarea>
        </div>

        <div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Enviar
            </button>
        </div>

    </form>
</div>
