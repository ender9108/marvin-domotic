import { Controller } from "stimulus"

export default class extends Controller {
    static targets = ['uploadField']
    static values = {
        mimetypes: Array
    }

    connect() {
        console.log('Controller SingleImageUploader');

        this.file = null;
        this.form = this.element.closest('form');
    }

    inputChange(event) {
        if (this.uploadFieldTarget.files.length === 1) {
            this.file = this.uploadFieldTarget.files[0]
            this.handleFile(event)
        } else {
            const error = this.element.querySelector('#avatar-upload-error')
            error.innerHTML = 'Vous devez sélectionner un seul fichier'
            error.classList.remove('d-none')
        }
    }

    handleFile(event) {
        if (this.file) {
            const error = this.element.querySelector('#avatar-upload-error')
            error.classList.add('d-none')

            if (this.mimetypesValue.length > 0 && false === this.mimetypesValue.includes(this.file.type)) {
                error.innerHTML = 'Type de fichier non supporté'
                error.classList.remove('d-none')
            } else {
                const reader = new FileReader()
                const image = this.element.querySelector('#imagePreview')

                reader.onload = e => {
                    image.style.backgroundImage = 'url(' + e.target.result + ')';
                }

                reader.readAsDataURL(this.file)

                const formEvent = new CustomEvent('appFormChange');
                this.form.dispatchEvent(formEvent);
                console.log('Send appFormChange event !')
            }
        }
    }
}