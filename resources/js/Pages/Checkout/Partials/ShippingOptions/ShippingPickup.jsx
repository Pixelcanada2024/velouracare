import Button from "@/components/ui/Button"
import PhoneInput from "@/components/ui/PhoneInput"
import TextAreaInput from "@/components/ui/TextAreaInput"
import TextInput from "@/components/ui/TextInput"
import { useTranslation } from "@/contexts/TranslationContext"
import React from "react"
import InvoiceAddress from "./Partials/InvoiceAddress"

export default function ShippingPickup({ ShowEditAddressModalState, SelectedAddressState }) {
  const [{lang, currency, tr}, _setTranslation] = useTranslation();

  const [_, setShowEditAddressModal] = ShowEditAddressModalState;
  const [selectedAddress, setSelectedAddress] = SelectedAddressState;

  const billingSavedAddress = selectedAddress.billing;

  const handleEditAddress = (type) => {
    setSelectedAddress({ ...selectedAddress, type: type })
    setShowEditAddressModal(true);
  };

  return (
    <div>
      <h3 className='text-xl font-semibold mb-3'>{tr["pickup_info"]}</h3>
      <p className='text-[#7E7E87] mb-4 text-xs sm:text-sm '>{tr["pickup_page_paragraph_1"]}</p>

      <div>
        <p className=' mb-3'>{tr["name_of_the_person_picking_up"]}</p>
        <TextInput
          value={selectedAddress?.pickup?.name ?? null}
          onChange={(e) => setSelectedAddress({ ...selectedAddress, pickup: { ...selectedAddress.pickup, name: e.target.value } })}
          placeholder={tr["enter_name"]} />
      </div>
      <div>
        <p className=' mb-3'>{tr["phone_number_of_the_person_picking_up"]}</p>
        <PhoneInput
          value={selectedAddress?.pickup?.phone ?? null}
          onChange={(e) => setSelectedAddress({ ...selectedAddress, pickup: { ...selectedAddress.pickup, phone: e.target.value } })}
        />
      </div>
      {/* Invoice Address Section */}
      <InvoiceAddress  {...{ billingSavedAddress, handleEditAddress, tr }}/>

    </div>
  )
}
