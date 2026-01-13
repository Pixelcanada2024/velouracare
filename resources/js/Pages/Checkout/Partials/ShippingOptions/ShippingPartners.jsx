import { usePage } from "@inertiajs/react";
import React from "react"

export default function ShippingPartners({tr}) {
  const shippingCompanyImages = {
    America: ["dhl.png", "fedex.png", "ups.png", "usps.png"],
    Gulf: ["aramex.png", "dhl.png", "naqel.png", "smsa.png", "ups.png"],
  };

  const currentWebsiteType = usePage().props.isSkyAmerica ? "America" : "Gulf";

  return (
    <div className="mt-8">
      <h3 className='text-xl font-medium mb-3'>{tr["shipping_partners"]}</h3>
      <p className="text-[#7E7E87] mb-4">{tr["checkout_shipping_partner_description"]}</p>
      <div className="flex flex-wrap justify-start gap-5 w-full">
        {shippingCompanyImages[currentWebsiteType].map((img, index) => (
          <div key={index} className="p-2 flex items-center justify-center border border-gray-200 rounded-lg bg-white">
            <img src={`/public/website-assets/checkout/Shipping/${currentWebsiteType}/${img}`} alt={img} className="w-28 h-14   lg:w-36 lg:h-18 object-contain" />
          </div>
        ))}
      </div>
    </div>
  )
}
