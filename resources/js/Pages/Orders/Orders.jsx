import React, { useEffect, useState } from "react"
import Layout from "@/components/Layout/Layout";
import { usePage } from "@inertiajs/react";
import Pagination from "@/components/ui/Pagination"
import OrdersTable from "./Partials/OrdersPartials/OrdersTable";
import OrdersCards from "./Partials/OrdersPartials/OrdersCards";
import { useTranslation } from "@/contexts/TranslationContext";
import Modal from "@/components/ui/Modal";

export default function Orders({ orders }) {
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();
  const [isQuotationModalOpen, setIsQuotationModalOpen] = useState(false);
  const [orderCode, setOrderCode] = useState("0000000");

  const {
    auth: { user },
  } = usePage().props;

  useEffect(() => {
    const orderSuccess = localStorage.getItem("order_success");
    const orderCode = localStorage.getItem("order_code");
    if (!orderSuccess || !orderCode) return;

    setOrderCode(orderCode);
    setIsQuotationModalOpen(true);
  }, []);

  const onClose = () => {
    setIsQuotationModalOpen(false);
    localStorage.removeItem("order_success");
    localStorage.removeItem("order_code");
  }

  return (
    <Layout
      pageTitle={tr["orders_list"]}
      breadcrumbs={[
        { label: tr["home"], url: route("react.home") },
        { label: tr["orders"], url: route("react.dashboard.orders") },
      ]}
    >
      <div className="container mx-auto py-8">

        {/* Orders Table On Tablet - Website */}
        <OrdersTable orders={orders} />

        {/* Orders Cards On Mobile */}
        <OrdersCards orders={orders} />

        <Pagination links={orders.links} totalPages={orders.last_page} />

      </div>

      <Modal
        isOpen={isQuotationModalOpen}
        onClose={onClose}
        title={tr["quotation_request_submitted"]}
        status={'success'}
      >
        <p>{tr["quotation_request_submitted_line_1"]}
          <span className="font-bold  ">(ID: {orderCode})</span>
          {tr["quotation_request_submitted_line_2"]}
        </p>
      </Modal>
    </Layout>
  )
}
