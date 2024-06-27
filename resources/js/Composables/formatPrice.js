export function useFormatPrice(price) {
    price = Math.ceil(parseFloat(price));

    const priceToString = price.toString();
    const formattedPrice = priceToString.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

    return 'RM' + formattedPrice;
}