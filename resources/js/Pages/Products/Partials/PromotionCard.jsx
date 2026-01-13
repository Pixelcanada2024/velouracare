import React from "react";

// start lang
import { useTranslation } from "@/contexts/TranslationContext";
// end lang

export default function PromotionCard({ promotion = {} }) {
  // Start language
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();
  // end lang
  return (
    <div className="container mx-auto">
      <div
        key={promotion?.id}
        className="grid grid-cols-1 xl:grid-cols-2 gap-8"
      >
        <div className="flex flex-col gap-3 ">
          <img
            src={promotion?.mobile_banner}
            alt="Mobile Banner"
            className="object-contain w-full h-full rounded-xl shadow "
          />
        </div>

        <div className="space-y-5">
          <div className="text-[#818181] flex items-center justify-between">
            <p className=" font-semibold">{tr['promotion']}</p>
            <div className="flex items-center text-sm gap-1">
              <span>{promotion?.start_at}</span>
              <span>-</span>
              <span>{promotion?.end_at}</span>
            </div>
          </div>
          <h3 className="text-2xl font-semibold">{promotion?.title}</h3>

          <p className="text-gray-600">{promotion?.description}</p>

        </div>


      </div>
    </div>
  );
}
