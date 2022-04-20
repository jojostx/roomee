export default class Dropdown {
    constructor(labels, checkboxes) {
        this.labels = labels;
        this.checkboxes = checkboxes;
    }

    addLabelListeners() {
        for (let label of this.labels) {
            label.addEventListener('click', (e) => {
                e.stopPropagation();
                label.parentElement.classList.add('hidden');
            })
        }

        return this;
    }

    addCheckboxListeners() {
        for (let dislike of this.checkboxes) {
            dislike.addEventListener('change', (e) => {
                e.stopPropagation();
                if (dislike.checked) {
                    dislike.labels[1].parentElement.classList.add('inline-flex');
                    dislike.labels[1].parentElement.classList.remove('hidden');
                } else {
                    dislike.labels[1].parentElement.classList.add('hidden');
                    dislike.labels[1].parentElement.classList.remove('inline-flex');
                }
            })
        }

        return this;
    }

    addDOMListener() {
        window.addEventListener('DOMContentLoaded', () => {
            for (let option of this.checkboxes) {
                if (option.checked) {
                    option.labels[1].parentElement.classList.add('inline-flex');
                    option.labels[1].parentElement.classList.remove('hidden');
                }
            }
        })

        return this;
    }
}