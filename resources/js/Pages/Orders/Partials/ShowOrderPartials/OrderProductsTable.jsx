import React from "react"
import { Link } from "@inertiajs/react";
import { useTranslation } from "@/contexts/TranslationContext";

export default function OrderProductsTable({ order, paymentDone }) {
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();
  const orderCurrency = tr[order.additional_info["invoice"]["currency"]] ?? "SAR";

  const orderDetails = order?.order_details || [];

  return (
    <>
      <div className="overflow-x-auto max-lg:hidden">
        <div className="text-[20px] sm:text-[28px] xl:text-[30px]  font-medium my-10">
          {tr["order_information"]}
        </div>
        <div className="bg-white  overflow-hidden">

          <table className="min-w-full divide-y divide-[#E5E7EB]">
            <thead className="bg-[#F2F4F7] text-xs">
              <tr>
                <th className="px-3 py-5 ltr:text-left rtl:text-right font-medium text-[#222222]">
                  {tr["products"]}
                </th>
                {paymentDone &&
                  <th className="px-3 py-5 text-center font-medium text-[#222222]">
                    {tr["unit_price"]}
                  </th>
                }
                <th className="px-3 py-5 text-center font-medium text-[#222222]">
                  {tr["boxes"]}
                </th>

                <th className="px-3 py-5 text-center font-medium text-[#222222]">
                  {tr["total_qty"]}
                </th>


                {paymentDone &&
                  <th className="px-3 py-5 text-center font-medium text-[#222222]">
                    {tr["total_price"]}
                  </th>
                }
              </tr>
            </thead>

            <tbody className="bg-white divide-y divide-[#E5E7EB] text-sm">
              {orderDetails.map((order_detail) => (

                <tr key={order_detail.id} className="border-b border-[#E5E7EB]">

                  <td className="p-3">
                    <div className="flex flex-col">
                      <span className="uppercase text-[#9CA3AF]">
                        {order_detail?.variation?.brand}
                      </span>
                      <p
                        className="truncate max-w-[200px]"
                        title={order_detail?.variation?.[`name_${lang}`] || order_detail?.variation?.name}
                      >
                        {order_detail?.variation?.[`name_${lang}`] || order_detail?.variation?.name}
                      </p>
                    </div>
                  </td>
                  {paymentDone &&
                    <td className="p-3 text-center">
                      <span dir='ltr'>
                        {orderCurrency}
                        {(Number(order_detail?.variation?.price) || 0).toFixed(2)}
                      </span>
                    </td>
                  }
                  <td className="p-3 text-center">{order_detail?.quantity}</td>
                  <td className="p-3 text-center">
                    {order_detail?.variation?.box_qty * order_detail?.quantity}
                  </td>
                  {paymentDone &&
                    <td className="p-3 text-center">
                      <span dir='ltr'>
                        {orderCurrency}
                        {(Number(order_detail?.price) || 0).toFixed(2)}
                      </span>
                    </td>
                  }
                </tr>
              ))}
            </tbody>

            {/* Totals Section */}
            {paymentDone && (
              <tfoot className="bg-white text-sm xl:text-base border-t border-[#E5E7EB]">
                <tr className="border-t border-[#E5E7EB] font-medium">
                  <td colSpan="4" className="p-3 ltr:text-left rtl:text-right text-[#222]">
                    {tr["subtotal"]}
                  </td>
                  <td className="p-3 text-center">
                    <span dir='ltr'>
                      {orderCurrency}
                      {(Number(order?.additional_info?.["invoice"]["cart_total"]) || 0).toFixed(2)}
                    </span>
                  </td>
                </tr>
                {order?.additional_info?.["invoice"]["tax_amount"] > 0 && (
                  <tr className="border-t border-[#E5E7EB] font-medium">
                    <td colSpan="4" className="p-3 text-right ltr:text-left rtl:text-right text-[#222]">
                      {tr["tax"]}
                    </td>
                    <td className="p-3 text-center">
                      <span dir='ltr'>
                        {orderCurrency}
                        {(Number(order?.additional_info?.["invoice"]["tax_amount"]) || 0).toFixed(2)}
                      </span>
                    </td>
                  </tr>
                )}
                {order?.additional_info?.["invoice"]["shipping_cost"] > 0 && (
                  <tr className="border-t border-[#E5E7EB] font-medium">
                    <td colSpan="4" className="p-3 text-right ltr:text-left rtl:text-right text-[#222]">
                      {tr["shipping"]}
                    </td>
                    <td className="p-3 text-center">
                      <span dir='ltr'>
                        {orderCurrency}
                        {(Number(order?.additional_info?.["invoice"]["shipping_cost"]) || 0).toFixed(2)}
                      </span>
                    </td>
                  </tr>
                )}
                {order?.additional_info?.["invoice"]["discount_amount"] > 0 && (
                  <tr className="border-t border-[#E5E7EB] font-medium">
                    <td colSpan="4" className="p-3 text-right ltr:text-left rtl:text-right text-[#222]">
                      {tr["discount"]}
                    </td>
                    <td className="p-3 text-center">
                      <span dir='ltr'>
                        {orderCurrency}
                        {(Number(order?.additional_info?.["invoice"]["discount_amount"]) || 0).toFixed(2)}
                      </span>
                    </td>
                  </tr>
                )}

                <tr className="bg-[#F2F4F7] font-bold border-t border-[#E5E7EB]">
                  <td colSpan="4" className="p-3 ltr:text-left  rtl:text-right text-[#222]">
                    {tr["total_amount"]}
                  </td>
                  <td className="p-3 text-center">
                    <span dir='ltr'>
                      {orderCurrency}
                      {(Number(order?.grand_total) || 0).toFixed(2)}
                    </span>
                  </td>
                </tr>
              </tfoot>
            )}
          </table>



        </div>


      </div>
    </>
  )
}
