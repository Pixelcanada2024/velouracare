import React from "react";
import { useTranslation } from "@/contexts/TranslationContext";

export default function OrderStatus({ order }) {
  const [{ lang, tr }] = useTranslation();

  // Define status order
  const normalStatusSteps = [
    "processing",
    "payment",
    "confirmed",
    "picked_up",
    "on_the_way",
    "delivered",
  ];

  const cancelledStatusSteps = ["processing", "cancelled"];

  // Define colors
  const statusColors = {
    processing: "#FFE5B1",
    payment: "#F3B8FF",
    confirmed: "#CFFFF9",
    picked_up: "#FFBCDE",
    on_the_way: "#9AD7FF",
    delivered: "#72F98F",
    cancelled: "#FF7A7A",
  };

  const statusBorderColors = {
    processing: "#987736",
    payment: "#863C96",
    confirmed: "#3C9388",
    picked_up: "#8E305F",
    on_the_way: "#2D6388",
    delivered: "#2A813D",
    cancelled: "#7D2525",
  };

  const currentStatus = order.delivery_status;
  const statusSteps =
    currentStatus === "cancelled" ? cancelledStatusSteps : normalStatusSteps;

  // Format date function
  const formatDateTime = (datetime) => {
    if (!datetime) return "";

    const date = new Date(datetime);
    const day = String(date.getDate()).padStart(2, "0");
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const year = date.getFullYear();

    let hours = date.getHours();
    const minutes = String(date.getMinutes()).padStart(2, "0");
    const ampm = hours >= 12 ? "PM" : "AM";
    const ampmAr = hours >= 12 ? "مساءً" : "صباحاً";

    hours = hours % 12;
    hours = hours ? hours : 12; // 0 should be 12
    const hoursStr = String(hours).padStart(2, "0");

    if (lang === "ar") {
      return `${day}/${month}/${year} - ${hoursStr}:${minutes} ${ampmAr}`;
    }

    return `${day}/${month}/${year} - ${hoursStr}:${minutes} ${ampm}`;
  };

  return (
    <div className="border border-[#E5E7EB] rounded-md h-fit">
      <h2 className="text-[22px] sm:text-[28px] font-medium mb-8 p-6 border-b border-[#E5E7EB]">
        {tr["order_status"]}
      </h2>

      {/* Timeline */}
      <div className="relative px-6 pb-6">
        {statusSteps.map((status, index) => {
          const isLast = index === statusSteps.length - 1;
          const currentIndex = statusSteps.indexOf(currentStatus);
          const isCompleted = index < currentIndex;
          const isActive = index === currentIndex;

          // Get status time from order
          const statusTime = order.delivery_status_time?.[status];

          return (
            <div key={status} className="relative flex items-start">
              {/* Circle */}
              <div className="relative flex flex-col items-center">
                {isCompleted ? (
                  <div className="w-8 h-8 rounded-full flex items-center justify-center relative z-10 border-2 border-[#7B7B7B] bg-[#FAFAFA]">
                    <svg
                      width="20"
                      height="20"
                      viewBox="0 0 20 20"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        d="M7.95832 12.625L15.0208 5.5625C15.1875 5.39583 15.3819 5.3125 15.6042 5.3125C15.8264 5.3125 16.0208 5.39583 16.1875 5.5625C16.3542 5.72917 16.4375 5.92722 16.4375 6.15667C16.4375 6.38611 16.3542 6.58389 16.1875 6.75L8.54165 14.4167C8.37499 14.5833 8.18054 14.6667 7.95832 14.6667C7.7361 14.6667 7.54165 14.5833 7.37499 14.4167L3.79165 10.8333C3.62499 10.6667 3.54499 10.4689 3.55165 10.24C3.55832 10.0111 3.64527 9.81306 3.81249 9.64583C3.97971 9.47861 4.17777 9.39528 4.40665 9.39583C4.63554 9.39639 4.83332 9.47972 4.99999 9.64583L7.95832 12.625Z"
                        fill="#7B7B7B"
                      />
                    </svg>
                  </div>
                ) : isActive ? (
                  <div
                    className="w-8 h-8 rounded-full flex items-center justify-center relative z-10 border-2 border-dashed bg-white"
                    style={{ borderColor: statusBorderColors[status] }}
                  >
                    <div
                      className="w-[18px] h-[18px] rounded-full border"
                      style={{
                        backgroundColor: statusColors[status],
                        borderColor: statusBorderColors[status],
                      }}
                    ></div>
                  </div>
                ) : (
                  <div
                    className="w-8 h-8 rounded-full flex items-center justify-center relative z-10"
                    style={{ backgroundColor: statusColors[status] }}
                  >
                    <div
                      className="w-[18px] h-[18px] rounded-full border"
                      style={{
                        backgroundColor: statusColors[status],
                        borderColor: statusBorderColors[status],
                      }}
                    ></div>
                  </div>
                )}

                {/* Line */}
                {!isLast && (
                  <div className="w-[4px] bg-gray-200 h-[39px] xl:h-[64px] my-[8px]" />
                )}
              </div>

              {/* Text */}
              <div className="mx-4 pb-[39px] xl:pb-[56px] -mt-[1.5px]">
                <p
                  className={`capitalize mb-1 text-sm ${isActive ? "font-bold" : "font-medium"
                    }`}
                  style={{
                    color: isActive ? statusBorderColors[status] : "#000000",
                  }}
                >
                  {tr[status]}
                </p>
                <p className="text-xs text-[#7B7B7B]">
                  {status == 'processing' ? formatDateTime(order.created_at) : (statusTime ? formatDateTime(statusTime) : "")}
                </p>
              </div>
            </div>
          );
        })}
      </div>
    </div>
  );
}
