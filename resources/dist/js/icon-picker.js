export default function iconPicker({
    state,
    allowedSets = [],
    availableSets = [],
    placeholder = '',
    isSearchable = true,
    showSetFilter = true,
    translations = {},
    endpoint = '',
}) {
    return {
        state,
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
        iconSvgCache: {},
        currentIconSvg: '',

        // Config
        allowedSets,
        availableSets,
        placeholder,
        isSearchable,
        showSetFilter,
        translations,
        endpoint,

        init() {
            // Watch for state changes to update the icon preview
            this.$watch('state', (value) => {
                if (value) {
                    this.loadCurrentIconSvg();
                } else {
                    this.currentIconSvg = '';
                }
            });

            // Initial load of current icon if set
            if (this.state) {
                this.loadCurrentIconSvg();
            }

            // Watch search and filter changes
            this.$watch('search', () => {
                this.resetAndFetch();
            });

            this.$watch('selectedSet', () => {
                this.resetAndFetch();
            });
        },

        async openModal() {
            this.isOpen = true;
            document.body.style.overflow = 'hidden';

            if (this.icons.length === 0) {
                await this.fetchIcons();
            }

            // Focus search input
            this.$nextTick(() => {
                const searchInput = document.querySelector('.filament-icon-picker-modal input[type="text"]');
                if (searchInput) {
                    searchInput.focus();
                }
            });
        },

        closeModal() {
            this.isOpen = false;
            document.body.style.overflow = '';
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
            const container = event.target;
            const scrollTop = container.scrollTop;
            const scrollHeight = container.scrollHeight;
            const clientHeight = container.clientHeight;

            // Load more when near bottom (100px threshold)
            if (scrollTop + clientHeight >= scrollHeight - 100) {
                this.loadMore();
            }
        },

        selectIcon(icon) {
            this.state = icon.name;
            this.closeModal();
        },

        clearSelection() {
            this.state = null;
            this.currentIconSvg = '';
        },

        formatSetName(setName) {
            return setName
                .replace(/-/g, ' ')
                .replace(/_/g, ' ')
                .split(' ')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                .join(' ');
        },

        async loadCurrentIconSvg() {
            if (!this.state) return;
            this.currentIconSvg = await this.getIconSvgAsync(this.state);
        },

        getIconSvg(iconName) {
            if (this.iconSvgCache[iconName]) {
                return this.iconSvgCache[iconName];
            }

            // Return placeholder and load async
            this.getIconSvgAsync(iconName);

            return `<svg class="animate-pulse w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><rect width="24" height="24" rx="4"/></svg>`;
        },

        async getIconSvgAsync(iconName) {
            if (this.iconSvgCache[iconName]) {
                return this.iconSvgCache[iconName];
            }

            try {
                // Try to find the icon in the DOM (Filament renders blade icons)
                // Or use a simple fetch approach
                const response = await fetch(`/filament-icon-picker/icon/${encodeURIComponent(iconName)}`);

                if (response.ok) {
                    const svg = await response.text();
                    this.iconSvgCache[iconName] = svg;
                    return svg;
                }
            } catch (error) {
                console.warn('Failed to load icon:', iconName);
            }

            // Fallback SVG
            const fallbackSvg = `<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>`;
            this.iconSvgCache[iconName] = fallbackSvg;
            return fallbackSvg;
        },
    };
}
