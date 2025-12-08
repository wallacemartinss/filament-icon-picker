@php
    $statePath = $getStatePath();
    $isDisabled = $isDisabled();
    $placeholder = $getPlaceholder();
    $availableSets = $getAvailableSets();
    $allowedSets = $getAllowedSets();
    $isSearchable = $isSearchable();
    $showSetFilter = $shouldShowSetFilter();
    $modalSize = $getModalSize();
    $gridColumns = $getGridColumns();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="{
        state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
        isOpen: false,
        search: '',
        selectedSet: '',
        icons: [],
        isLoading: false,
        isLoadingMore: false,
        hasMore: false,
        totalIcons: 0,
        currentPage: 1,
        perPage: 100,
        iconCache: {},
    
        allowedSets: @js($allowedSets ?? []),
        availableSets: @js($availableSets),
        placeholder: @js($placeholder),
        isSearchable: @js($isSearchable),
        showSetFilter: @js($showSetFilter),
        translations: {
            searchPlaceholder: @js(__('filament-icon-picker::icon-picker.search_placeholder')),
            allSets: @js(__('filament-icon-picker::icon-picker.all_sets')),
            noResults: @js(__('filament-icon-picker::icon-picker.no_results')),
            loading: @js(__('filament-icon-picker::icon-picker.loading')),
            clear: @js(__('filament-icon-picker::icon-picker.clear')),
            close: @js(__('filament-icon-picker::icon-picker.close')),
            selectIcon: @js(__('filament-icon-picker::icon-picker.select_icon')),
            cancel: @js(__('filament-icon-picker::icon-picker.cancel')),
            iconsAvailable: @js(__('filament-icon-picker::icon-picker.icons_available')),
        },
        endpoint: @js(route('filament-icon-picker.icons')),
    
        init() {
            // No watches needed - debounce on x-model handles the delay
        },

        searchDebounceTimer: null,
    
        handleSearchInput() {
            clearTimeout(this.searchDebounceTimer);
            this.searchDebounceTimer = setTimeout(() => {
                this.resetAndFetch();
            }, 400);
        },

        handleSetChange() {
            this.resetAndFetch();
        },

        // Store reference to elements that had inert removed
        _inertElements: [],
    
        async openModal() {
            this.isOpen = true;
            this.currentPage = 1;
            this.icons = [];

            // Remove inert from our modal container to allow interaction
            this.$nextTick(() => {
                const modal = document.querySelector('.fi-icon-picker-modal');
                if (modal) {
                    // Remove inert from the modal and all parents
                    let el = modal;
                    while (el) {
                        if (el.hasAttribute('inert')) {
                            el.removeAttribute('inert');
                            this._inertElements.push(el);
                        }
                        el = el.parentElement;
                    }
                }
            });
    
            await this.fetchIcons();
    
            this.$nextTick(() => {
                if (this.$refs.searchInput) {
                    this.$refs.searchInput.focus();
                }
            });
        },
    
        closeModal() {
            this.isOpen = false;

            // Restore inert to elements that had it
            this._inertElements.forEach(el => {
                el.setAttribute('inert', '');
            });
            this._inertElements = [];
        },
    
        async resetAndFetch() {
            this.currentPage = 1;
            this.icons = [];
            await this.fetchIcons();
        },
    
        async fetchIcons(append = false) {
            if (this.isLoading || (append && this.isLoadingMore)) return;
    
            if (append) {
                this.isLoadingMore = true;
            } else {
                this.isLoading = true;
            }
    
            try {
                const params = new URLSearchParams({
                    page: this.currentPage.toString(),
                    per_page: this.perPage.toString(),
                });
    
                if (this.search) {
                    params.append('search', this.search);
                }
    
                if (this.selectedSet) {
                    params.append('set', this.selectedSet);
                }
    
                if (this.allowedSets.length > 0) {
                    params.append('allowed_sets', this.allowedSets.join(','));
                }
    
                const response = await fetch(`${this.endpoint}?${params.toString()}`);
                const data = await response.json();
    
                if (append) {
                    this.icons = [...this.icons, ...data.icons];
                } else {
                    this.icons = data.icons;
                }
    
                this.hasMore = data.has_more;
                this.totalIcons = data.total;
            } catch (error) {
                console.error('Failed to fetch icons:', error);
            } finally {
                this.isLoading = false;
                this.isLoadingMore = false;
            }
        },
    
        async loadMore() {
            if (!this.hasMore || this.isLoadingMore) return;
            this.currentPage++;
            await this.fetchIcons(true);
        },
    
        handleScroll(event) {
            const el = event.target;
            // Check if scrolled near bottom (within 150px)
            if ((el.scrollTop + el.clientHeight) >= (el.scrollHeight - 150)) {
                if (this.hasMore && !this.isLoadingMore) {
                    this.loadMore();
                }
            }
        },
    
        selectIcon(icon) {
            this.state = icon.name;
            // Cache the SVG for the preview
            if (icon.svg) {
                this.iconCache[icon.name] = icon.svg;
            }
            this.closeModal();
        },
    
        clearSelection() {
            this.state = null;
        },
    
        formatSetName(setName) {
            return setName
                .replace(/-/g, ' ')
                .replace(/_/g, ' ')
                .split(' ')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                .join(' ');
        },
    
        async loadIconSvg(iconName, element) {
            // Check cache first
            if (this.iconCache[iconName]) {
                element.innerHTML = this.iconCache[iconName];
                return;
            }
    
            try {
                const response = await fetch('/filament-icon-picker/icon/' + encodeURIComponent(iconName));
                if (response.ok) {
                    const svg = await response.text();
                    this.iconCache[iconName] = svg;
                    element.innerHTML = svg;
                }
            } catch (e) {
                console.error('Failed to load icon:', iconName);
            }
        },
    
        getIconSvg(iconName) {
            return this.iconCache[iconName] || '';
        },
    }" wire:ignore class="fi-fo-icon-picker">
        {{-- Trigger Button using Filament's input wrapper structure --}}
        <x-filament::input.wrapper :disabled="$isDisabled" :valid="!$errors->has($statePath)" class="fi-fo-icon-picker-trigger cursor-pointer"
            x-on:click="openModal()">
            <button type="button" x-on:click="openModal()" @disabled($isDisabled)
                class="flex min-h-9 w-full items-center gap-x-2 rounded-lg py-1.5 ps-3 pe-3 text-start text-sm leading-6 text-gray-950 focus:ring-0 focus:outline-none dark:text-white">
                {{-- Selected Icon Preview --}}
                <template x-if="state">
                    <span class="fi-icon-picker-preview shrink-0" x-init="$watch('state', async (value) => {
                        if (value) {
                            // Try cache first
                            if (iconCache[value]) {
                                $el.innerHTML = iconCache[value];
                            } else {
                                const response = await fetch('/filament-icon-picker/icon/' + encodeURIComponent(value));
                                if (response.ok) {
                                    const svg = await response.text();
                                    iconCache[value] = svg;
                                    $el.innerHTML = svg;
                                }
                            }
                        }
                    });
                    if (state) {
                        // Try cache first for initial load
                        if (iconCache[state]) {
                            $el.innerHTML = iconCache[state];
                        } else {
                            fetch('/filament-icon-picker/icon/' + encodeURIComponent(state))
                                .then(r => r.text())
                                .then(svg => {
                                    iconCache[state] = svg;
                                    $el.innerHTML = svg;
                                });
                        }
                    }"></span>
                </template>

                {{-- Text or Placeholder (takes all available space) --}}
                <span class="fi-select-input-label flex-1 truncate text-start"
                    x-bind:class="state ? 'text-gray-950 dark:text-white' : 'text-gray-400 dark:text-gray-500'"
                    x-text="state || placeholder"></span>

                {{-- Clear Button --}}
                <template x-if="state">
                    <span x-on:click.stop="clearSelection()"
                        class="shrink-0 flex items-center justify-center rounded h-5 w-5 text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/10"
                        x-bind:title="translations.clear">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                        </svg>
                    </span>
                </template>

                {{-- Dropdown Icon --}}
                <span class="shrink-0 text-gray-400 dark:text-gray-500">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10.53 3.47a.75.75 0 0 0-1.06 0L6.22 6.72a.75.75 0 0 0 1.06 1.06L10 5.06l2.72 2.72a.75.75 0 1 0 1.06-1.06l-3.25-3.25Zm-4.31 9.81 3.25 3.25a.75.75 0 0 0 1.06 0l3.25-3.25a.75.75 0 1 0-1.06-1.06L10 14.94l-2.72-2.72a.75.75 0 0 0-1.06 1.06Z"
                            clip-rule="evenodd" />
                    </svg>
                </span>
            </button>
        </x-filament::input.wrapper>

        {{-- Modal --}}
        <template x-teleport="body">
            <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fi-icon-picker-modal fixed inset-0 flex min-h-full items-center justify-center overflow-y-auto overflow-x-hidden p-4 transition"
                style="display: none; z-index: 999999;"
                @keydown.escape.stop="closeModal()">
                {{-- Backdrop --}}
                <div class="fi-modal-close-overlay fixed inset-0 bg-gray-950/50 dark:bg-gray-950/75"
                    style="z-index: -1;"
                    x-on:click="closeModal()"></div>

                {{-- Modal Content --}}
                <div x-show="isOpen" x-trap="isOpen" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-on:click.stop
                    class="fi-modal-window pointer-events-auto relative flex w-full cursor-default flex-col bg-white shadow-xl ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 rounded-xl max-h-[90vh]"
                    style="max-width: {{ $modalSize }};">
                    {{-- Header --}}
                    <div class="fi-modal-header flex items-center justify-between gap-x-4 px-6 pt-6 pb-4">
                        <div class="flex flex-col">
                            <h2 class="fi-modal-heading text-base font-semibold leading-6 text-gray-950 dark:text-white"
                                x-text="translations.selectIcon"></h2>
                            <p class="fi-modal-description text-sm text-gray-500 dark:text-gray-400">
                                <span x-text="totalIcons"></span> <span x-text="translations.iconsAvailable"></span>
                            </p>
                        </div>

                        <button type="button" x-on:click="closeModal()"
                            class="fi-icon-btn relative flex items-center justify-center rounded-lg outline-none transition duration-75 hover:bg-gray-50 focus-visible:bg-gray-50 dark:hover:bg-white/5 dark:focus-visible:bg-white/5 -m-1.5 h-8 w-8 text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Search and Filter Row --}}
                    <div class="px-6 pb-4">
                        <div class="flex gap-3">
                            {{-- Provider Select --}}
                            <template x-if="showSetFilter && availableSets.length > 1">
                                <select x-model="selectedSet" x-on:change="handleSetChange()"
                                    class="fi-select-input block w-48 rounded-lg border-none bg-gray-50 py-2 pe-8 ps-3 text-sm text-gray-950 ring-1 ring-inset ring-gray-950/10 transition duration-75 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-white/5 dark:text-white dark:ring-white/20 dark:focus:bg-white/10 dark:focus:ring-primary-500">
                                    <option value="" x-text="translations.allSets + ' (' + totalIcons + ')'">
                                    </option>
                                    <template x-for="set in availableSets" :key="set">
                                        <option :value="set" x-text="formatSetName(set)"></option>
                                    </template>
                                </select>
                            </template>

                            {{-- Search Input --}}
                            <div x-show="isSearchable" class="relative flex-1">
                                <div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3">
                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" x-ref="searchInput" x-model="search" x-on:input="handleSearchInput()"
                                    x-bind:placeholder="translations.searchPlaceholder"
                                    class="fi-input block w-full rounded-lg border-none bg-gray-50 py-2 pe-3 ps-10 text-sm text-gray-950 ring-1 ring-inset ring-gray-950/10 transition duration-75 placeholder:text-gray-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-white/5 dark:text-white dark:ring-white/20 dark:placeholder:text-gray-500 dark:focus:bg-white/10 dark:focus:ring-primary-500" />
                            </div>
                        </div>
                    </div>

                    {{-- Icons Grid Container --}}
                    <div x-ref="iconsContainer" class="fi-modal-content overflow-y-scroll px-6 pb-4"
                        @scroll="handleScroll($event)" style="height: 560px; overflow-y: scroll;">
                        {{-- Loading State --}}
                        <div x-show="isLoading && icons.length === 0" class="flex items-center justify-center py-12">
                            <svg class="h-8 w-8 animate-spin text-primary-500" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>

                        {{-- No Results --}}
                        <div x-show="!isLoading && icons.length === 0"
                            class="flex flex-col items-center justify-center py-12 text-center">
                            <svg class="mb-4 h-12 w-12 text-gray-400 dark:text-gray-500"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.182 16.318A4.486 4.486 0 0 0 12.016 15a4.486 4.486 0 0 0-3.198 1.318M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Z" />
                            </svg>
                            <p class="text-sm text-gray-500 dark:text-gray-400" x-text="translations.noResults"></p>
                        </div>

                        {{-- Icons Grid --}}
                        <div x-show="icons.length > 0"
                            class="grid grid-cols-5 sm:grid-cols-7 md:grid-cols-9 lg:grid-cols-10 gap-3">
                            <template x-for="icon in icons" :key="icon.name">
                                <button type="button" x-on:click="selectIcon(icon)" x-bind:title="icon.name"
                                    x-bind:class="{
                                        'bg-primary-100 ring-2 ring-primary-500 text-primary-600 dark:bg-primary-500/20 dark:text-primary-400 border-primary-500': state ===
                                            icon.name,
                                        'text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:text-gray-500 dark:hover:bg-white/10 dark:hover:text-gray-300 border-gray-200 dark:border-gray-700 hover:border-gray-400 dark:hover:border-gray-500': state !==
                                            icon.name
                                    }"
                                    class="fi-icon-picker-item relative flex aspect-square items-center justify-center rounded-lg p-3 transition duration-75 outline-none focus-visible:ring-2 focus-visible:ring-primary-500 border">
                                    <span class="fi-icon-picker-icon" x-html="icon.svg"></span>
                                </button>
                            </template>
                        </div>

                        {{-- Load More Indicator --}}
                        <div x-show="isLoadingMore" class="mt-4 flex justify-center py-4">
                            <svg class="h-6 w-6 animate-spin text-primary-500" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div
                        class="fi-modal-footer flex items-center justify-end gap-x-3 px-6 py-4 border-t border-gray-100 dark:border-white/5">
                        <button type="button" x-on:click="closeModal()"
                            class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-gray fi-btn-color-gray fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20">
                            <span x-text="translations.cancel"></span>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <style>
        .fi-icon-picker-preview {
            width: 1.25rem;
            height: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fi-icon-picker-preview svg {
            width: 100% !important;
            height: 100% !important;
            max-width: 100% !important;
            max-height: 100% !important;
        }

        .fi-icon-picker-icon {
            width: 1.5rem;
            height: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fi-icon-picker-icon svg {
            width: 100% !important;
            height: 100% !important;
            max-width: 100% !important;
            max-height: 100% !important;
        }
    </style>
</x-dynamic-component>
