import React from "react"
import HtmlStringRenderer from "./../HtmlStringRenderer";
import { useTranslation } from "@/contexts/TranslationContext";
import { ModalCloseIcon } from "./Partials/ModalsIcons";

export default function Modal2({ children, onClose, title, description, modalClassName = "" }) {
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();

  return (
    <div
      onClick={(e) => e.stopPropagation()}
      className={"relative w-full max-w-md  m-4 bg-white shadow-lg rounded-xl lg:min-w-lg overflow-hidden max-sm:max-w-76" + modalClassName}>
      <button
        onClick={onClose}
        className="absolute text-gray-500 cursor-pointer top-4 right-4 hover:text-gray-700"
      >
        <ModalCloseIcon />
      </button>

      <div className={" bg-[#F7FBF5] gap-2 flex sm:gap-5  items-center justify-center px-6 py-5 h-[78px] " + (title != "Already Subscribed!" && "text-green-500")}>
        { title != "Already Subscribed!" && <svg width="27" height="27" viewBox="0 0 27 27" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M7.5 14L11 18L18.5 9.75M26 13.5C26 20.4037 20.4037 26 13.5 26C6.59625 26 1 20.4037 1 13.5C1 6.59625 6.59625 1 13.5 1C20.4037 1 26 6.59625 26 13.5Z" stroke="currentColor" strokeWidth="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>}
        <h3 className='text-base sm:text-2xl text-center font-semibold pe-5'>{ title != "Already Subscribed!" ? title : tr["oops_already_subscribed"]}</h3>
      </div>

      {description && (
        <div className="p-6 bg-white rounded-lg text-center">
          <div className="text-black text-sm sm:text-xl text-center">
            <HtmlStringRenderer htmlString={description} />
          </div>
        </div>
      )}

      {children && (
        <div className="p-6">
          {children}
        </div>
      )}
    </div>
  )
}
