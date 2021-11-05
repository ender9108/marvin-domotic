import { Controller } from "stimulus"

export default class extends Controller {
    static targets = []
    static values = {}

    connect() {
        console.log('Controller FormValidator')

        this.element.addEventListener('submit', event => {
            this.validateForm(event)
        })

        this.element.addEventListener('appFormError', event => {
            if (event.detail && event.detail.node) {
                this.buildError(event.detail.node, event)
            }
        })
    }

    validateForm(event) {
        const fields = this.element.querySelectorAll('input:invalid, select:invalid, textarea:invalid')

        if (fields.length > 0) {
            event.preventDefault()

            fields.forEach(field => {
                this.buildError(field, event)
            })

            return false
        }
    }

    buildError(field, event) {
        const container = field.closest('div.form-group')
        const existingDivError = container.querySelector('.div-field-error')

        if (existingDivError !== null) {
            existingDivError.remove()
        }

        field.classList.add('invalid')

        const divError = document.createElement('div')
        divError.classList.add('div-field-error', 'mt-2')

        const spanBadgeError = document.createElement('span')
        spanBadgeError.classList.add('badge','bg-danger')
        spanBadgeError.innerHTML = 'erreur'

        const spanMessageError = document.createElement('span')
        spanMessageError.classList.add('pl-2')

        if (
            event !== undefined &&
            event.detail !== undefined &&
            event.detail.message !== undefined
        ) {
            spanMessageError.innerHTML = '&nbsp;' + event.detail.message
        } else {
            spanMessageError.innerHTML = '&nbsp;' + field.validationMessage
        }

        divError.append(spanBadgeError)
        divError.append(spanMessageError)

        container.append(divError)

        const tab = container.closest('div[role="tabpanel"]')

        if (tab) {
            const index = [...tab.parentElement.children].indexOf(tab);

            if (index === 0) {
                document.querySelector('.nav.nav-tabs').children[index].querySelector('a').click()
            }

            document.querySelector('.nav.nav-tabs').children[index].classList.add('tab-invalid')
        }
    }
}