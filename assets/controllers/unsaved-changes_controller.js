import { Controller } from "stimulus"

const LEAVING_PAGE_MESSAGE = 'Vous n\'avez pas sauvegardées vos données. Voulez-vous quitter ?'

export default class extends Controller {
    static targets = []
    static values = {}

    connect() {
        console.log('Controller UnsavedChangesController');

        this.formChanged = false
        window.addEventListener("turbo:before-visit", (event) => this.leavingPage(event));
        window.addEventListener("beforeunload", (event) => this.leavingPage(event));

        this.btnSave = document.querySelector('#btn-form-save')
        this.formSerialized = this.serialize(new FormData(this.element))

        this.element.addEventListener('submit', (event) => {
            this.resetEvent()
        })

        this.element.addEventListener('change', (event) => {
            this.onChange(event)
        })

        this.element.addEventListener('appFormChange', (event) => {
            this.onChange(event)
        })
    }

    leavingPage(event) {
        if (true === this.formChanged) {
            if (event.type === "turbo:before-visit") {
                if (!window.confirm(LEAVING_PAGE_MESSAGE)) {
                    event.preventDefault()
                } else {
                    this.resetEvent()
                }
            } else {
                event.returnValue = LEAVING_PAGE_MESSAGE;
                return event.returnValue;
            }
        }
    }

    onChange(event) {
        const currentFormSerialized = this.serialize(new FormData(this.element))

        if (this.formSerialized !== currentFormSerialized) {
            this.formChanged = true

            if (this.btnSave !== null) {
                this.btnSave.classList.remove('btn-success')
                this.btnSave.classList.add('btn-warning')
            }
        } else {
            this.formChanged = false

            if (this.btnSave) {
                this.btnSave.classList.add('btn-success')
                this.btnSave.classList.remove('btn-warning')
            }
        }
    }

    serialize(data) {
        let obj = {}

        for (let [key, value] of data) {
            if (obj[key] !== undefined) {
                if (!Array.isArray(obj[key])) {
                    obj[key] = [obj[key]]
                }
                obj[key].push(value);
            } else {
                obj[key] = value
            }
        }

        return JSON.stringify(obj)
    }

    resetEvent() {
        this.formChanged = false
        window.removeEventListener('beforeunload', (event) => this.leavingPage(event))
        window.removeEventListener('turbo:before-visit', (event) => this.leavingPage(event))
    }
}