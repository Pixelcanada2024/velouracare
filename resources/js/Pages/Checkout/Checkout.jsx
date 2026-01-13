import Layout from "@/components/layout/Layout"
import React, { useEffect, useState } from "react"
import ShippingOptions from "./Partials/ShippingOptions"
import OrderSummary from "./Partials/OrderSummary"
import { Link, router, usePage } from "@inertiajs/react"
import OrderReview from "./Partials/OrderReview"
import { useTranslation } from "@/contexts/TranslationContext"
import { useCart } from "@/contexts/CartItemsContext"

export default function Checkout({ BillingSavedAddress, ShippingSavedAddress, countries }) {

  const [{ lang, currency, tr }, _setTranslation] = useTranslation();

  const [orderStatus, setOrderStatus] = useState(0); // 0 -> Entering Info , 1 -> Review
  const orderStatusState = [orderStatus, setOrderStatus];
  const [selectedCard, setSelectedCard] = useState(0)  // 0 -> Deliver , 1 -> Pickup
  const selectedCardState = [selectedCard, setSelectedCard];
  const [nextBtnIsDisabled, setNextBtnIsDisabled] = useState(true);

  // get selected product ids from cart page
  const userId = usePage().props?.auth?.user?.id ?? null;

  const [isLoading, setIsLoading] = useState(true);
  const isLoadingState = [isLoading, setIsLoading];

  const selectedItems = JSON.parse(localStorage.getItem("selectedItems_" + userId)) ?? [];

  //  get user cart items
  const { cartData, _ } = useCart();
  const allCartItems = cartData?.find(item => item.user_id === userId)?.cart_items ?? {};
  const selectedCartItems = Object.fromEntries(Object.entries(allCartItems).filter(([id, _qty]) => selectedItems.includes(+id)));

  const [productItems, setProductItems] = useState([]);
  const productItemsState = [productItems, setProductItems];

  useEffect(() => {
    let timeoutId;

    // if (!Object.keys(selectedCartItems)?.length) {
    //   return;
    //   // timeoutId = setTimeout(() => router.get(route("react.products")), 10000);
    //   // return () => clearTimeout(timeoutId);
    // }

    const fetchCartData = async () => {
      if (!Object.keys(selectedCartItems)?.length) {
        setIsLoading(false);
        return;
      }
      try {
        setIsLoading(true);
        const productIds = Object.keys(selectedCartItems);
        const response = await axios.post(route("react.cart-data", { productIds }));
        if (response.data.success) {
          setProductItems(response.data.productItems);
          timeoutId = setTimeout(() => setIsLoading(false), 500);
        } else {
          console.error(response);
        }
      } catch (error) {
        console.error(error);
      }
    };

    fetchCartData();

    return () => clearTimeout(timeoutId);
  }, [Object.keys(selectedCartItems)?.length]);

  return (
    <Layout
      pageTitle={tr["order_request"]}
      breadcrumbs={[
        { label: tr["home"], url: route("react.home") },
        { label: tr["cart"], url: route("react.cart") },
        { label: tr["order_request"], url: "#" },
      ]}
    >
      <div className="container py-8 mx-auto">
        <h2 className='text-[28px] sm:text-[40px] xl:text-[52px] font-bold mb-10'> {tr["order_request"]} </h2>
        <div className="flex flex-wrap gap-10 ">

          <div className='w-full'>
            <Link href={route("react.cart")} className='flex items-center gap-2 font-bold'>
              <svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg" className="rtl:rotate-180">
                <path d="M15.75 9.49025L2.40825 9.5M7.491 4.25L2.25 9.5L7.491 14.75" stroke="black" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
              </svg>
              <span className="text-sm sm:text-base">{tr["back_to_cart"]}</span>
            </Link>
          </div>

          <div className="w-full xl:w-[calc(65%-20px)] flex flex-col gap-6">
            <div className="border border-gray-200 rounded-xl py-6 xl:py-10">
              <div className={"relative mx-auto w-4/5 h-2" + (orderStatus == 0 ? "  bg-gray-200 " : " bg-black ")}>
                <div className={"absolute top-1/2 -translate-y-1/2 ltr:left-0 ltr:-translate-x-1/2 rtl:translate-x-1/2 rtl:right-0  w-5 h-5 rounded-full bg-white border-2 border-black z-40 cursor-pointer hover:scale-125"} onClick={() => setOrderStatus(0)}></div>
                <div className={"absolute top-1/2 -translate-y-1/2 ltr:right-0 ltr:translate-x-1/2 rtl:-translate-x-1/2 rtl:left-0 w-5 h-5 rounded-full bg-white border-2 z-40" + (orderStatus == 0 ? "  border-gray-200 " : " border-black ")}></div>
              </div>
              <div className="flex items-center justify-between px-5 sm:px-10 mt-5 gap-5">
                <p className={"font-semibold text-black max-sm:text-xs max-sm:text-start cursor-pointer"} onClick={() => setOrderStatus(0)}>{tr["shipping_information"]}</p>
                <p className={"font-semibold max-sm:text-xs max-sm:text-end" + (orderStatus == 0 ? "  text-gray-200 " : " text-black ")}>{tr["review_request"]}</p>
              </div>
            </div>

            {/* Shipping Options */}
            {orderStatus == 0 && (
              <ShippingOptions
                BillingSavedAddress={BillingSavedAddress}
                ShippingSavedAddress={ShippingSavedAddress}
                countries={countries}
                setNextBtnIsDisabled={setNextBtnIsDisabled}
                setSelectedCardStatus={[selectedCard, setSelectedCard]}
              />
            )}

            {/* Order Review */}
            {orderStatus == 1 && (
              <OrderReview {...{ isLoadingState, productItemsState, selectedCartItems, selectedCardState , orderStatusState }} />
            )}
          </div>

          <OrderSummary {...{ orderStatusState, nextBtnIsDisabled , allCartItems , selectedCard, isLoadingState, productItemsState, selectedCartItems  }} />

        </div>
      </div>

    </Layout>
  )
}
