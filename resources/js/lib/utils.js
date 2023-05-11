export function formatBytes (bytes) {
    const formatter = Intl.NumberFormat("en", {
        notation: 'compact',
        style: 'unit',
        unit: 'byte',
        unitDisplay: 'narrow',
    });

    return formatter.format(bytes)
}

export function capitalizeFirstLetter (str) {
    return `${str.charAt(0).toUpperCase()}${str.slice(1)}`
}

export function getNodeIdFromUrl () {
    const urlParams = new URLSearchParams(window.location.search)

    return urlParams.get('path')
}

export function updateUrlPath (nodeId) {
    const url = new URL(window.location)

    if (nodeId) {
        url.searchParams.set('path', nodeId)
    } else {
        url.searchParams.delete('path')
    }

    window.history.pushState({}, '', url.href)
}
