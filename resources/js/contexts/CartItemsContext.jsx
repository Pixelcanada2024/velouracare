import React, { createContext, useContext, useEffect, useState } from "react";

const CartItemsContext = createContext();

export const useCart = () => useContext(CartItemsContext);

const STORAGE_KEY = "cartItems";

export const CartItemsProvider = ({ children }) => {
  const [cartData, setCartData] = useState([]);
  const showAddedSuccessMsgState = React.useState(false);
  const [showAddedSuccessMsg, setShowAddedSuccessMsg] = showAddedSuccessMsgState;

  useEffect(() => {
    const stored = localStorage.getItem(STORAGE_KEY);
    if (stored) {
      setCartData(JSON.parse(stored));
    }
  }, []);

  useEffect(() => {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(cartData));
  }, [cartData]);

  const updateCart = ({ user_id, cartItems = {} , showAddedSuccessMsgStatus = true }) => {
    setCartData(prev => {
      const updated = [...prev];
      const userIndex = updated.findIndex(u => u.user_id === user_id);
      const newCartItems = Object.fromEntries(
        Object.entries(cartItems).filter(([_id, qty]) => qty > 0)
      );
      const totalQty = Object.entries(newCartItems).reduce((sum, [_id, qty]) => sum + Number(qty), 0);

      if (userIndex !== -1) {

        if (Object.keys(newCartItems).length > 0) {
          updated[userIndex] = {
            user_id,
            cart_items: newCartItems,
            total_qty: totalQty,
          };
        } else {
          updated.splice(userIndex, 1);
        }

      } else if (Object.keys(newCartItems).length > 0) {
        // New user with items
        updated.push({
          user_id,
          cart_items: newCartItems,
          total_qty: totalQty,
        });
      }

      return updated;
    });

    if( showAddedSuccessMsgStatus ) {
      setShowAddedSuccessMsg(prev => true);
      setTimeout(() => {
        setShowAddedSuccessMsg(prev => false);
      }, 1500);
    }
  };

  return (
    <CartItemsContext.Provider value={{ cartData, updateCart , showAddedSuccessMsgState }}>
      {children}
    </CartItemsContext.Provider>
  );
};
