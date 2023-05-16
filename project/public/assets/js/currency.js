function IDRCurrency(value){
    var formatted_price = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
    var price_without_decimal = formatted_price.slice(0, -3);
    return price_without_decimal;
}