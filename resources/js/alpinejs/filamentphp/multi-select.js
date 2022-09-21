export default ({
    options,
    placeholder,
    selectedOptions,
    getOptionsUsing,
    getSearchResultsUsing,
    isAutofocused,
    loadingMessage,
    searchingMessage,
    state,
}) => ({
    optionsVisible: false,
    
    search: "",
    
    isSearching: false,

    searchingMessage: searchingMessage ?? 'Searching...',

    loadingMessage: loadingMessage ?? 'Loading...',

    placeholder: placeholder ?? 'Please select an option',

    selected: selectedOptions,

    options,

    state,

    init: async function () {
        if (isAutofocused) {
            this.openListbox()
        }

        this.$watch('search', async () => {
            this.isSearching = true;
            let search = this.search;

            this.options = await this.getOptions(search);
        })

        this.$watch('state', async () => {
            if (this.isStateBeingUpdated) {
                return
            }
        })
    },

    getOptions: async function (search) {
        if (
            search !== '' &&
            search !== null &&
            search !== undefined
        ) {
            return await getSearchResultsUsing(search)
        }

        return await getOptionsUsing()
    },

    // fixed
    formatState: function (state) {
        return (state ?? []).map((item) => item?.toString())
    },

    isSelected(option) {
        return this.selected.some(_option => _option == option.id)
    },

    filteredOptions() {
        return this.options.filter((option) => {
            return option.name.includes(this.search.toLowerCase());
        });
    },

    selectedOptions() {
        if (this.selected.length != 0) {
            return this.options.filter((option) => {
                return this.selected.some((_option) => _option == option.id)
            });
        }

        return [];
    },

    toggleSelection(option) {
        this.selected = this.isSelected(option) ? this.selected.filter(id => id != option.id) : [...this.selected, option.id]
    },

    selectOption(option) {
        this.selected = [...this.selected, option.id]
    },

    deselectOption(option) {
        this.selected = this.selected.filter((id) => id !== option.id)
    },

    openListbox() {
        this.optionsVisible = true;
    },

    closeListbox() {
        this.optionsVisible = false;
    },

    previousUp(event) {
        (event.target.previousElementSibling) ? event.target.previousElementSibling.focus(): '';
    },

    nextDown(event) {
        (event.target.nextElementSibling) ? event.target.nextElementSibling.focus(): '';
    },

    _id(t) {
        return t.replace(/\s+/g, '_')
    },

    capitalize(str) {
        if (typeof(str) != 'string') {
            return str;
        }

        return str[0].toUpperCase() + str.substring(1);
    },
})