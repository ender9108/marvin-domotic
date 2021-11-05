import { Modal } from 'bootstrap';

const TurboHelper = class {
    constructor() {
        document.addEventListener('turbo:before-cache', () => {
            this.closeModal();
        });

        document.addEventListener('turbo:visit', () => {
            // fade out the old body
            document.body.classList.add('turbo-loading');
        });

        document.addEventListener('turbo:before-render', (event) => {
            // when we are *about* to render, start us faded out
            event.detail.newBody.classList.add('turbo-loading');
        });

        document.addEventListener('turbo:render', () => {
            // after rendering, we first allow the turbo-loading class to set the low opacity
            // THEN, one frame later, we remove the turbo-loading class, which allows the fade in
            requestAnimationFrame(() => {
                document.body.classList.remove('turbo-loading');
            });
        });

        /*document.addEventListener('turbo:before-visit', () => {
            document.body.classList.add('turbo-fade-out')
        })

        document.addEventListener('turbo:render', () => {
            document.body.classList.remove('turbo-fade-out')
            document.body.classList.add('turbo-fade-in')
        });*/

        this.initializeTransitions();
    }

    closeModal() {
        if (document.body.classList.contains('modal-open')) {
            const modalEl = document.querySelector('.modal');
            const modal = Modal.getInstance(modalEl);
            modalEl.classList.remove('fade');
            modal._backdrop._config.isAnimated = false;
            modal.hide();
            modal.dispose();
        }
    }

    isPreviewRendered() {
        return document.documentElement.hasAttribute('data-turbo-preview');
    }

    initializeTransitions() {
        document.addEventListener('turbo:visit', () => {
            // fade out the old body
            document.body.classList.add('turbo-loading');
        });
        document.addEventListener('turbo:before-render', (event) => {
            if (this.isPreviewRendered()) {
                // this is a preview that has been instantly swapped
                // remove .turbo-loading so the preview starts fully opaque
                event.detail.newBody.classList.remove('turbo-loading');
                // start fading out 1 frame later after opacity starts full
                requestAnimationFrame(() => {
                    document.body.classList.add('turbo-loading');
                });
            } else {
                const isRestoration = event.detail.newBody.classList.contains('turbo-loading');
                if (isRestoration) {
                    // this is a restoration (back button). Remove the class
                    // so it simply starts with full opacity
                    event.detail.newBody.classList.remove('turbo-loading');
                    return;
                }
                // when we are *about* to render a fresh page
                // we should already be faded out, so start us faded out
                event.detail.newBody.classList.add('turbo-loading');
            }
        });
        document.addEventListener('turbo:render', () => {
            if (!this.isPreviewRendered()) {
                // if this is a preview, then we do nothing: stay faded out
                // after rendering the REAL page, we first allow the .turbo-loading to
                // instantly start the page at lower opacity. THEN remove the class
                // one frame later, which allows the fade in
                requestAnimationFrame(() => {
                    document.body.classList.remove('turbo-loading');
                });
            }
        });
    }
}

export default new TurboHelper();