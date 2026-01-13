import { router, usePage } from "@inertiajs/react"
import React, { useEffect, useState } from "react"
import OrderProductsTable from "./OrderReviewPartials/OrderProductsTable"
import OrderProductsCards from "./OrderReviewPartials/OrderProductsCards"
import { useCart } from "@/contexts/CartItemsContext";
import { useTranslation } from "@/contexts/TranslationContext";
import axios from "axios";
import Button from "@/components/ui/Button";



export default function OrderReview({ isLoadingState, productItemsState, selectedCartItems, selectedCardState, orderStatusState }) {
  const [orderStatus, setOrderStatus] = orderStatusState;
  const [selectedCard] = selectedCardState;
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();
  const [isLoading, setIsLoading] = isLoadingState;
  const [productItems, setProductItems] = productItemsState;
  const [selectedAddress, _setSelectedAddress] = useState(JSON.parse(localStorage.getItem("selectedAddress")))

  const EditBtn =
    <Button variant='outline' size="sm" onClick={() => setOrderStatus(0)}>
      <div className='flex items-center gap-2'>
        <span className="max-sm:hidden">{tr["edit_address"]}</span>
        <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M13.332 10.5001L6.66536 17.1667H3.33203V13.8334L9.9987 7.16675L12.3895 4.77592L12.3904 4.77508C12.7195 4.44592 12.8845 4.28092 13.0745 4.21925C13.2419 4.16487 13.4222 4.16487 13.5895 4.21925C13.7795 4.28092 13.9437 4.44592 14.2729 4.77425L15.7229 6.22425C16.0529 6.55425 16.2179 6.71925 16.2795 6.90925C16.3339 7.07661 16.3339 7.25689 16.2795 7.42425C16.2179 7.61425 16.0529 7.77925 15.7229 8.10925L13.332 10.5009L9.9987 7.16758" stroke="black" strokeWidth="1.25" strokeLinecap="round" strokeLinejoin="round" />
        </svg>
      </div>
    </Button>

  return (
    <>
      <h3 className='text-2xl font-semibold mb-2'>{tr["review_order_details"]}</h3>
      {/* End Header */}
      {/* price msg */}
      <div className="bg-gray-500 text-white py-2 px-4 mb-2 rounded-md">
        <p className="text-sm lg:text-base">{tr["checkout_review_price_header"]}</p>
      </div>

      {isLoading
        ?
        <div className="mb-10 bg-gray-100 min-h-64 flex flex-1 items-center justify-center">
          <span className="animate-pulse ">
            {tr["loading"]} ...
          </span>
        </div>
        : <>
          <OrderProductsTable {...{ selectedCartItems, productItems }} />
          <OrderProductsCards {...{ selectedCartItems, productItems }} />
        </>}


      {/* Start Contact & Address Information */}
      <div>
        <h2 className='text-xl font-bold mb-4'>{tr["contact_address_information"]}</h2>
        <div className='flex flex-col md:flex-row items-center gap-8 '>
          <div className='p-4 border border-[#E5E7EB] rounded-md w-full min-h-60'>
            {selectedCard == 0 && <> {/* Delivery */}
              <div className="flex items-center justify-between mb-4">
                <h3 className='text-lg font-bold mb-3'>{tr["delivery_address"]}</h3>
                {EditBtn}
              </div>
              <div>
                {selectedAddress?.shipping ? (
                  <>
                    <p title={tr["name"]}>{selectedAddress.shipping?.first_name} {selectedAddress.shipping?.last_name}</p>
                    <p title={tr["email"]}>{selectedAddress.shipping?.email}</p>
                    <p title={tr["phone"]}>{selectedAddress.shipping?.phone}</p>
                    <p title={tr["address"]}>{selectedAddress.shipping?.address}</p>
                    <p title={tr["city,state,(postal_code)"]}>{selectedAddress.shipping?.city}, {selectedAddress.shipping?.state}, ({selectedAddress.shipping?.postal_code})</p>
                    <p title={tr["country"]}>{selectedAddress.shipping?.country?.name}</p>
                  </>
                ) : (
                  <p>{tr["no_delivery_address_available"]}</p>
                )}
              </div>
            </>}
            {selectedCard == 1 && <> {/* pickup */}
              <div className="flex items-center justify-between mb-4">
                <h3 className='text-lg font-bold mb-3'>{tr["pickup_info"]} </h3>
                {EditBtn}
              </div>
              <div>
                {!!selectedAddress?.pickup ? (
                  <>
                    <p title={tr["picker_name"]}>{selectedAddress.pickup?.name}</p>
                    <p title={tr["picker_phone"]}>{selectedAddress.pickup?.phone}</p>
                  </>
                ) : (
                  <p>{tr["no_pickup_ino_available"]}</p>
                )}
              </div>
            </>}

          </div>
          <div className='p-4 border border-[#E5E7EB] rounded-md w-full min-h-60'>
            <div className="flex items-center justify-between mb-4">
              <h3 className='text-lg font-bold mb-3'>{tr["invoice_address"]}</h3>
              {EditBtn}
            </div>
            <div>
              {selectedAddress?.billing ? (
                <>
                  <p title={tr["name"]}>{selectedAddress.billing?.first_name} {selectedAddress.billing?.last_name}</p>
                  <p title={tr["email"]}>{selectedAddress.billing?.email}</p>
                  <p title={tr["phone"]}>{selectedAddress.billing?.phone}</p>
                  <p title={tr["address"]}>{selectedAddress.billing?.address}</p>
                  <p title={tr["city_state_postal_code"]}>{selectedAddress.billing?.city}, {selectedAddress.billing?.state}, ({selectedAddress.billing?.postal_code})</p>
                  <p title={tr["country"]}>{selectedAddress.billing?.country?.name}</p>
                  <p title={tr["additional_notes"]}>{selectedAddress.additionalNotes}</p>
                </>
              ) : (
                <p>{tr["no_invoice_address_available"]}</p>
              )}
            </div>
          </div>
        </div>
      </div>
      {/* End Contact & Address Information */}

    </>
  )
}
