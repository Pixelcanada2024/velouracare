import { Link, usePage } from "@inertiajs/react"
import React, { useEffect, useState } from "react"
import ShippingToDoor from "./ShippingOptions/ShippingToDoor"
import ShippingPickup from "./ShippingOptions/ShippingPickup"
import EditAddressModal from "./ShippingOptions/Partials/EditAddressModal"
import TextAreaInput from "@/components/ui/TextAreaInput"
import { useTranslation } from "@/contexts/TranslationContext"
import ShippingPartners from "./ShippingOptions/ShippingPartners";

export default function ShippingOptions({ BillingSavedAddress, ShippingSavedAddress, countries, setNextBtnIsDisabled, setSelectedCardStatus }) {
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();

  const [selectedCard, setSelectedCard] = setSelectedCardStatus  // 0 -> Deliver , 1 -> Pickup

  const [selectedAddress, setSelectedAddress] = useState(
    localStorage.getItem("selectedAddress")
      ? JSON.parse(localStorage.getItem("selectedAddress"))
      : {
        type: "shipping",
        shipping: ShippingSavedAddress ?? null,
        billing: BillingSavedAddress ?? null,
        pickup: {
          name: null,
          phone: null
        },
        additionalNotes: null,
        sameAsDelivery: false,
      }
  )

  const [showEditAddressModal, setShowEditAddressModal] = useState(false);

  const options = [
    {
      label: tr["delivery"],
      description: tr["we_arrange_shipping_on_your_behalf"],
      icon: <>
        <svg className="w-[35px] h-[35px] sm:w-[40px] sm:h-[40px]" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M20.2461 25.7488V9.24878C20.2461 8.51943 19.9564 7.81996 19.4406 7.30424C18.9249 6.78851 18.2254 6.49878 17.4961 6.49878H6.49609C5.76675 6.49878 5.06727 6.78851 4.55155 7.30424C4.03582 7.81996 3.74609 8.51943 3.74609 9.24878V24.3738C3.74609 24.7385 3.89096 25.0882 4.14882 25.3461C4.40668 25.6039 4.75642 25.7488 5.12109 25.7488H7.87109M21.6211 25.7488H13.3711M27.1211 25.7488H29.8711C30.2358 25.7488 30.5855 25.6039 30.8434 25.3461C31.1012 25.0882 31.2461 24.7385 31.2461 24.3738V19.355C31.2455 19.043 31.1389 18.7404 30.9436 18.497L26.1586 12.5158C26.03 12.3547 25.8668 12.2247 25.6812 12.1352C25.4956 12.0457 25.2922 11.9991 25.0861 11.9988H20.2461" stroke="#222222" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" />
          <path d="M24.375 28.4988C25.8938 28.4988 27.125 27.2676 27.125 25.7488C27.125 24.23 25.8938 22.9988 24.375 22.9988C22.8562 22.9988 21.625 24.23 21.625 25.7488C21.625 27.2676 22.8562 28.4988 24.375 28.4988Z" stroke="#222222" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" />
          <path d="M10.6211 28.4988C12.1399 28.4988 13.3711 27.2676 13.3711 25.7488C13.3711 24.23 12.1399 22.9988 10.6211 22.9988C9.10231 22.9988 7.87109 24.23 7.87109 25.7488C7.87109 27.2676 9.10231 28.4988 10.6211 28.4988Z" stroke="#222222" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </>
    },
    {
      label: tr["self_pickup"],
      description: tr["we_arrange_pickup_yourself_at_our_warehouse"],
      icon: <>
        <svg className="w-[35px] h-[35px] sm:w-[40px] sm:h-[40px]" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M21.6206 16.1238L20.2457 28.4985M27.1205 16.1238L21.6206 6.49902M3.74609 16.1238H31.2454M5.80854 16.1238L8.00849 26.2985C8.13705 26.929 8.48264 27.4945 8.9851 27.8964C9.48756 28.2984 10.1151 28.5114 10.7584 28.4985H24.2331C24.8764 28.5114 25.5039 28.2984 26.0064 27.8964C26.5088 27.4945 26.8544 26.929 26.983 26.2985L29.3204 16.1238M7.18351 22.3111H27.808M7.87099 16.1238L13.3708 6.49902M13.3708 16.1238L14.7458 28.4985" stroke="#222222" stroke-width="1.64996" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </>
    }
  ]

  useEffect(() => {
    localStorage.setItem("selectedAddress", JSON.stringify(selectedAddress))
  }, [selectedAddress])

  useEffect(() => {
    setNextBtnIsDisabled(!((
      selectedCard == 0 && !!selectedAddress?.shipping?.first_name && !!selectedAddress?.billing?.first_name
    ) || (
        selectedCard == 1 && !!selectedAddress?.pickup?.name && !!selectedAddress?.pickup?.phone && !!selectedAddress?.billing?.first_name
      )))
  }, [selectedCard, selectedAddress])


  return (
    <>
      <h3 className='text-[20px] sm:text-2xl font-semibold'>{tr["shipping_options"]}</h3>
      <div className="flex max-sm:flex-col justify-center gap-4 xl:gap-8 max-sm:min-h-48">
        {options.map((opt, idx) => (
          <div
            key={idx}
            onClick={() => setSelectedCard(idx)}
            className={`flex sm:flex-col items-center sm:text-center gap-4 border rounded-xl px-5 sm:py-5  flex-1 cursor-pointer ${selectedCard === idx ? "border-black bg-gray-50" : "border-gray-200"}`}
          >
            {opt.icon}
            <div>
              <h4 className='text-sm sm:text-base font-semibold sm:mb-[8px]'>{opt.label}</h4>
              <p className='text-xs sm:text-sm text-[#7E7E87]'>{opt.description}</p>
            </div>
          </div>
        ))}
      </div>
      <div className="mt-8">
        {selectedCard === 0 && <ShippingToDoor ShowEditAddressModalState={[showEditAddressModal, setShowEditAddressModal]} SelectedAddressState={[selectedAddress, setSelectedAddress]} selectedCard={selectedCard} />}
        {selectedCard === 1 && <ShippingPickup ShowEditAddressModalState={[showEditAddressModal, setShowEditAddressModal]} SelectedAddressState={[selectedAddress, setSelectedAddress]} />}
        {/* Additional Request */}
        <div className="mt-8">
          <h3 className='text-xl font-medium mb-3'>{tr["additional_request"]}</h3>
          <TextAreaInput
            name="additionalNotes"
            value={selectedAddress.additionalNotes}
            onChange={(e) => setSelectedAddress({ ...selectedAddress, additionalNotes: e.target.value })}
            placeholder={tr["type_your_request_here"]}
          />
        </div>
      </div>

      <div className="relative z-200">
        <EditAddressModal
          isOpen={showEditAddressModal}
          onClose={() => setShowEditAddressModal(false)}
          SelectedAddressState={[selectedAddress, setSelectedAddress]}
          countries={countries}
        />
      </div>

    </>
  )
}
