import Button from "@/components/ui/Button"
import TextAreaInput from "@/components/ui/TextAreaInput"
import { useTranslation } from "@/contexts/TranslationContext";
import React from "react"
import InvoiceAddress from "./Partials/InvoiceAddress";
import ShippingPartners from "./ShippingPartners";

export default function ShippingToDoor({ ShowEditAddressModalState, SelectedAddressState, selectedCard }) {
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();

  const [_, setShowEditAddressModal] = ShowEditAddressModalState;
  const [selectedAddress, setSelectedAddress] = SelectedAddressState;

  const billingSavedAddress = selectedAddress.billing;
  const shippingSavedAddress = selectedAddress.shipping;

  const handleEditAddress = (type) => {
    setSelectedAddress({ ...selectedAddress, type: type })
    setShowEditAddressModal(true);
  };

  return (
    <div>
      {/* Delivery Address Section */}
      <div className="mb-8">
        <h2 className="text-xl font-medium mb-3">{tr["delivery_address"]}</h2>

        <p className="text-[#7E7E87] mb-4 text-xs sm:text-sm ">{tr["please_provide_the_address_where_you_would_like_to_receive_your_order"]}</p>

        <div className="border border-[#E5E7EB] p-3 rounded-lg">
          {!!shippingSavedAddress ? (
            <div className='flex justify-between items-start p-2'>
              <div className="space-y-1 text-[#7E7E87] font-inter flex-1 text-sm">
                <div>{shippingSavedAddress.first_name} {shippingSavedAddress.last_name}</div>
                <div>{shippingSavedAddress.email}</div>
                <div>{shippingSavedAddress.phone}</div>
                <div>{shippingSavedAddress.city}, {shippingSavedAddress.state},{shippingSavedAddress.address_line_one},{shippingSavedAddress.address_line_two}</div>
                <div>{shippingSavedAddress.country?.name}</div>
                <div>{shippingSavedAddress.postal_code}</div>
              </div>
              <Button variant='outline' size="sm" onClick={() => handleEditAddress("shipping")}>
                <div className='flex items-center gap-2'>
                  <span className="max-sm:hidden">{tr["edit_address"]}</span>
                  <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.332 10.5001L6.66536 17.1667H3.33203V13.8334L9.9987 7.16675L12.3895 4.77592L12.3904 4.77508C12.7195 4.44592 12.8845 4.28092 13.0745 4.21925C13.2419 4.16487 13.4222 4.16487 13.5895 4.21925C13.7795 4.28092 13.9437 4.44592 14.2729 4.77425L15.7229 6.22425C16.0529 6.55425 16.2179 6.71925 16.2795 6.90925C16.3339 7.07661 16.3339 7.25689 16.2795 7.42425C16.2179 7.61425 16.0529 7.77925 15.7229 8.10925L13.332 10.5009L9.9987 7.16758" stroke="black" strokeWidth="1.25" strokeLinecap="round" strokeLinejoin="round" />
                  </svg>
                </div>
              </Button>
            </div>
          ) : (
            <div className="text-center py-4">
              <p className='text-lg font-bold sm:text-[20px] mb-1'>{tr["no_address_registered"]}</p>
              <p className='text-[#7E7E87]  text-xs sm:text-sm'>{tr["please_add_one_to_continue"]}</p>

              <Button className='mt-4' onClick={() => handleEditAddress("shipping")}>
                <div className='flex items-center gap-2'>
                  <span className="text-sm sm:text-base">{tr["add_address"]}</span>
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 12H12M12 12H6M12 12V6M12 12V18" stroke="white" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                  </svg>
                </div>
              </Button>
            </div>
          )}
        </div>
      </div>

      {/* Invoice Address Section */}
      <InvoiceAddress  {...{ billingSavedAddress, handleEditAddress, tr, selectedCard, selectedAddress, setSelectedAddress }} />

      {/* shipping partners */}
      <ShippingPartners {...{ tr }} />
    </div>
  )
}
