import { Link, usePage } from "@inertiajs/react";
// start lang
import ar from '@/translation/ar'
import en from '@/translation/en'
import Button from "../ui/Button";
import { useCart } from "@/contexts/CartItemsContext";
import React, { useEffect } from "react";
import toggleNotifyMeWhenStockAvailable from "@/utils/toggleNotifyMeWhenStockAvailable";
import { increaseQuantityFunction } from "@/utils/cartSharedFunctions";
import { GrayBellOffIcon, WhiteBellIcon } from "./Icons";
// end lang
export default function ProductCard({
  product
}) {
  const notifyMeProducts = usePage().props?.notifyMeProducts ?? [];

  const isAuthenticated = usePage().props?.auth?.user != undefined;

  // Destructure product properties with defaults for safety
  const {
    name = '',
    brand = '',
    image = '',
    price = 0,
  } = product || {};


  const lang = usePage().props.locale;
  const tr = lang === 'ar' ? ar : en;

  //  get user cart items
  const { cartData, updateCart } = useCart();

  const userId = usePage().props?.auth?.user?.id ?? null;
  const cartItems = cartData?.find(item => item.user_id === userId)?.cart_items ?? {};

  // add cart items into state for easily dynamic update
  const [cartQuantities, setCartQuantities] = React.useState({});

  useEffect(() => {
    if (Object.keys(cartQuantities)?.length > 0) return;
    setCartQuantities(cartItems);
  }, [Object.keys(cartItems)?.length]);

  const increaseQuantity = (id, qty, box_qty) => {
    increaseQuantityFunction(id, qty, box_qty, cartQuantities, setCartQuantities);
  }

  const decreaseQuantity = (id) => {
    const newCartQuantities = { ...cartQuantities };
    if (!newCartQuantities?.[id] || newCartQuantities?.[id] < 1) return;
    newCartQuantities[id] = (newCartQuantities?.[id] ?? 0) - 1;
    setCartQuantities(newCartQuantities);
    // updateCart({ user_id: userId, cartItems: newCartQuantities });
  }

  const addSelectedItemsToCart = (ids = []) => {
    const filteredEntries = Object.entries(cartQuantities).filter(([id, qty]) => {
      return ids.includes(+id) && qty > 0
    });
    if (!filteredEntries.length) return;
    const selectedCartItems = Object.fromEntries(filteredEntries);
    const newCartQuantities = { ...cartItems, ...selectedCartItems };
    updateCart({ user_id: userId, cartItems: newCartQuantities });
  }

  return (
    <div className="relative flex flex-col overflow-hidden bg-white border border-gray-200 rounded-lg [&_*]:max-sm:text-xs">

      {/* Product Image */}
      <Link className="block relative overflow-hidden pt-[100%] border-b border-[#ECECEC]"
        href={route('react.product', { product: product.product_id })}
      >
        <img
          src={image}
          alt={name}
          className="absolute top-0 left-0 object-contain object-center w-full h-full"
          loading="lazy"
        />
      </Link>

      {/* Product Info */}
      <div className="flex flex-col flex-grow p-2">
        {/* Product name */}
        <Link
          href={route("react.product", {
            product: product.product_id,
          })}
          title={product.name}
          className="text-center block"
        >
          <div className="  mb-1 uppercase">
            {product.brand}
          </div>
          <div className=" !text-sm xl:!text-base text-[#222222] mb-1 overflow-hidden line-clamp-2 leading-[1.5rem] min-h-[3rem]">
            {product.name}
          </div>
          {isAuthenticated && <div className="my-2">
            <div className=" text-[#9CA3AF]" dir={lang === 'ar' ? 'rtl' : 'ltr'}>
              {tr['SKU']}: {product.sku}
            </div>
            <div className=" text-[#9CA3AF]">
              {tr['barcode']}: {product.barcode}
            </div>
          </div>}
        </Link>

        {isAuthenticated && (
          <div className="text-center text-sm">
            <p className=" "><span className="text-[#565656]">{tr['MOQ']}</span> 1 {tr['box']} ({product.box_qty}{tr['pcs']})</p>
            <div className=" ">
              {product?.qty >= ((cartQuantities?.[product.id] ?? 0) * product?.box_qty) && product?.qty != 0 ? <>{tr['available_now']} <span className='text-[#009B22]'>({tr['stock'] + " : " + product?.qty + " " + tr['pcs']})</span></> : <span className='text-[#B00E0E]'>{tr['out_of_stock']}</span>} </div>
          </div>
        )}

        {/* Price section */}
        <div className="text-center mt-auto ">
          {isAuthenticated ? (
            <>
              {/*<ProductPrice product={product} /> */}
              {/* Box Quantity */}
              <div className="mt-4">
                <div className="flex flex-col ">
                  <div className="flex items-center flex-1 gap-5">
                    <button
                      onClick={() => decreaseQuantity(product.id)}
                      className="w-7 h-7 rounded-full border border-[#E1E4EA] flex items-center justify-center hover:bg-gray-100">
                      <svg
                        width="12"
                        height="12"
                        viewBox="0 0 15 15"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          d="M3 8H10.5"
                          stroke="black"
                          strokeLinecap="round"
                          strokeLinejoin="round"
                        />
                      </svg>
                    </button>

                    <div className=" font-medium rounded px-9 py-1 border border-[#E1E4EA] min-w-[40px] text-center flex-1">
                      {cartQuantities?.[product.id] || 0}
                    </div>

                    <button
                      onClick={() => increaseQuantity(product.id, product.qty, product.box_qty)}
                      className="w-7 h-7 rounded-full border border-[#E1E4EA] flex items-center justify-center hover:bg-gray-100 ">
                      <svg
                        width="12"
                        height="12"
                        viewBox="0 0 15 15"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          d="M11.25 7.5H7.5M7.5 7.5H3.75M7.5 7.5V3.75M7.5 7.5V11.25"
                          stroke="black"
                          strokeWidth="0.625"
                          strokeLinecap="round"
                          strokeLinejoin="round"
                        />
                      </svg>
                    </button>
                  </div>
                  <div className="text-sm text-center  mt-1">
                    ({tr['total']} {+(cartQuantities?.[product.id] || 0) * product.box_qty} {tr['pcs']})
                  </div>
                </div>
                <div className="p-2 pt-4">
                  {product?.qty >= (+(cartQuantities?.[product.id] || 0) * product?.box_qty) && product?.qty != 0 ? (
                    <Button
                      type="submit"
                      variant="primary"
                      fullWidth
                      className="cursor-pointer rounded-sm"
                      onClick={() => addSelectedItemsToCart([product.id])}
                    >
                      <div className="flex items-center gap-2 ">
                        <span className="!text-xs sm:!text-sm">{tr['add_to_cart']}</span>
                        <svg
                          width="18"
                          height="18"
                          viewBox="0 0 18 18"
                          fill="none"
                          xmlns="http://www.w3.org/2000/svg"
                        >
                          <g clipPath="url(#clip0_579_10468)">
                            <path
                              d="M6 16.5C6.41421 16.5 6.75 16.1642 6.75 15.75C6.75 15.3358 6.41421 15 6 15C5.58579 15 5.25 15.3358 5.25 15.75C5.25 16.1642 5.58579 16.5 6 16.5Z"
                              stroke="white"
                              strokeWidth="1.5"
                              strokeLinecap="round"
                              strokeLinejoin="round"
                            />
                            <path
                              d="M14.25 16.5C14.6642 16.5 15 16.1642 15 15.75C15 15.3358 14.6642 15 14.25 15C13.8358 15 13.5 15.3358 13.5 15.75C13.5 16.1642 13.8358 16.5 14.25 16.5Z"
                              stroke="white"
                              strokeWidth="1.5"
                              strokeLinecap="round"
                              strokeLinejoin="round"
                            />
                            <path
                              d="M1.53906 1.5376H3.03906L5.03406 10.8526C5.10725 11.1937 5.29707 11.4987 5.57085 11.715C5.84463 11.9313 6.18524 12.0454 6.53406 12.0376H13.8691C14.2104 12.037 14.5414 11.9201 14.8073 11.706C15.0732 11.4919 15.2582 11.1935 15.3316 10.8601L16.5691 5.2876H3.84156"
                              stroke="white"
                              strokeWidth="1.5"
                              strokeLinecap="round"
                              strokeLinejoin="round"
                            />
                          </g>
                          <defs>
                            <clipPath id="clip0_579_10468">
                              <rect
                                width="18"
                                height="18"
                                fill="white"
                              />
                            </clipPath>
                          </defs>
                        </svg>
                      </div>
                    </Button>
                  ) : (
                    (() => {
                      const isSubscribed = notifyMeProducts.includes(product.id);
                      return (
                        <Button
                          onClick={(e) =>
                            toggleNotifyMeWhenStockAvailable(product.id)
                          }
                          fullWidth
                          variant={isSubscribed ? 'out_of_stock_unsubscribe' : 'out_of_stock'}
                          className='flex items-center gap-2 rounded-sm py-[8px] sm:py-[10px] xl:py-[11px]'
                        >
                          <span className='!text-xs sm:!text-sm'>
                            {isSubscribed
                              ? tr['unsubscribe']
                              : tr['notify_me_when_available']}
                          </span>
                          <span>
                            {isSubscribed ? (
                              <GrayBellOffIcon />
                            ) : (
                              <WhiteBellIcon />
                            )}
                          </span>
                        </Button>
                      );
                    })()
                  )}


                </div>
              </div>
            </>
          ) : (
            <div className="mb-3 mt-5">
              <Link href={route('react.login')} className="text-sm text-black underline underline-offset-4">{tr['login_for_more_details']}</Link>
            </div>
          )}

        </div>
      </div>

    </div>
  );
}
