import { Controller } from "stimulus"

export default class extends Controller {
    static targets = []
    static values = {
        userId: Number
    }

    connect() {
        this.element.querySelector('#btn-change-password').addEventListener('click', event => {
            this.validateForm(event);
        });

        this.alertBox = this.element.querySelector('#change-password-alert');
    }

    validateForm(event) {
        const passwordInput = this.element.querySelector('#password');
        const confirmPasswordInput = this.element.querySelector('#confirm_password');

        this.alertBox.classList.add('d-none');
        this.alertBox.innerHTML = '';

        if (passwordInput.value.length < 6) {
            this.alertBox.classList.remove('d-none');
            this.alertBox.innerHTML = 'Le mot de passe doit comporter au moins 6 caractères.';
            return false;
        }

        if (passwordInput.value !== confirmPasswordInput.value) {
            this.alertBox.classList.remove('d-none');
            this.alertBox.innerHTML = 'Les mots de passe doivent être identiques.';
            return false;
        }

        fetch('/users/change-password/' + this.userIdValue, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                password: passwordInput.value,
                confirmPassword: confirmPasswordInput.value
            })
        }).then(response => {
            return response.json();
        }).then(response => {
            if (response.status === 'ok') {
                const modal = bootstrap.Modal.getInstance(this.element);
                modal.hide();

                notyf.open({
                    type: 'success',
                    message: response.message
                })
            } else {
                this.alertBox.classList.remove('d-none');
                this.alertBox.innerHTML = response.message;
            }
        });
    }
}