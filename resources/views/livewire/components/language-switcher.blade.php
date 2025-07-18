<div class="relative inline-block text-left" x-data="{ open: false }">
    <button 
        type="button" 
        @click="open = !open"
        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
    >
        <!-- Current language flag/icon -->
        <span class="mr-2">
            @switch($currentLocale)
                @case('no')
                    🇳🇴
                    @break
                @case('da')
                    🇩🇰
                    @break
                @case('sv')
                    🇸🇪
                    @break
                @default
                    🇬🇧
            @endswitch
        </span>
        {{ $availableLocales[$currentLocale] ?? $currentLocale }}
        <!-- Dropdown arrow -->
        <svg class="ml-2 -mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    </button>

    <!-- Dropdown menu -->
    <div 
        x-show="open" 
        @click.outside="open = false"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
    >
        <div class="py-1">
            @foreach($availableLocales as $locale => $name)
                <button
                    wire:click="switchLanguage('{{ $locale }}')"
                    @click="open = false"
                    class="group flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 {{ $locale === $currentLocale ? 'bg-gray-50 font-medium' : '' }}"
                >
                    <span class="mr-3">
                        @switch($locale)
                            @case('no')
                                🇳🇴
                                @break
                            @case('da')
                                🇩🇰
                                @break
                            @case('sv')
                                🇸🇪
                                @break
                            @default
                                🇬🇧
                        @endswitch
                    </span>
                    {{ $name }}
                    @if($locale === $currentLocale)
                        <svg class="ml-auto h-4 w-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    @endif
                </button>
            @endforeach
        </div>
    </div>
</div>
