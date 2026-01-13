import React from 'react'
import { Link } from "@inertiajs/react";
import { useTranslation } from '@/contexts/TranslationContext';

export default function OrdersProductsCard({ order, paymentDone }) {
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();
  const orderCurrency = tr[order.additional_info["invoice"]["currency"]] ?? "SAR";

  const orderDetails = order?.order_details || [];


  return (
    <>
      <div className="bg-white border-gray-200 lg:hidden">
        <div className="text-[20px] sm:text-[28px] xl:text-[30px]  font-medium">
          {tr["order_information"]}
        </div>

        <div className='bg-[#F2F4F7] px-3 py-5 mt-3'>
          {tr["orders_list"]}
        </div>
        <div className="divide-y divide-gray-200 text-sm" >

          {/* Start Product */}
          {orderDetails.map((order_detail, index) => <div key={order_detail.id} >
            <div className='flex items-center justify-between gap-5 px-3 py-1 '>
              <span className='text-[#818181]'>{tr["product"]}</span>
              <div className="flex flex-col gap-1">
                <span className="uppercase text-[#9CA3AF] self-end">
                  {order_detail?.variation?.brand}
                </span>
                <span
                  className="truncate max-w-[200px]"
                  title={order_detail?.variation?.[`name_${lang}`] || order_detail?.variation?.name}
                >
                  {order_detail?.variation?.[`name_${lang}`] || order_detail?.variation?.name}
                </span>
              </div>
            </div>
            {paymentDone &&
              <div className='flex items-center justify-between gap-5 px-3 py-1'>
                <span className='text-[#818181]'>{tr["unit_price"]}</span>
                <span dir='ltr'>
                  {orderCurrency}
                  {(Number(order_detail?.variation?.price) || 0).toFixed(2)}
                </span>
              </div>
            }

            <div className='flex items-center justify-between gap-5 px-3 py-1'>
              <span className='text-[#818181]'>{tr["boxes"]}</span>
              <span>{order_detail?.quantity}</span>
            </div>

            <div className='flex items-center justify-between gap-5 px-3 py-1'>
              <span className='text-[#818181]'>{tr["total_qty"]}</span>
              <span>{order_detail?.variation?.box_qty * order_detail?.quantity}</span>
            </div>
            {paymentDone &&
              <div className='flex items-center justify-between gap-5 px-3 py-1'>
                <span className='text-[#818181]'>{tr["total_price"]}</span>
                <span dir='ltr'>
                  {orderCurrency}
                  {(Number(order_detail?.price) || 0).toFixed(2)}
                </span>
              </div>
            }


          </div>)}
          {/* end Product */}

          {/* Total Section */}
          {paymentDone &&
            (<div className='divide-[#E5E7EB] divide-y '>
              <div className='p-3 flex justify-between font-medium'>
                <span>{tr["subtotal"]}</span>
                <span dir='ltr'>
                  {orderCurrency}
                  {(Number(order?.additional_info?.['invoice']['cart_total']) || 0).toFixed(2)}
                </span>
              </div>

              {order?.additional_info?.['invoice']['tax_amount'] > 0 && (
                <div className='p-3 flex justify-between font-medium'>
                  <span>{tr["tax"]}</span>
                  <span dir='ltr'>
                    {orderCurrency}
                    {(Number(order?.additional_info?.['invoice']['tax_amount']) || 0).toFixed(2)}
                  </span>
                </div>
              )}

              {order?.additional_info?.['invoice']['shipping_cost'] > 0 && (
                <div className='p-3 flex justify-between font-medium'>
                  <span>{tr["shipping"]}</span>
                  <span dir='ltr'>
                    {orderCurrency}
                    {(Number(order?.additional_info?.['invoice']['shipping_cost']) || 0).toFixed(2)}
                  </span>
                </div>
              )}

              {order?.additional_info?.['invoice']['discount_amount'] > 0 && (
                <div className='p-3 flex justify-between font-medium'>
                  <span>{tr["discount"]}</span>
                  <span dir='ltr'>
                    {orderCurrency}
                    {(Number(order?.additional_info?.['invoice']['discount_amount']) || 0).toFixed(2)}
                  </span>
                </div>
              )}

              <div className='p-3 flex justify-between bg-[#F2F4F7] font-bold'>
                <span>{tr["total_amount"]}</span>
                <span dir='ltr'>
                  {orderCurrency}
                  {(Number(order?.grand_total) || 0).toFixed(2)}
                </span>
              </div>
            </div>)
          }


        </div>

      </div>
    </>
  )
}
