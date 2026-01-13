import React, { useEffect } from "react";
import ProductsTable from "./PrintPartials/ProductsTable";
import ProductsCards from "./PrintPartials/ProductsCards";
import Button from "@/components/ui/Button";
import { Head, usePage } from "@inertiajs/react";
import PrintHeader from "./PrintPartials/PrintHeader";
// start trans
import ar from "@/translation/ar"
import en from "@/translation/en"
import { useTranslation } from "@/contexts/TranslationContext";
// end trans

export default function PrintOrder({ order, info }) {
  // Start language & currency
  const langInitial = usePage().props.locale;
  const trInitial = langInitial === "ar" ? ar : en;
  const currencySymbol = usePage().props?.currency ?? "SAR";
  const currencyInitial = trInitial[currencySymbol] || currencySymbol;
  // end lang
  const [{ lang, currency, tr }, setTranslation] = useTranslation();
  const orderCurrency = tr[order.additional_info["invoice"]["currency"]] ?? "SAR";

  useEffect(() => {
    setTranslation({
      lang: langInitial,
      tr: trInitial,
      currency: currencyInitial
    });
  }, [currencyInitial]);

  useEffect(() => {
    window.print();
  }, []);

  return (
    <div className=" mx-auto  ">
      <Head><title>{tr["print_order_invoice"]}</title></Head>
      <style jsx>{`@media print {
        body {
          -webkit-print-color-adjust: exact !important; /* For Safari/Chrome */
          print-color-adjust: exact !important; /* For Firefox */
        }
      }`}</style>

      <div className="" dir={lang === "ar" ? "rtl" : "ltr"}>

        {/* Header */}
        <PrintHeader info={info} />


        <div className="max-w-4xl mx-auto px-4">

          {/* Order Info */}
          <div className="md:py-6 py-4">
            <div className='flex justify-between items-center  mb-4 '>
              <h2 className="font-bold mb-2 text-lg xl:text-2xl">{tr["invoice_number"]} : <span dir="ltr">#{order.code}</span> </h2>
              <Button
                onClick={() => window.print()}
                size="sm"
                className='print:hidden'
              >
                {tr["print_invoice"]}
              </Button>
            </div>
            <div className="space-y-2 max-sm:text-sm text-[15px]">
              <div><span className='text-[#818181] font-medium'>{tr["date"]}: </span>{new Date(order.date * 1000).toISOString().split("T")[0]}</div>
              <div><span className='text-[#818181] font-medium'>{tr["order_number"]}: </span><span className='underline text-[#006DFF]' dir="ltr">#{order.code}</span></div>
              <div><span className='text-[#818181] font-medium'>{tr["shipping_type"]}: </span>{order.shipping_type}</div>
              <div><span className='text-[#818181] font-medium'>{tr["total_payable_amount"]}: </span><span className='font-bold' dir='ltr'>{orderCurrency}{order.grand_total}</span></div>
            </div>
          </div>
          <div className='flex flex-col md:flex-row items-center gap-8 max-sm:text-sm text-[15px]'>
            {order?.shipping_type === "delivery"
              ?
              <div className='p-4 border border-[#E5E7EB] rounded-md w-full min-h-45 sm:min-h-50'>
                <h3 className='text-[#666666] font-bold mb-3'>{tr["shipped_to"]} :</h3>
                <div>
                  <p>{order?.additional_info["shipping"]["first_name"] + " " + order?.additional_info["shipping"]["last_name"]} </p>
                  <p>{order?.additional_info["shipping"]["email"]}</p>
                  <p>{order?.additional_info["shipping"]["phone"]}</p>
                  <p>
                    {order?.additional_info["shipping"]["address_line_one"] + " " + order?.additional_info["shipping"]["address_line_two"] + ", " + order?.additional_info["shipping"]["city"] + ", " + order?.additional_info["shipping"]["state"] + ", " + order?.additional_info["shipping"]["postal_code"] + ", " + order?.additional_info["shipping"]["country"]["name"]}
                  </p>
                </div>
              </div>
              :
              <div className='p-4 border border-[#E5E7EB] rounded-md w-full min-h-45 sm:min-h-50'>
                <h3 className='text-[#666666] font-bold mb-3'>{tr["picker_information"]}</h3>
                <div>
                  <p>{order?.additional_info["pickup"]["name"]} </p>
                  <p className='color-[transparent]'>---</p>
                  <p>{order?.additional_info["pickup"]["phone"]} </p>
                  <p className='color-[transparent]'>---</p>
                </div>
              </div>

            }
            <div className='p-4 border border-[#E5E7EB] rounded-md w-full min-h-45 sm:min-h-50'>
              <h3 className='text-[#666666] font-bold mb-3'>{tr["billed_to"]} :</h3>
              <div>
                <p>{order?.additional_info["billing"]["first_name"] + " " + order?.additional_info["billing"]["last_name"]} </p>
                <p>{order?.additional_info["billing"]["email"]}</p>
                <p>{order?.additional_info["billing"]["phone"]}</p>
                <p> {order?.additional_info["billing"]["address_line_one"] + " " + order?.additional_info["billing"]["address_line_two"] + ", " + order?.additional_info["billing"]["city"] + ", " + order?.additional_info["billing"]["state"] + ", " + order?.additional_info["billing"]["postal_code"] + ", " + order?.additional_info["billing"]["country"]["name"]} </p>
              </div>
            </div>
          </div>


          <div className="md:py-6 py-4">
            <ProductsTable
              order={order}
            />

            <ProductsCards
              order={order}
            />
          </div>

        </div>

        {/* Footer */}
        <div className='border-t border-[#E5E7EB] pt-3 pb-8 md:py-6 mt-8'>
          <div className="px-5 max-w-4xl mx-auto">
            {tr["print_order_contact_us_note"]} <a href={`mailto:${info.email}`} className='text-[#006DFF]'>{info.email}</a>
          </div>
        </div>
      </div>

    </div>
  );
}
