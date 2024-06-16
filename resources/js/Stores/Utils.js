export function unmaskMoneyToNumeric(amount) {
    
    return amount ? amount.replace(/[^0-9.-]+/g, '') : 0;
};