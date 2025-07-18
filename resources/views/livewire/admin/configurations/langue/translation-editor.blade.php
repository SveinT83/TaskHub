<div class="translation-editor bg-white shadow-lg rounded-lg">
    <!-- Header -->
    <div class="border-b px-6 py-4">
        <h2 class="text-xl font-semibold text-gray-800">{{ __('core.ui.settings') }} - {{ __('Translation Editor') }}</h2>
    </div>

    <!-- Controls -->
    <div class="p-6 border-b bg-gray-50">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Locale Selector -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Language') }}
                </label>
                <select wire:model.live="currentLocale" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($availableLocales as $code => $name)
                        <option value="{{ $code }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Namespace Selector -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Module') }}
                </label>
                <select wire:model.live="selectedNamespace" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($availableNamespaces as $namespace)
                        <option value="{{ $namespace }}">{{ ucfirst($namespace) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- File Selector -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('File') }}
                </label>
                <select wire:model.live="selectedFile" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($availableFiles as $file)
                        <option value="{{ $file }}">{{ $file }}.php</option>
                    @endforeach
                </select>
            </div>

            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('core.ui.search') }}
                </label>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="searchTerm" 
                    placeholder="{{ __('Search translations...') }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>
        </div>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="mx-6 mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    <!-- Translations Table -->
    <div class="p-6">
        @if(empty($this->filteredTranslations))
            <div class="text-center py-8 text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('core::tables.no_data') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('No translations found for the selected criteria.') }}</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Key') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Translation') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('core.ui.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($this->filteredTranslations as $key => $value)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $key }}</code>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @if($editingKey === $key)
                                        <div class="space-y-2">
                                            <textarea 
                                                wire:model="editingValue"
                                                rows="3"
                                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('editingValue') border-red-500 @enderror"
                                            ></textarea>
                                            @error('editingValue')
                                                <p class="text-red-500 text-xs">{{ $message }}</p>
                                            @enderror
                                            <div class="flex gap-2">
                                                <button 
                                                    wire:click="saveTranslation"
                                                    class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
                                                >
                                                    {{ __('core.ui.save') }}
                                                </button>
                                                <button 
                                                    wire:click="cancelEdit"
                                                    class="px-3 py-1 bg-gray-400 text-white text-xs rounded hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500"
                                                >
                                                    {{ __('core.ui.cancel') }}
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="max-w-md">
                                            @if(empty($value))
                                                <span class="text-red-500 italic">{{ __('Empty translation') }}</span>
                                            @else
                                                {{ $value }}
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if($editingKey !== $key)
                                        <button 
                                            wire:click="editTranslation('{{ $key }}')"
                                            class="text-blue-600 hover:text-blue-900 focus:outline-none"
                                        >
                                            {{ __('core.ui.edit') }}
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="border-t px-6 py-4 bg-gray-50 text-sm text-gray-600">
        <div class="flex justify-between items-center">
            <span>
                {{ __('core.tables.showing', [
                    'from' => 1,
                    'to' => count($this->filteredTranslations),
                    'total' => count($this->filteredTranslations)
                ]) }}
            </span>
            <span>
                {{ __('Current locale') }}: <strong>{{ $availableLocales[$currentLocale] ?? $currentLocale }}</strong>
            </span>
        </div>
    </div>
</div>

@script
<script>
    $wire.on('translation-saved', (event) => {
        // Optional: Show toast notification or refresh other components
        console.log('Translation saved:', event);
    });
</script>
@endscript
