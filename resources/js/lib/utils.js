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
