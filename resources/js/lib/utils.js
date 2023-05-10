export function formatBytes(bytes) {
    const byteValueNumberFormatter = Intl.NumberFormat("en", {
        notation: "compact",
        style: "unit",
        unit: "byte",
        unitDisplay: "narrow",
    });

    return byteValueNumberFormatter.format(bytes)
}

export function capitalizeFirstLetter (str) {
    return `${str.charAt(0).toUpperCase()}${str.slice(1)}`
}
