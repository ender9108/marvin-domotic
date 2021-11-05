export default {
    position: {
        x: 'right',
        y: 'top',
    },
    dismissible: true,
    types: [
        {
            type: 'info',
            background: '#3c77af',
            icon: {
                className: 'fas fa-exclamation-circle',
                tagName: 'i',
                text: 'info'
            },
            dismissible: true
        },
        {
            type: 'warning',
            background: '#f69c7b',
            icon: {
                className: 'fas fa-exclamation-triangle',
                tagName: 'i',
                text: 'warning'
            },
            dismissible: true
        }
    ]
}