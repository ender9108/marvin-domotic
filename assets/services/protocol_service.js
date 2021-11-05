import {Notyf} from "notyf";
import notyfConfig from "../modules/notyf-config";

export default class {
    constructor() {
        this.notyf = new Notyf(notyfConfig);
    }

    parseMessage(message) {
        console.log(message)
        for (const [selector, state] of Object.entries(message)) {
            const parts = selector.split('_');

            switch (parts[1]) {
                case 'state':
                    return this.updateState(selector, state);
                    break;
                case 'permit-join':
                    return this.updatePermitJoin(selector, state)
                    break;
            }
        }

        return [];
    }

    updateState(selector, state) {
        let result = [];

        document.querySelectorAll('.' + selector).forEach(element => {
            element.classList.remove('bg-success');
            element.classList.remove('bg-danger');

            const container = element.closest('.protocol-info');
            const titleParts = container.getAttribute('title').split(' - ');
            container.setAttribute('title', titleParts[0] + ' - ' + state);
            const addingModuleAllowed = container.querySelector('.protocol-adding-module-allowed');

            switch (state) {
                case 'online':
                    element.classList.add('bg-success');
                    element.classList.add('bg-success');
                    addingModuleAllowed.classList.remove('d-none');

                    result.push({
                        type: 'success',
                        message: 'Protocol ' + titleParts[0] + ' ' + titleParts[1]
                    });
                    break;
                case 'offline':
                    element.classList.add('bg-danger');
                    element.classList.add('bg-danger');
                    addingModuleAllowed.classList.add('d-none');
                    result.push({
                        type: 'error',
                        message: 'Protocol ' + titleParts[0] + ' ' + titleParts[1]
                    });
                    break;
                default:
                    element.classList.add('bg-secondary');
                    element.classList.add('bg-secondary');
                    addingModuleAllowed.classList.add('d-none');
                    result.push({
                        type: 'error',
                        message: 'Protocol ' + titleParts[0] + ' ' + titleParts[1]
                    });
                    break;
            }
        });

        return result;
    }

    updatePermitJoin(selector, state) {
        let result = [];
        const icon = document.querySelector('.' + selector);
        const container = icon.closest('.protocol-info');
        const titleParts = container.getAttribute('title').split(' - ');
        const link = container.querySelector('.protocol-adding-module-allowed');
        const text = link.querySelector('.state-label')

        if (icon !== null) {
            if (true === state) {
                icon.classList.remove('d-none');
                text.innerHTML = link.dataset.stateOff;
                result.push({
                    type: 'info',
                    message: 'Protocol ' + titleParts[0] + ' - mode inclusion activé',
                });
            } else {
                icon.classList.add('d-none');
                text.innerHTML = link.dataset.stateOn;
                result.push({
                    type: 'info',
                    message: 'Protocol ' + titleParts[0] + ' - mode inclusion désactivé',
                });
            }
        }

        return result;
    }
}