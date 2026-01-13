export function increaseQuantityFunction(id,qty, box_qty, cartQuantities, setCartQuantities) {
    const newCartQuantities = { ...cartQuantities };
    const itemCartQty = +(newCartQuantities?.[id] ?? 0);
    if ( (+itemCartQty + 1 ) * +box_qty > +qty  ) return;
    newCartQuantities[id] = +itemCartQty + 1;
    setCartQuantities(newCartQuantities);
} 