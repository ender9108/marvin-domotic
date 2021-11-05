import { Controller } from "stimulus";
import {Notyf} from "notyf";
import notyfConfig from "../modules/notyf-config";

export default class extends Controller {
    static targets = []
    static values = {
        messages: Object
    }

    connect() {
        console.log('Controller FlashMessage');

        const notifier = new Notyf(notyfConfig);

        if (this.messagesValue.success !== undefined && this.messagesValue.success.length > 0) {
            this.messagesValue.success.forEach(message => {
                notifier.success(message);
            })
        }

        if (this.messagesValue.error !== undefined && this.messagesValue.error.length > 0) {
            this.messagesValue.error.forEach(message => {
                notifier.error(message);
            })
        }

        if (this.messagesValue.info !== undefined && this.messagesValue.info.length > 0) {
            this.messagesValue.info.forEach(message => {
                notifier.open({
                    type: 'info',
                    message: message,
                    duration: 4000
                });
            })
        }

        if (this.messagesValue.warning !== undefined && this.messagesValue.warning.length > 0) {
            this.messagesValue.warning.forEach(message => {
                notifier.open({
                    type: 'warning',
                    message: message,
                    duration: 4000
                });
            })
        }
    }
}