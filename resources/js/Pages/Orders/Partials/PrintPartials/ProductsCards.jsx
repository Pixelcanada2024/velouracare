import { useTranslation } from "@/contexts/TranslationContext";

export default function ProductsCards({ order }) {
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();
  const orderCurrency = tr[order.additional_info["invoice"]["currency"]] ?? "SAR";

  return (
    <>
      <div className="bg-white border-gray-200 lg:hidden">
        <div className='bg-[#F2F4F7] px-3 py-5 mt-3 font-medium'>
          {tr["products"]}
        </div>
        <div className="divide-y divide-gray-200 " >

          {/* Start Product */}
          {order?.order_details?.map((order_detail) =>
            <div key={order_detail.id} className="py-3 space-y-1">
              <div className="flex items-center justify-between gap-5 px-3 ">
                <span className="text-[#666666] font-medium text-sm">
                  {tr["product_name"]}
                </span>
                <span
                  className="truncate max-w-[200px] text-sm"
                  title={order_detail?.variation?.[`name_${lang}`] || order_detail?.variation?.name}
                >
                  {order_detail?.variation?.[`name_${lang}`] || order_detail?.variation?.name}
                </span>
              </div>
              <div className='flex items-center justify-between gap-5 px-3  '>
                <span className='text-[#666666] font-medium text-sm'>{tr["brand"]}</span>
                <span className=' uppercase  self-end text-sm'>{order_detail?.variation?.brand}</span>
              </div>
              <div className='flex items-center justify-between gap-5 px-3  '>
                <span className='text-[#666666] font-medium text-sm'>{tr["barcode"]}</span>
                <span className="text-sm" >{order_detail?.variation?.barcode}</span>
              </div>
              <div className='flex items-center justify-between gap-5 px-3  '>
                <span className='text-[#666666] font-medium text-sm'>{tr["unit_price"]}</span>
                <span className="text-sm" dir="ltr">{orderCurrency}{order_detail?.variation?.price}</span>
              </div>
              <div className='flex items-center justify-between gap-5 px-3  '>
                <span className='text-[#666666] font-medium text-sm'>{tr["qty"]}</span>
                <span className="text-sm">{order_detail?.variation?.box_qty * order_detail?.quantity}</span>
              </div>
              <div className='flex items-center justify-between gap-5 px-3  '>
                <span className='text-[#666666] font-medium text-sm'>{tr["total_price"]}</span>
                <span className="text-sm"  dir="ltr">{orderCurrency}{order_detail?.price}</span>
              </div>
            </div>)}
          {/* end Product */}

          {/* Total Section */}
          <div className='divide-[#E5E7EB] divide-y bg-[#F9F9F9] text-sm'>
            <div className='p-3 flex justify-between'>
              <span className="">{tr["subtotal"]}</span>
              <span  dir="ltr">{orderCurrency}{order?.additional_info?.['invoice']['cart_total']}</span>
            </div>
            {order?.additional_info?.['invoice']['tax_amount'] > 0 && <div className='p-3 flex justify-between'>
              <span className="">{tr["tax"]}</span>
              <span  dir="ltr">{orderCurrency}{order?.additional_info?.['invoice']['tax_amount']}</span>
            </div>}
            {order?.additional_info?.['invoice']['shipping_cost'] > 0 && <div className='p-3 flex justify-between'>
              <span className="">{tr["shipping_fee"]}</span>
              <span  dir="ltr">{orderCurrency}{order?.additional_info?.['invoice']['shipping_cost']}</span>
            </div>}
            {order?.additional_info?.['invoice']['discount_amount'] > 0 && <div className='p-3 flex justify-between'>
              <span className="">{tr["discount"]}</span>
              <span  dir="ltr">{orderCurrency}{order?.additional_info?.['invoice']['discount_amount']}</span>
            </div>}

            <div className='p-3 flex justify-between font-semibold'>
              <span>{tr["total_amount"]}</span>
              <span  dir="ltr">{orderCurrency}{order?.grand_total}</span>
            </div>
          </div>

        </div>

      </div>
    </>
  );
};
