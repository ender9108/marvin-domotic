import { Controller } from "stimulus";
import {Notyf} from "notyf"
import notyfConfig from "../modules/notyf-config"
import configuration from "../config/marvin"

export default class extends Controller {
    static targets = []
    static values = {
        websocketHost: String,
        websocketPort: String,
        websocketSsl: Boolean
    }

    connect() {
        console.log('Controller MessageCenter');
        this.socket = null;
        this.notifier = new Notyf(notyfConfig);
        this.services = {};

        for (const [key, value] of Object.entries(configuration.services)) {
            this.services[key] = value;
        }
    }

    websocketConnect() {
        try {
            this.socket = new WebSocket(
                (this.websocketSslValue.ssl === true ? 'wss' : 'ws') +
                '://' +
                this.websocketHostValue +
                (this.websocketPortValue === '' ? '' : ':' + this.websocketPortValue)
            );

            this.socket.addEventListener('open', function (event) {});
            this.socket.addEventListener('message', event => {
                this.onMessage(event);
            })
        } catch (error) {
            console.error(error);
        }
    }

    onMessage(event) {
        console.log('Voici un message du serveur', event.data);

        try {
            const message = JSON.parse(event.data);
            console.log(message)

            if (message.update_interface !== undefined) {
                console.log('Update interface !')
                console.log(this.services[message.update_interface.type])

                if (
                    this.services[message.update_interface.type] !== undefined &&
                    message.update_interface.data !== undefined &&
                    typeof this.services[message.update_interface.type].parseMessage === "function"
                ) {
                    this.services[message.update_interface.type].parseMessage(message.update_interface.data)
                }
            }
        } catch (error) {
            console.error(error)
        }
    }
}
