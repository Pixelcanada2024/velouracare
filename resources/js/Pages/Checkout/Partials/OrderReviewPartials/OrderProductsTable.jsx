import React from "react"
import { Link } from "@inertiajs/react";
import { useTranslation } from "@/contexts/TranslationContext";

export default function OrderProductsTable({ productItems , selectedCartItems }) {
  const [{lang, currency, tr}, _setTranslation] = useTranslation();

  return (
    <>
      <div className="overflow-x-auto max-sm:hidden">
        <div className="bg-white  overflow-hidden">
          <table className="min-w-full divide-y divide-[#E5E7EB]">
            <thead className="bg-[#F2F4F7]">
              <tr>
                <th className="px-3 py-5 text-start font-medium text-[#222222]" width="70%">
                  {tr["product"]}
                </th>
                <th className="px-3 py-5 font-medium text-[#222222] text-center" width="30%">
                  {tr["product_quantity"]} {tr["boxes"]}
                </th>
              </tr>
            </thead>
            <tbody className="bg-white divide-y divide-[#E5E7EB]">
              { productItems.map((item, index) => <tr key={index}>
                <td className='p-3'>
                  <div className='flex flex-col'>
                    <span className=' uppercase text-[#9CA3AF]'>{item.brand}</span>
                    <p className='sm:max-w-[500px] line-clamp-2' title={item.name}>{item.name}</p>
                  </div>
                </td>
                <td className='p-3 text-center'>{item.box_qty * selectedCartItems[item.id] }  {tr["pcs"]}  ({selectedCartItems[item.id]} {tr["boxes"]})</td>
              </tr>)}
            </tbody>
          </table>

        </div>

      </div>
    </>
  )
}
