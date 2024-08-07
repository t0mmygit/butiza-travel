import { isRef, unref } from 'vue';

export function useFormatPrice(price,  fixed = 2,ceil = true, prefix = '') {
    if (isRef(price)) {
        price = unref(price);
    }

    if (ceil) {
        price = Math.ceil(parseFloat(price));
    }

    const priceToString = price.toFixed(fixed);
    const formattedPrice = priceToString.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

    if (prefix !== '') return prefix + 'RM' + formattedPrice;

    return 'RM' + formattedPrice;
}