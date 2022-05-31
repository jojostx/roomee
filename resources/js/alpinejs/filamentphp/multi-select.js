export default ({
    options,
    selectedOptions
}) => ({
    optionsVisible: false,

    search: "",

    selected: selectedOptions,

    options: options,

    capitalize(str) {
        if (typeof(str) != 'string') {
            return str;
        }

        return str[0].toUpperCase() + str.substring(1);
    },

    openListbox() {
        this.optionsVisible = true;
    },

    closeListbox() {
        this.optionsVisible = false;
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

    selectOption(option) {
        this.selected = this.isSelected(option) ? this.selected.filter(id => id != option.id) : [...this.selected, option.id]
    },

    deselectOption(optionToDeselect) {
        this.selected = this.selected.filter((id) => id !== optionToDeselect.id)
    },

    isSelected(option) {
        return this.selected.some(_option => _option == option.id)
    },

    _id(t) {
        return t.replace(/\s+/g, '_')
    }
})