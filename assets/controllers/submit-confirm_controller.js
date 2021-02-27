import { Controller } from 'stimulus';
import Swal from 'sweetalert2';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static values = {
        title: String,
    }

    onSubmit(event) {
        event.preventDefault();
        const formElement = event.currentTarget;

        Swal.fire({
            title: this.titleValue || 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, remove it!',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return this.removeFromCart(formElement);
            }
        });
    }

    async removeFromCart(formElement) {
        await fetch(formElement.action, {
            method: formElement.method,
            body: new URLSearchParams(new FormData(formElement)),
        });

        this.element.remove();
    }
}
