import { Controller } from "stimulus"
import $ from "jquery";
import "datatables.net-bs5"
import {Notyf} from "notyf"
import notyfConfig from "../modules/notyf-config"

export default class extends Controller {
    static targets = []
    static values = {
        pageLength: Number,
        lengthChange: Boolean,
        searching: Boolean,
        info: Boolean,
        paging: Boolean,
        secureDelete: Boolean
    }

    initialized() {

    }

    connect() {
        if (!this.isBooting()) {
            return false
        }

        this.notifier = new Notyf(notyfConfig);

        document.addEventListener('turbo:before-render', this._teardown)

        this.dataTable = $(this.element)
            .DataTable({
                responsive: true,
                pagingType: 'simple_numbers',
                pageLength: this.pageLengthValue === 0 ? 10 : this.pageLengthValue,
                lengthChange: this.lengthChangeValue,
                searching: this.searchingValue,
                info: this.infoValue,
                paging: this.pagingValue,
                ordering: true,
                order: [],
                aaSorting: [],
                columnDefs: [{
                    targets: 'no-sort',
                    orderable: false
                }, {
                    targets: 'no-searchable',
                    searchable: false
                }],
                language: {
                    processing: "Traitement en cours ...",
                    search: "Rechercher&nbsp;:",
                    lengthMenu: "Afficher _MENU_ &eacute;l&eacute;ments",
                    info: "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                    infoEmpty: "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
                    infoFiltered: "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                    infoPostFix: "",
                    loadingRecords: "Chargement en cours ...",
                    zeroRecords: "Aucun &eacute;l&eacute;ment &agrave; afficher",
                    emptyTable: "Aucune donnée disponible",
                    paginate: {
                        first: "Premier",
                        previous: "Pr&eacute;c&eacute;dent",
                        next: "Suivant",
                        last: "Dernier"
                    },
                    aria: {
                        sortAscending: ": activer pour trier la colonne par ordre croissant",
                        sortDescending: ": activer pour trier la colonne par ordre décroissant"
                    }
                }
            });

        this.dataTable.on('click', '.btn-delete-table-line', element => {
            this.deleteLine(element.originalEvent, element.target);
        });
    }

    deleteLine(event, button) {
        event.stopPropagation();
        event.preventDefault();

        if (button.tagName.toUpperCase() !== 'A') {
            button = button.closest('a')
        }

        const url = button.dataset.url;
        const token = button.dataset.token;
        const message = button.dataset.message;

        if (confirm(message)) {
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    '_token': token
                })
            }).then(response => {
                return response.json();
            }).then(response => {
                if (response.status === 'ok') {
                    this.notifier.success(response.message);
                    button.closest('tr').remove();

                    if (this.secureDeleteValue && this.element.querySelectorAll('tbody tr').length == 1) {
                        this.element.querySelector('.btn-delete-table-line').remove()
                    }
                } else {
                    this.notifier.error(response.message);
                }
            }).catch(error => {
                console.error(error);
            })
        }
    }

    isLive() {
        return this.dataTable
    }

    isTable() {
        return this.element.nodeName === 'TABLE'
    }

    isPreview() {
        document.documentElement.hasAttribute('data-turbolinks-preview')
    }

    isBooting() {
        return this.isTable() && !this.isPreview() && !this.isLive()
    }

    _teardown = () => this.teardown()

    teardown(event) {
        if (!this.isLive()) return false

        document.removeEventListener('turbolinks:before-render', this._teardown)
        this.dataTable.destroy()
        this.dataTable = undefined
    }
}