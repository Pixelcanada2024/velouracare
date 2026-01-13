import React from "react"
import { ModalCloseIcon, RedDangerIcon, YellowPendingIcon, GreenSuccessIcon } from "./Partials/ModalsIcons"

export default function Modal1({ children, onClose, status, title, description, withoutHeaderBg, modalClassName = "" }) {
  return (
    <div
      onClick={(e) => e.stopPropagation()}
      className={"relative w-full max-w-md overflow-hidden m-4 bg-white shadow-lg rounded-lg  sm:min-w-[475px] xl:min-w-[540px] pb-2 " + modalClassName}>
      <button
        onClick={onClose}
        className="absolute text-gray-500 cursor-pointer top-4 right-4 hover:text-gray-700"
      >
        <ModalCloseIcon />
      </button>

      <div
        className={`p-4 flex flex-col items-center justify-center title-container
          ${withoutHeaderBg
            ? "bg-transparent border-b-0 text-black"
            : status === "success"
              ? "bg-[#EBFBEF] border-b border-b-[#009B22] text-[#009B22]"
              : status === "rejected" || status === "error"
                ? "bg-[#FEF3F3] border-b border-b-[#E40707] text-[#E40707]"
                : status === "pending"
                  ? "bg-[#FFFDEB] border-b border-b-[#CA7B06] text-[#CA7B06]"
                  : "bg-[#F2F4F7] border-b border-b-[#CECECE] text-black"
          }`}
      >
        <div className="flex items-center justify-center gap-2  font-semibold text-[18px] sm:text-[22px] xl:text-2xl">
          <div className="">
            {status === "success" && <GreenSuccessIcon />}
            {(status === "rejected" || status === "error") && <RedDangerIcon />}
            {status === "pending" && <YellowPendingIcon />}
          </div>
          <h2 className=" capitalize">{title}</h2>
        </div>
      </div>


      <div className="py-4 text-center text-sm sm:text-lg xl:text-[20px] px-[15px] sm:px-[30px] xl:px-[40px] space-y-6">
        {description && (
          <p className="text-black ">
            {description}
          </p>
        )}

        {children && (
          <div className="children">
            {children}
          </div>
        )}
      </div>
    </div >
  )
}
