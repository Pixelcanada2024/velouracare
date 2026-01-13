import React from "react"
import { Link } from "@inertiajs/react";
import { useTranslation } from "@/contexts/TranslationContext";
import SearchInput from "@/components/ui/SearchInput";
import { usePage } from "@inertiajs/react";

export default function OrdersTable({ orders }) {
  const { queryParams , orderStatusOrder } = usePage().props;
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();

  const statusColors = {
    processing: "bg-[#FFE5B1]",
    payment: "bg-[#F3B8FF]",
    on_the_way: "bg-[#9AD7FF]",
    picked_up: "bg-[#FFBCDE]",
    confirmed: "bg-[#CFFFF9]",
    cancelled: "bg-[#FF7A7A]",
    delivered: "bg-[#72F98F]",
  };

  return (
    <>
      <div className="overflow-x-auto max-xl:hidden">

        <div className="flex justify-between items-center">
          <p className='text-[34px] sm:text-[40px] xl:text-[52px] font-bold my-4'>{tr["orders_list"]}</p>

          <SearchInput
            placeholder={tr["search_by_order_id"]}
            routeName="react.dashboard.orders"
            queryKey="search"
            value={queryParams?.search || ""}
            className="min-w-[420px]"
          />
        </div>

        <table className="w-full">
          <thead className='mb-4'>
            <tr className="bg-[#F2F4F7] text-sm">
              <th className="p-4 text-center text-[#222222]" width="10%">{tr["order_id"]}</th>
              <th className="p-4 text-center text-[#222222]" width="15%">{tr["the_products"]}</th>
              <th className="p-4 text-center text-[#222222]" width="10%">{tr["total_quantity"]}</th>
              <th className="p-4 text-center text-[#222222]" width="10%">{tr["total_amount"]}</th>
              <th className="p-4 text-center text-[#222222]" width="10%">{tr["date"]}</th>
              <th className="p-4 text-center text-[#222222]" width="10%">{tr["status"]}</th>
              <th className="p-4 text-center text-[#222222]" width="10%">{tr["pdf_invoice"]}</th>
              <th className="p-4 text-center text-[#222222]" width="10%"></th>
            </tr>
          </thead>

          {/* ------------------------------------------------------------- */}
          {!!orders?.data?.length ? (
            <tbody className='[&_td]:odd:bg-gray-50'>
              {orders?.data?.map((order) => (
                <tr className='mt-4 text-sm text-center' key={order.id}>
                  <td className='p-4 text-center' dir='ltr'>#{order.code}</td>
                  <td className="p-4">
                    <div className="truncate max-w-[200px] ltr:text-left rtl:text-right !text-center mx-auto">
                      {order?.order_details?.slice(0, 2).map((item, index) => {
                        const variation = item?.variation;
                        const variationName = variation?.[`name_${lang}`] || variation?.name;

                        return (
                          <div
                            key={index}
                            className="truncate max-w-[200px] ltr:text-left rtl:text-right "
                            title={variationName}
                          >
                            {variationName}
                          </div>
                        );
                      })}

                      {order?.order_details?.length > 2 && (
                        <span className="text-gray-500 ltr:text-left rtl:text-right">
                          {`... +${order?.order_details?.length - 2} ${tr["more_items"]}`}
                        </span>
                      )}
                    </div>
                  </td>
                  <td className='p-4'>
                    {order.order_details.reduce((sum, item) => {
                      const boxQty = Number(item?.variation?.box_qty) || 0;
                      const qty = Number(item?.quantity) || 0;
                      return sum + (boxQty * qty);
                    }, 0)}
                  </td>
                  { orderStatusOrder[order.delivery_status] >= orderStatusOrder["confirmed"] ? 
                  <td className='p-4'><span dir='ltr'>{tr[order.additional_info["invoice"]["currency"]] ?? "SAR"}{(Number(order.grand_total) || 0).toFixed(2)}</span></td> 
                  : <td className='p-4'>-</td>}
                  <td className='p-4'>{new Date(order.date * 1000).toISOString().split("T")[0]}</td>
                  <td className="p-4 text-center">
                    <span
                      className={`
                        p-1 py-[2px] min-w-30 max-w-30 rounded-2xl capitalize block mx-auto
                        ${statusColors[order.delivery_status] || "bg-gray-200"}
                      `}
                    >
                      {tr[order.delivery_status]}
                    </span>
                  </td>
                  <td className='text-center'>
                    <div className='flex flex-col min-h-[65px] justify-center items-center'>
                      <Link href={ 
                          orderStatusOrder[order.delivery_status] >= orderStatusOrder["confirmed"] 
                          ? route("orders.print", order.id) 
                          : "#" 
                        }
                        title={
                          orderStatusOrder[order.delivery_status] >= orderStatusOrder["confirmed"]
                          ? tr["download_the_invoice"]
                          : tr["order_not_confirmed_yet"]
                        }
                        >
                        <svg width="30" height="30" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg" className={`
                          ${orderStatusOrder[order.delivery_status] >= orderStatusOrder["confirmed"] ? "cursor-pointer" : "cursor-not-allowed opacity-50"}
                          `}>
                          <path d="M18.6462 1.55396L22.8192 5.90396V22.446H7.23047V22.5H22.8725V5.9587L18.6462 1.55396Z" fill="#818181" />
                          <path d="M18.5945 1.5H7.17725V22.446H22.8192V5.90475L18.5945 1.5Z" fill="#F8F8F8" />
                          <path d="M7.06252 2.625H2.27002V7.74525H17.345V2.625H7.06252Z" fill="#818181" />
                          <path d="M17.4254 7.6583H2.36768V2.5343H17.4254V7.6583Z" fill="#DD2025" />
                          <path d="M7.36013 3.4005H6.37988V7.0005H7.15088V5.78625L7.32113 5.796C7.48662 5.79372 7.65058 5.76407 7.80638 5.70825C7.94335 5.66175 8.06923 5.58744 8.17613 5.49C8.28575 5.39808 8.37189 5.28136 8.42738 5.1495C8.50332 4.93104 8.5302 4.69853 8.50613 4.4685C8.50186 4.30414 8.47304 4.14134 8.42063 3.9855C8.37341 3.87286 8.30319 3.77131 8.21446 3.68737C8.12574 3.60342 8.02047 3.53892 7.90538 3.498C7.80617 3.46126 7.70335 3.43511 7.59863 3.42C7.51953 3.40712 7.43953 3.4006 7.35938 3.4005M7.21763 5.121H7.15088V4.011H7.29563C7.35952 4.00639 7.42362 4.0162 7.48321 4.0397C7.54279 4.06319 7.59634 4.09978 7.63988 4.14675C7.73012 4.26751 7.77833 4.4145 7.77713 4.56525C7.77713 4.74975 7.77713 4.917 7.61063 5.03475C7.49071 5.10081 7.35416 5.13126 7.21763 5.121ZM9.97088 3.39075C9.88763 3.39075 9.80663 3.39675 9.74963 3.399L9.57113 3.4035H8.98613V7.0035H9.67463C9.93772 7.01027 10.1996 6.96569 10.4456 6.87225C10.6437 6.79402 10.8191 6.66742 10.9556 6.504C11.0895 6.33987 11.185 6.14796 11.2354 5.94225C11.2945 5.7097 11.3233 5.47045 11.3209 5.2305C11.3356 4.94712 11.3136 4.66302 11.2556 4.38525C11.2001 4.18102 11.0973 3.9927 10.9556 3.8355C10.8445 3.70838 10.7078 3.60616 10.5544 3.5355C10.423 3.4746 10.2848 3.42978 10.1426 3.402C10.0861 3.39274 10.0289 3.38847 9.97163 3.38925M9.83513 6.342H9.76013V4.044H9.76988C9.9245 4.02612 10.081 4.05403 10.2199 4.12425C10.3216 4.2055 10.4045 4.30785 10.4629 4.42425C10.5258 4.54675 10.5621 4.6812 10.5694 4.81875C10.5761 4.98375 10.5694 5.11875 10.5694 5.2305C10.5722 5.35922 10.5639 5.48794 10.5446 5.61525C10.521 5.7458 10.4783 5.87216 10.4179 5.99025C10.3496 6.10038 10.2563 6.19288 10.1456 6.26025C10.0532 6.32025 9.94354 6.34819 9.83363 6.33975M13.6436 3.4035H11.8211V7.0035H12.5921V5.5755H13.5671V4.9065H12.5921V4.0725H13.6421V3.4035" fill="#383B44" />
                          <path d="M16.9068 15.1913C16.9068 15.1913 19.2978 14.7578 19.2978 15.5745C19.2978 16.3913 17.8166 16.059 16.9068 15.1913ZM15.1391 15.2535C14.7591 15.3372 14.3889 15.4601 14.0343 15.6203L14.3343 14.9453C14.6343 14.2703 14.9456 13.35 14.9456 13.35C15.3026 13.9531 15.7192 14.5189 16.1891 15.039C15.8354 15.0917 15.4848 15.1638 15.1391 15.255V15.2535ZM14.1926 10.3785C14.1926 9.66676 14.4228 9.47251 14.6021 9.47251C14.7813 9.47251 14.9831 9.55876 14.9898 10.1768C14.9313 10.7982 14.8012 11.4107 14.6021 12.0023C14.3283 11.506 14.187 10.9475 14.1918 10.3808L14.1926 10.3785ZM10.7058 18.2655C9.97231 17.8268 12.2441 16.476 12.6558 16.4325C12.6536 16.4333 11.4738 18.7245 10.7058 18.2655ZM19.9961 15.6713C19.9886 15.5963 19.9211 14.766 18.4436 14.8013C17.8277 14.7905 17.2121 14.8339 16.6038 14.931C16.0142 14.3376 15.5067 13.6679 15.0948 12.9398C15.3542 12.1892 15.5113 11.4072 15.5621 10.6148C15.5403 9.71476 15.3251 9.19876 14.6351 9.20626C13.9451 9.21376 13.8446 9.81751 13.9353 10.716C14.0241 11.3198 14.1918 11.9093 14.4341 12.4695C14.4341 12.4695 14.1153 13.4618 13.6938 14.4488C13.2723 15.4358 12.9843 15.9533 12.9843 15.9533C12.2512 16.1916 11.5612 16.5461 10.9406 17.0033C10.3226 17.5785 10.0713 18.0203 10.3968 18.462C10.6773 18.843 11.6591 18.9293 12.5366 17.7795C13.002 17.1851 13.4279 16.5607 13.8116 15.9105C13.8116 15.9105 15.1496 15.5438 15.5658 15.4433C15.9821 15.3428 16.4853 15.2633 16.4853 15.2633C16.4853 15.2633 17.7071 16.4925 18.8853 16.449C20.0636 16.4055 20.0066 15.7448 19.9991 15.6728" fill="#DD2025" />
                          <path d="M18.5366 1.55774V5.96249H22.7614L18.5366 1.55774Z" fill="#818181" />
                          <path d="M18.5947 1.5V5.90475H22.8195L18.5947 1.5Z" fill="#F8F8F8" />
                          <path d="M7.30252 3.34276H6.32227V6.94276H7.09627V5.72926L7.26727 5.73901C7.43275 5.73673 7.59671 5.70708 7.75252 5.65126C7.88948 5.60476 8.01536 5.53045 8.12227 5.43301C8.23107 5.34084 8.31641 5.22413 8.37127 5.09251C8.4472 4.87405 8.47409 4.64154 8.45002 4.41151C8.44574 4.24715 8.41692 4.08435 8.36452 3.92851C8.31729 3.81587 8.24707 3.71432 8.15834 3.63038C8.06962 3.54643 7.96435 3.48194 7.84927 3.44101C7.7496 3.40391 7.64627 3.37751 7.54102 3.36226C7.46191 3.34938 7.38191 3.34286 7.30177 3.34276M7.16002 5.06326H7.09327V3.95326H7.23877C7.30265 3.94865 7.36675 3.95846 7.42634 3.98196C7.48593 4.00546 7.53947 4.04204 7.58302 4.08901C7.67325 4.20977 7.72146 4.35676 7.72027 4.50751C7.72027 4.69201 7.72027 4.85926 7.55377 4.97701C7.43385 5.04307 7.29729 5.07278 7.16077 5.06251M9.91327 3.33301C9.83002 3.33301 9.74902 3.33901 9.69202 3.34126L9.51577 3.34576H8.93077V6.94576H9.61927C9.88235 6.95253 10.1442 6.90795 10.3903 6.81451C10.5884 6.73628 10.7637 6.60968 10.9003 6.44626C11.0341 6.28213 11.1297 6.09022 11.18 5.88451C11.2392 5.65196 11.2679 5.41271 11.2655 5.17276C11.2802 4.88938 11.2583 4.60528 11.2003 4.32751C11.1447 4.12328 11.042 3.93496 10.9003 3.77776C10.7891 3.65064 10.6524 3.54842 10.499 3.47776C10.3676 3.41686 10.2294 3.37204 10.0873 3.34426C10.0308 3.335 9.97353 3.33073 9.91627 3.33151M9.77977 6.28426H9.70477V3.98626H9.71452C9.86914 3.96838 10.0256 3.99629 10.1645 4.06651C10.2663 4.14776 10.3492 4.25011 10.4075 4.36651C10.4705 4.48901 10.5068 4.62347 10.514 4.76101C10.5208 4.92601 10.514 5.06101 10.514 5.17276C10.5168 5.30148 10.5085 5.43021 10.4893 5.55751C10.4657 5.68806 10.423 5.81442 10.3625 5.93251C10.2942 6.04264 10.201 6.13514 10.0903 6.20251C9.9978 6.26251 9.88817 6.29045 9.77827 6.28201M13.586 3.34576H11.7635V6.94576H12.5345V5.51776H13.5095V4.84876H12.5345V4.01476H13.5845V3.34576" fill="white" />
                        </svg>
                      </Link>
                    </div>
                  </td>

                  <td className='text-center p-4'><Link
                    href={route("react.dashboard.orders.show", order.id)}
                    className="font-medium text-[#004AAD] underline"
                  >
                    {tr["view_details"]}
                  </Link>
                  </td>
                </tr>
              ))}
            </tbody>
          ) : (
            <tbody>
              <tr >
                <td colSpan="8" >
                  <div className="text-center py-12 bg-[#F2F4F7] mt-4">
                    <div className="text-6xl mb-4 flex justify-center items-center"><svg width="57" height="57" viewBox="0 0 57 57" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M49.3483 26.1877V21.017C49.3513 19.7759 49.022 18.5566 48.3946 17.4857C47.7673 16.4148 46.8647 15.5314 45.7806 14.927L31.881 7.21072C30.847 6.63535 29.6833 6.33337 28.5 6.33337C27.3167 6.33337 26.153 6.63535 25.119 7.21072L11.2193 14.9247C10.1349 15.5292 9.23211 16.4131 8.60473 17.4844C7.97735 18.5557 7.64826 19.7755 7.65164 21.017V35.983C7.64867 37.2242 7.97796 38.4435 8.60532 39.5144C9.23268 40.5853 10.1352 41.4687 11.2193 42.0731L25.119 49.7894C26.153 50.3648 27.3167 50.6667 28.5 50.6667C29.6833 50.6667 30.847 50.3648 31.881 49.7894L37.7656 46.5227" stroke="#CDCDCD" strokeWidth="3.5" strokeLinecap="round" strokeLinejoin="round" />
                      <path d="M47.9366 16.8242L28.4999 28.5002M28.4999 28.5002L9.06323 16.8242M28.4999 28.5002V50.6436" stroke="#CDCDCD" strokeWidth="3.5" strokeLinecap="round" strokeLinejoin="round" />
                      <path d="M54.0152 34.905L44.6819 44.2197M44.6819 34.926L54.0152 44.2384" stroke="#CDCDCD" strokeWidth="3.5" strokeMiterlimit="10" strokeLinecap="round" />
                    </svg>
                    </div>
                    <p className="text-lg text-gray-500">{tr["there_are_no_orders_yet"]}!</p>
                  </div>
                </td>
              </tr>
            </tbody>
          )}

        </table>

      </div>
    </>
  )
}
