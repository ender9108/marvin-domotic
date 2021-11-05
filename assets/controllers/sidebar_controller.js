import { Controller } from "stimulus"
import SimpleBar from "simplebar";

export default class extends Controller {
    static targets = []
    static values = {}

    connect() {
        console.log('Controller SidebarController');
    }

    initialize() {
        this.initializeSimplebar();
        this.initializeSidebarCollapse();
    }

    initializeSimplebar() {
        const simplebarElement = document.getElementsByClassName("js-simplebar")[0];

        if(simplebarElement){
            const simplebarInstance = new SimpleBar(document.getElementsByClassName("js-simplebar")[0]);

            /* Recalculate simplebar on sidebar dropdown toggle */
            const sidebarDropdowns = document.querySelectorAll(".js-sidebar [data-bs-parent]");

            sidebarDropdowns.forEach(link => {
                link.addEventListener("shown.bs.collapse", () => {
                    simplebarInstance.recalculate();
                });
                link.addEventListener("hidden.bs.collapse", () => {
                    simplebarInstance.recalculate();
                });
            });
        }
    }

    initializeSidebarCollapse() {
        const sidebarElement = document.getElementsByClassName("js-sidebar")[0];
        const sidebarToggleElement = document.getElementsByClassName("js-sidebar-toggle")[0];

        if(sidebarElement && sidebarToggleElement) {
            sidebarToggleElement.addEventListener("click", () => {
                sidebarElement.classList.toggle("collapsed");

                sidebarElement.addEventListener("transitionend", () => {
                    window.dispatchEvent(new Event("resize"));
                });
            });
        }
    }
}