// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

// Turbo
import './turbo/turbo-helper';
import './turbo/prefetch';

// Init adminlte theme
import "./modules/bootstrap";
import "./modules/theme";
import "./modules/chartjs";
import "./modules/flatpickr";
import "./modules/vector-maps";
import "./modules/notyf";

String.prototype.noAccent = function(){
    const accent = [
        /[\300-\306]/g, /[\340-\346]/g, // A, a
        /[\310-\313]/g, /[\350-\353]/g, // E, e
        /[\314-\317]/g, /[\354-\357]/g, // I, i
        /[\322-\330]/g, /[\362-\370]/g, // O, o
        /[\331-\334]/g, /[\371-\374]/g, // U, u
        /[\321]/g, /[\361]/g, // N, n
        /[\307]/g, /[\347]/g, // C, c
    ]
    const noaccent = ['A','a','E','e','I','i','O','o','U','u','N','n','C','c']

    let str = this

    for (let i = 0; i < accent.length; i++){
        str = str.replace(accent[i], noaccent[i])
    }

    return str
}

String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1)
}

String.prototype.toHtml = function(selector) {
    const parser = new DOMParser()
    const doc = parser.parseFromString(this, 'text/html')

    if (selector !== undefined) {
        return doc.body.querySelector(selector)
    } else {
        return doc.body
    }
}