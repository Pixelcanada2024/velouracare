import React from "react"
import Layout from "@/components/Layout/Layout";
import OrderProductsTable from "./ShowOrderPartials/OrderProductsTable";
import OrderProductsCards from "./ShowOrderPartials/OrderProductsCards";
import Button from "@/components/ui/Button";
import { useTranslation } from "@/contexts/TranslationContext";
import OrderStatus from "./OrdersPartials/OrderStatus";
import { usePage } from "@inertiajs/react";

export default function ShowOrder({
  order
}) {
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();
  const orderCurrency = tr[order.additional_info["invoice"]["currency"]] ?? "SAR";

  // true only if the status is AFTER "payment"
  const { orderStatusOrder } = usePage().props;
  const paymentDone = orderStatusOrder[order.delivery_status] >= orderStatusOrder["confirmed"] ;

  return (
    <Layout
      pageTitle="Order Details"
      breadcrumbs={[
        { label: tr["home"], url: route("react.home") },
        { label: tr["orders"], url: route("react.dashboard.orders") },
        { label: `${tr["order"]} #${order.code}`, url: "#" },
      ]}
    >
      <div className="container mx-auto py-8">

        {/* Header */}
        <div>
          <Button size="sm" variant='outline' className='!border-[#E5E7EB]' href={route("react.dashboard.orders")} >
            <div className='flex items-center gap-4'>
              <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" className={lang === "ar" ? "rotate-180" : ""}>
                <path d="M15.75 8.99025L2.40825 9M7.491 3.75L2.25 9L7.491 14.25" stroke="black" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
              </svg>
              {tr["back_to_orders"]}
            </div>
          </Button>
          <h2 className='text-[34px] sm:text-[40px] xl:text-[52px] font-medium mt-8 mb-4'>{tr["order_details"]}</h2>
          <p className='text-base sm:text-[18px] text-[#7E7E87]'>{tr["order"]} <span dir="ltr">#{order.code}</span></p>
        </div>

        <div className='grid grid-cols-1 xl:grid-cols-4 gap-8 xl:gap-16 mt-6 mb-8'>

          <div className='xl:col-span-3 space-y-8'>

            {/*Start Invoice Information */}
            <div>

              {/* Header */}
              <div className='flex items-center justify-between mb-8'>
                <h3 className='text-[20px] sm:text-[28px] xl:text-[30px] font-medium'>{tr["invoice_information"]}</h3>
                { paymentDone && <div className="flex max-sm:flex-col-reverse gap-2">
                  <Button className='' href={route("orders.print", order.id)}>
                    <div className='flex items-center gap-4'>
                      <span className='' title={tr["download_invoice"]}>{tr["download_invoice"]}</span>
                      <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.25 7.5L9 11.25M9 11.25L12.75 7.5M9 11.25V2.25M16.5 11.25V14.25C16.5 14.6478 16.342 15.0294 16.0607 15.3107C15.7794 15.592 15.3978 15.75 15 15.75H3C2.60218 15.75 2.22064 15.592 1.93934 15.3107C1.65804 15.0294 1.5 14.6478 1.5 14.25V11.25" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                    </div>
                  </Button>
                  <a href={route("react.order-details-export", order.id)} target="_blank" rel="noopener noreferrer">
                    <Button className='!border-[#CECECE]' variant="outline">
                      <div className='flex items-center gap-4'>
                        <span className='' title={tr["download_excel"]}>{tr["download_excel"]}</span>
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path fill-rule="evenodd" clip-rule="evenodd" d="M16.3034 1.53642H10.5149V0L1 1.65421V16.154L10.5149 18V15.7227H16.3034C16.4788 15.7331 16.6507 15.6646 16.7813 15.5323C16.9119 15.4001 16.9906 15.2148 17 15.0173V2.24123C16.9904 2.0438 16.9117 1.85872 16.7811 1.72659C16.6505 1.59446 16.4787 1.52607 16.3034 1.53642ZM16.3949 15.1344H10.4954L10.4857 13.9185H11.9069V12.5025H10.4749L10.468 11.6657H11.9069V10.2497H10.4571L10.4503 9.41291H11.9069V7.99685H10.4457V7.16009H11.9069V5.74404H10.4457V4.90728H11.9069V3.49122H10.4457V2.2039H16.3949V15.1344Z" fill="#20744A" />
                          <path d="M12.6484 4.18457H15.0801V5.42207H12.6484V4.18457ZM12.6484 6.15388H15.0801V7.39138H12.6484V6.15388ZM12.6484 8.1232H15.0801V9.3607H12.6484V8.1232ZM12.6484 10.0925H15.0801V11.33H12.6484V10.0925ZM12.6484 12.0618H15.0801V13.2993H12.6484V12.0618Z" fill="#20744A" />
                          <path fill-rule="evenodd" clip-rule="evenodd" d="M3.57225 6.00373L4.77937 5.93455L5.53819 8.02086L6.43481 5.84848L7.64194 5.7793L6.17606 8.74142L7.64194 11.7109L6.36562 11.6248L5.50388 9.3613L4.64156 11.5387L3.46875 11.4352L4.83113 8.8123L3.57225 6.00373Z" fill="white" />
                        </svg>
                      </div>
                    </Button>
                  </a>
                </div>}
              </div>

              {/* Body */}
              <div className='text-[#333333] border-y border-[#E5E7EB] grid grid-cols-4 gap-2 [&>*]:py-2 [&>*]:text-sm [&>*]:md:text-base'>

                <p>{tr["invoicing_address"]}</p>
                <p className='col-span-3 '>
                  {order?.additional_info["billing"]["address_line_one"] + " " + order?.additional_info["billing"]["address_line_two"] + ", " + order?.additional_info["billing"]["city"] + ", " + order?.additional_info["billing"]["state"] + ", " + order?.additional_info["billing"]["postal_code"] + ", " + order?.additional_info["billing"]["country"]["name"]}
                </p>

                <p>{tr["contact"]}</p>
                <p className='col-span-3 '>
                  {order?.additional_info["billing"]["phone"]}
                </p>

                <p >{tr["recipient_name_co"]}</p>
                <p className='col-span-3 '>{order?.additional_info["billing"]["first_name"] + " " + order?.additional_info["billing"]["last_name"]}</p>


              </div>

            </div>
            {/*End Invoice Information */}

            {/*Start Shipping Information */}
            <div>

              {/* Header */}
              {order?.shipping_type === "delivery"
                ? <>
                  <div className='mb-8'>
                    <h3 className='text-[20px] sm:text-[28px] xl:text-[30px] font-medium'>{tr["shipping_information"]}</h3>
                  </div>

                  {/* Body */}
                  <div className='text-[#333333] border-y border-[#E5E7EB] grid grid-cols-4 gap-2  [&>*]:py-2 [&>*]:text-sm [&>*]:md:text-base'>

                    <p>{tr["name"]}</p>
                    <p className='col-span-3 '>{order?.additional_info["shipping"]["first_name"] + " " + order?.additional_info["shipping"]["last_name"]}</p>

                    <p>{tr["email"]}</p>
                    <p className='col-span-3 '>{order?.additional_info["shipping"]["email"]}</p>

                    <p>{tr["address"]}</p>
                    <p className='col-span-3 '>
                      {order?.additional_info["shipping"]["address_line_one"] + " " + order?.additional_info["shipping"]["address_line_two"] + ", " + order?.additional_info["shipping"]["city"] + ", " + order?.additional_info["shipping"]["state"] + ", " + order?.additional_info["shipping"]["postal_code"] + ", " + order?.additional_info["shipping"]["country"]["name"]}
                    </p>

                    <p>{tr["the_zip_code"]}</p>
                    <p className='col-span-3 '>{order?.additional_info["shipping"]["postal_code"]}</p>

                    <p>{tr["phone_number"]}</p>
                    <p className='col-span-3 '>{order?.additional_info["shipping"]["phone"]}</p>

                    <p>{tr["shipping_type"]}</p>
                    <p className='col-span-3 '>{order?.additional_info["shipping_type"]}</p>

                  </div>
                </>
                :
                <>
                  <div className='mb-8'>
                    <h3 className='text-[20px] sm:text-[28px] xl:text-[30px]  font-medium'>{tr["picker_information"]}</h3>
                  </div>

                  {/* Body */}
                  <div className='text-[#333333] border-y border-[#E5E7EB] grid grid-cols-4 gap-2  [&>*]:py-2 [&>*]:text-sm [&>*]:md:text-base'>

                    <p>{tr["name"]}</p>
                    <p className='col-span-3 '>{order?.additional_info["pickup"]["name"]}</p>

                    <p>{tr["phone_number"]}</p>
                    <p className='col-span-3 '>{order?.additional_info["pickup"]["phone"]}</p>

                  </div>
                </>
              }

            </div>
            {/*End Shipping Information */}

            {/*Start Order Status Before Xl */}
            <div className="xl:hidden">
              <OrderStatus order={order} />
            </div>
            {/*End Order Status Before Xl */}

            <OrderProductsTable
              order={order} paymentDone={paymentDone}
            />

            <OrderProductsCards
              order={order} paymentDone={paymentDone}
            />


          </div>

          {/*Start Order Status After Xl */}
          <div className="max-xl:hidden">
            <OrderStatus order={order} />
          </div>
          {/*End Order Status After Xl */}

        </div>

      </div>
    </Layout>
  );
}
