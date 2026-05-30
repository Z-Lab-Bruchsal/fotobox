<div class="w-full max-w-2xl mt-10">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-sm font-medium text-gray-400 tracking-wide uppercase">Foto-Einstellungen</h2>
        <button wire:click="$set('showForm', !$showForm)"
                class="text-xs px-3 py-1 rounded bg-gray-800 text-gray-300 hover:bg-gray-700 transition-colors">
            {{ $showForm ? 'Abbrechen' : '+ Neu' }}
        </button>
    </div>

    @if ($showForm)
        <div class="mb-4 p-4 rounded-xl bg-gray-900 border border-gray-700 space-y-2">
            <input wire:model="newName" type="text" placeholder="Name (z.B. Ölgemälde)"
                   class="w-full px-3 py-2 text-sm rounded-lg bg-gray-800 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:border-gray-500">
            <input wire:model="newCommand" type="text" placeholder="GMIC-Befehl (z.B. -to_gray)"
                   class="w-full px-3 py-2 text-sm rounded-lg bg-gray-800 border border-gray-700 text-white placeholder-gray-500 font-mono focus:outline-none focus:border-gray-500">
            <button wire:click="addSetting"
                    class="px-4 py-2 text-sm rounded-lg bg-white text-gray-950 font-medium hover:bg-gray-200 transition-colors">
                Hinzufügen
            </button>
        </div>
    @endif

    <div class="space-y-2">
        @foreach ($settings as $setting)
            <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-900 border border-gray-800">
                <button wire:click="toggleActive({{ $setting->id }})"
                        class="flex-shrink-0 w-9 h-5 rounded-full transition-colors {{ $setting->is_active ? 'bg-green-500' : 'bg-gray-700' }} relative">
                    <span class="absolute top-0.5 left-0.5 w-4 h-4 rounded-full bg-white transition-transform {{ $setting->is_active ? 'translate-x-4' : 'translate-x-0' }}"></span>
                </button>

                @if (isset($editingId[$setting->id]))
                    <div class="flex-1 flex gap-2">
                        <input wire:model="editName.{{ $setting->id }}" type="text"
                               class="flex-1 px-2 py-1 text-sm rounded bg-gray-800 border border-gray-600 text-white focus:outline-none">
                        <input wire:model="editCommand.{{ $setting->id }}" type="text"
                               class="flex-1 px-2 py-1 text-sm rounded bg-gray-800 border border-gray-600 text-white font-mono focus:outline-none">
                        <button wire:click="saveEdit({{ $setting->id }})"
                                class="px-3 py-1 text-xs rounded bg-green-700 text-white hover:bg-green-600">Speichern</button>
                        <button wire:click="cancelEdit({{ $setting->id }})"
                                class="px-3 py-1 text-xs rounded bg-gray-700 text-white hover:bg-gray-600">×</button>
                    </div>
                @else
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white">{{ $setting->name }}</p>
                        <p class="text-xs text-gray-500 font-mono truncate">{{ $setting->gmic_command }}</p>
                    </div>
                    <div class="flex gap-2 flex-shrink-0">
                        <button wire:click="startEdit({{ $setting->id }}, {{ Js::from($setting->name) }}, {{ Js::from($setting->gmic_command) }})"
                                class="p-1.5 rounded text-gray-400 hover:text-white hover:bg-gray-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                            </svg>
                        </button>
                        <button wire:click="delete({{ $setting->id }})" wire:confirm="Einstellung löschen?"
                                class="p-1.5 rounded text-gray-600 hover:text-red-400 hover:bg-gray-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                @endif
            </div>
        @endforeach

        @if ($settings->isEmpty())
            <p class="text-sm text-gray-600 text-center py-4">Keine Einstellungen vorhanden.</p>
        @endif
    </div>
</div>
