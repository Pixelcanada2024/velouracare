import Button from "@/components/ui/Button"
import { useCart } from "@/contexts/CartItemsContext";
import { useTranslation } from "@/contexts/TranslationContext";
import { Link, router, usePage } from "@inertiajs/react";
import React from "react"

export default function OrderSummary({ orderStatusState, nextBtnIsDisabled, selectedCard, isLoadingState, productItemsState, selectedCartItems, allCartItems }) {
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();
  const [isLoading, setIsLoading] = isLoadingState;
  const [productItems, setProductItems] = productItemsState;

  const { cartData, updateCart } = useCart();

  const [orderStatus, setOrderStatus] = orderStatusState;
  const userId = usePage().props?.auth?.user?.id ?? null;
  // get selected product ids from cart page 
  const selectedItems = JSON.parse(localStorage.getItem("selectedItems_" + userId));
  // get selected address from shipping options page 
  const selectedAddress = JSON.parse(localStorage.getItem("selectedAddress"));


  // prepare request data
  const data = {
    "cart_details": {
      "product_ids": Object.keys(selectedCartItems),
      "quantities": selectedCartItems,
    },
    "shipping_type": selectedCard === 0 ? "delivery" : "pickup",
    "additional_notes": selectedAddress?.additionalNotes,
    ...selectedAddress
  }

  const placeOrder = () => {
    const successMsg = document.getElementById("layout-success-msg");
    successMsg.innerHTML = tr["processing"] + "...";
    successMsg.style.backgroundColor = "yellow";
    successMsg.style.display = "block";
    axios.post(route("react.place-order"), data)
      .then(response => {
        // console.log(response.data);
        if (response.data.success) {
          localStorage.setItem("order_success", true);
          localStorage.setItem("order_code", response.data.order_code);
          const newCartItems = Object.fromEntries(Object.entries(allCartItems).filter(([id, _qty]) => !selectedItems.includes(+id)));
          updateCart({ user_id: userId, cartItems: newCartItems, showAddedSuccessMsgStatus: false });
          setTimeout(() => {
            router.visit(route("react.dashboard.orders"), { preserveState: true, preserveScroll: true, replace: true });
          }, 500);
        } else if (!response.data.success) {
          successMsg.innerHTML = "";
          successMsg.style.display = "none";
          console.log(response);
        }
      }).catch(error => {
        successMsg.innerHTML = "";
        successMsg.style.display = "none";
        console.log(error);
      })
  }

  return (
    <div className="w-full xl:w-[calc(35%-20px)] shadow-[0px_0px_4px_2px_rgba(0,0,0,0.1)] max-h-fit rounded-lg space-y-5 p-4">
      { orderStatus == 0 &&
        <>
          <h3 className='text-xl font-bold'>{tr["order_summary"]}</h3>
          <hr className='border-gray-200' />
          {isLoading
            ?
            <div className="mb-5 bg-gray-50 flex  items-center justify-center rounded min-h-10 p-2">
              <span className="animate-pulse ">
                {tr["loading"]} ...
              </span>
            </div>
            :
            <div className="mb-5 bg-gray-50 flex  flex-col  items-start justify-center rounded min-h-10 p-2">
              <h3 className='text-base sm:text-lg font-semibold'>
                {tr["the_products"] + " [" + productItems.length + "]"}
              </h3>
              <div className="p-2 divide-y divide-gray-200 w-full text-sm sm:text-base">
                {productItems.map((item, index) => <p className="py-2" key={index}> 
                  <Link href={route("react.product", { product: item.id })} className="font-medium hover:underline">
                    {item.name} 
                  </Link>
                  <br />
                 {item.box_qty * selectedCartItems[item.id]} {tr["pcs"]} ({selectedCartItems[item.id]} {tr["boxes"]})
                </p>)}
              </div>
            </div>
          }
          <hr className='border-gray-200' />
        </>
      }

      { orderStatus == 0 &&
        <>
          <Button
            fullWidth className={" !rounded-lg "}
            onClick={() => setOrderStatus(1)}
            disabled={nextBtnIsDisabled || Object.keys(selectedCartItems).length == 0} >
            {tr["next"]}
          </Button>
          <hr className='border-gray-200' />
        </>
      }

      {orderStatus == 1 &&
        <>
          <p className='font-semibold'>{tr["what_s_next"]}</p>
          <p className='text-[#818181]'>{tr["our_team_will_review_your_order_and_contact_you_via_email_shortly"]}</p>

          <Button
            fullWidth
            className={" !rounded-lg cursor-pointer "}
            onClick={() => placeOrder()}
            disabled={nextBtnIsDisabled || Object.keys(selectedCartItems).length == 0}
          >
            {tr["apply_quotation_request"]}
          </Button>
          <hr className='border-gray-200' />
        </>
      }
      {/* Help Link */}
      <div className="text-center">
        <p className=" text-gray-500 mb-2">
          {tr["need_help_before_you_order"]}
        </p>
        <Button
          target="_blank"
          rel="noopener noreferrer"
          data-inertia="false"
          variant="outline"
          href={route("react.contact-us")}
          fullWidth
          className="!rounded-lg !border-[#E5E7EB] flex items-center gap-2 cursor-pointer"
        >
          <span>{tr["contact_us"]}</span>
          <svg
            width="17"
            height="16"
            viewBox="0 0 17 16"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              d="M8.49956 14.2223C9.7302 14.2223 10.9332 13.8573 11.9564 13.1736C12.9797 12.4899 13.7772 11.5182 14.2481 10.3812C14.7191 9.24423 14.8423 7.99315 14.6022 6.78616C14.3621 5.57917 13.7695 4.47047 12.8993 3.60028C12.0291 2.73009 10.9205 2.13748 9.71346 1.89739C8.50647 1.65731 7.25539 1.78053 6.11842 2.25147C4.98146 2.72242 4.00968 3.51994 3.32598 4.54317C2.64227 5.56641 2.27734 6.76942 2.27734 8.00006C2.27734 9.0288 2.52623 9.99877 2.9687 10.8533L2.27734 14.2223L5.64633 13.5309C6.50085 13.9734 7.47152 14.2223 8.49956 14.2223Z"
              stroke="#222222"
              strokeWidth="1.5"
              strokeLinecap="round"
              strokeLinejoin="round"
            />
          </svg>
        </Button>
      </div>
    </div>
  )
}

