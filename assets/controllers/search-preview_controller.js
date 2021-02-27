import { Controller } from 'stimulus';
import { useClickOutside, useDebounce } from 'stimulus-use';

export default class extends Controller {
    query = '';

    static values = {
        url: String,
    }

    static targets = ['result'];
    static debounces = ['search'];

    connect() {
        useClickOutside(this);
        useDebounce(this);
    }

    onSearchInput(event) {
        this.search(event.currentTarget.value);
    }

    async search(query) {
        if (!query || query === this.query) {
            return;
        }
        this.query = query;

        const params = new URLSearchParams({
            q: this.query,
            preview: 1,
        });
        const response = await fetch(`${this.urlValue}?${params.toString()}`);

        this.resultTarget.innerHTML = await response.text();
    }

    clickOutside(event) {
        this.resultTarget.innerHTML = '';
    }
}
