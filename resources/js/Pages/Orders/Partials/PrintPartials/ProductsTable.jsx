import { useTranslation } from "@/contexts/TranslationContext";

export default function ProductsTable({ order }) {
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();
  const orderCurrency = tr[order.additional_info["invoice"]["currency"]] ?? "SAR";

  return (
    <div className="max-lg:hidden overflow-x-auto">
      <div className="overflow-x-auto max-lg:hidden">
        <div className="bg-white  overflow-hidden">
          <table className="min-w-full divide-y divide-[#E5E7EB] border-b border-b-[#E5E7EB] ">
            <thead className="bg-[#F2F4F7]">
              <tr>
                <th className="px-3 py-5 ltr:text-left rtl:text-right  font-medium text-sm ">
                  {tr["products"]}
                </th>
                <th className="px-3 py-5 text-center  font-medium text-sm ">
                  {tr["brand"]}
                </th>
                <th className="px-3 py-5 text-center  font-medium text-sm ">
                  {tr["barcode"]}
                </th>
                <th className="px-3 py-5 text-center  font-medium text-sm ">
                  {tr["unit_price"]}
                </th>
                <th className="px-3 py-5 text-center  font-medium text-sm ">
                  {tr["qty"]}
                </th>
                <th className="px-3 py-5 text-center  font-medium text-sm ">
                  {tr["total_price"]}
                </th>
              </tr>
            </thead>
            <tbody className="bg-white divide-y divide-[#E5E7EB]">
              {order?.order_details?.map((order_detail) => <tr key={order_detail.id}>
                <td className="p-3 text-sm">
                  <p
                    className="truncate max-w-[200px]"
                    title={order_detail?.variation?.[`name_${lang}`] || order_detail?.variation?.name}
                  >
                    {order_detail?.variation?.[`name_${lang}`] || order_detail?.variation?.name}
                  </p>
                </td>
                <td className='p-3 text-center text-sm'>{order_detail?.variation?.brand}</td>
                <td className='p-3 text-center text-sm'>{order_detail?.variation?.barcode}</td>
                <td className='p-3 text-center text-sm'><span  dir="ltr">{orderCurrency}{order_detail?.variation?.price}</span></td>
                <td className='p-3 text-center text-sm'>{order_detail?.variation?.box_qty * order_detail?.quantity}</td>
                <td className='p-3 text-center text-sm'><span  dir="ltr">{orderCurrency}{order_detail?.price}</span></td>
              </tr>)}
            </tbody>
          </table>

          {/* Total Section */}
          <div className='divide-[#E5E7EB] divide-y border-t border-t-[#E5E7EB] text-sm'>
            <div className='p-3 flex justify-between'>
              <span>{tr["subtotal"]}</span>
              <span dir="ltr">{orderCurrency}{order?.additional_info?.["invoice"]["cart_total"]}</span>
            </div>
            {order?.additional_info?.["invoice"]["tax_amount"] > 0 && <div className='p-3 flex justify-between'>
              <span>{tr["tax"]}</span>
              <span  dir="ltr">{orderCurrency}{order?.additional_info?.["invoice"]["tax_amount"]}</span>
            </div>}
            {order?.additional_info?.["invoice"]["shipping_cost"] > 0 && <div className='p-3 flex justify-between'>
              <span>{tr["shipping_fee"]}</span>
              <span  dir="ltr">{orderCurrency}{order?.additional_info?.["invoice"]["shipping_cost"]}</span>
            </div>}
            {order?.additional_info?.["invoice"]["discount_amount"] > 0 && <div className='p-3 flex justify-between'>
              <span>{tr["discount"]}</span>
              <span  dir="ltr">{orderCurrency}{order?.additional_info?.["invoice"]["discount_amount"]}</span>
            </div>}
            <div className='p-3 flex justify-between font-semibold'>
              <span>{tr["total_amount"]}</span>
              <span dir="ltr">{orderCurrency}{order?.grand_total}</span>
            </div>
          </div>
        </div>
      </div>
    </div >
  );
};
