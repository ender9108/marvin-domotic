import * as Turbo from '@hotwired/turbo';
let lastTouchTimestamp
let delayOnHover = 65
let mouseoverTimer
const pendingPrefetches = new Set()

const eventListenersOptions = {
    capture: true,
    passive: true,
}

class Snapshot extends Turbo.navigator.view.snapshot.constructor {
}

document.addEventListener('touchstart', touchstartListener, eventListenersOptions)
document.addEventListener('mouseover', mouseoverListener, eventListenersOptions)

function touchstartListener(event) {
    if (window.disablePrefetch) {
        return
    }

    /* Chrome on Android calls mouseover before touchcancel so `lastTouchTimestamp`
     * must be assigned on touchstart to be measured on mouseover. */
    lastTouchTimestamp = performance.now()
    const linkElement = event.target.closest('a')

    if (!isPreloadable(linkElement)) {
        return
    }

    preload(linkElement)
}

function mouseoverListener(event) {
    if (window.disablePrefetch) {
        return
    }

    if (performance.now() - lastTouchTimestamp < 1111) {
        return
    }

    const linkElement = event.target.closest('a')

    if (!isPreloadable(linkElement)) {
        return
    }

    const url = linkElement.getAttribute("href")
    const loc = new URL(url, location.protocol + "//" + location.host).toString()
    const absoluteUrl = loc.toString()

    if (pendingPrefetches.has(absoluteUrl)) {
        return
    }

    pendingPrefetches.add(absoluteUrl)
    linkElement.addEventListener('mouseout', mouseoutListener, {passive: true})

    mouseoverTimer = setTimeout(() => {
        preload(linkElement)
        mouseoverTimer = undefined
    }, delayOnHover)
}

function mouseoutListener(event) {
    if (event.relatedTarget && event.target.closest('a') == event.relatedTarget.closest('a')) {
        return
    }

    if (mouseoverTimer) {
        clearTimeout(mouseoverTimer)
        mouseoverTimer = undefined
    }
}

function isPreloadable(linkElement) {
    if (!linkElement || !linkElement.getAttribute("href") || linkElement.dataset.turbo == "false" || linkElement.dataset.prefetch == "false") {
        return
    }

    if (linkElement.origin != location.origin) {
        return
    }

    if (!['http:', 'https:'].includes(linkElement.protocol)) {
        return
    }

    if (linkElement.protocol == 'http:' && location.protocol == 'https:') {
        return
    }

    if (linkElement.search && !('prefetch' in linkElement.dataset)) {
        return
    }

    if (linkElement.hash && linkElement.pathname + linkElement.search == location.pathname + location.search) {
        return
    }

    return true
}

function fetchPage(url, success) {
    const xhr = new XMLHttpRequest()
    xhr.open('GET', url)
    xhr.setRequestHeader('VND.PREFETCH', 'true')
    xhr.setRequestHeader('Accept', 'text/html')
    xhr.onreadystatechange = () => {
        if (xhr.readyState !== XMLHttpRequest.DONE) return
        if (xhr.status !== 200) return
        success(xhr.responseText)
    }
    xhr.send()
}

function preload(link) {
    const url = link.getAttribute("href")
    const loc = new URL(url, location.protocol + "//" + location.host)
    const absoluteUrl = loc.toString()

    if (link.dataset.prefetchWithLink == "true") {
        const prefetcher = document.createElement('link')
        prefetcher.rel = 'prefetch'
        prefetcher.href = url
        document.head.appendChild(prefetcher)
        pendingPrefetches.delete(absoluteUrl)
    } else if (!Turbo.navigator.view.snapshotCache.has(loc)) {
        fetchPage(url, responseText => {
            const snapshot = Snapshot.fromHTMLString(responseText)
            Turbo.navigator.view.snapshotCache.put(loc, snapshot)
            pendingPrefetches.delete(absoluteUrl)
        })
    }
}