import React from "react"
import { Link } from "@inertiajs/react";
import { useTranslation } from "@/contexts/TranslationContext";

export default function OrdersProductsCard({ productItems , selectedCartItems }) {
  const [{lang, currency, tr}, _setTranslation] = useTranslation();

  return (
    <>
      <div className="bg-white border-gray-200 sm:hidden">
        <div className='bg-[#F2F4F7] font-medium px-3 py-5 mt-3'>
        {tr["the_products"]}
        </div>
        <div className="divide-y divide-gray-200 " >
          {/* Start Product */}
          {  productItems.map((item, index) => <div className='p-4 space-y-1' key={index}>
            <span className='text-[#9CA3AF] uppercase'>{item.brand}</span>
            <p className='max-w-full line-clamp-2' title={item.name}>{item.name}</p>
            <div className='text-[#818181] flex items-center gap-1'>
              <span>{item.box_qty * selectedCartItems[item.id] } {tr["pcs"]} ({selectedCartItems[item.id]} {tr["boxes"]})</span>
              {/* <span>|</span>
              <span>${ (item.order_price_per_box * +selectedCartItems[item.id]).toFixed(2) }</span> */}
            </div>
          </div>)}
          {/* end Product */}

        </div>
      </div>
    </>
  )
}
