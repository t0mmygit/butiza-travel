export function useFormatText(text, underscore = false) {
    if (typeof text != 'string') return;

    var splits = text;

    if (underscore) {
        splits = text.split('_');
    } else {
        splits = text.split(' ');
    }

    return splits.map(part => part.charAt(0).toUpperCase() + part.slice(1)).join(' ')
}