import Button from '@/components/ui/Button'
import React from 'react'
// start lang
import { useTranslation } from '@/contexts/TranslationContext';
// end lang
export default function EmptyCart() {
  // Start language & currency
  const [{lang, currency, tr}, _setTranslation] = useTranslation();
  // end lang

  return (
    <div className="flex flex-1 flex-col items-center justify-center py-12 text-center min-h-110">
      <div className="mb-4">
        <svg
          width="35"
          height="34"
          viewBox="0 0 35 34"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M11.8346 31.1667C12.617 31.1667 13.2513 30.5324 13.2513 29.75C13.2513 28.9676 12.617 28.3334 11.8346 28.3334C11.0522 28.3334 10.418 28.9676 10.418 29.75C10.418 30.5324 11.0522 31.1667 11.8346 31.1667Z"
            stroke="#818181"
            strokeWidth="2.83333"
            strokeLinecap="round"
            strokeLinejoin="round"
          />
          <path
            d="M27.4167 31.1667C28.1991 31.1667 28.8333 30.5324 28.8333 29.75C28.8333 28.9676 28.1991 28.3334 27.4167 28.3334C26.6343 28.3334 26 28.9676 26 29.75C26 30.5324 26.6343 31.1667 27.4167 31.1667Z"
            stroke="#818181"
            strokeWidth="2.83333"
            strokeLinecap="round"
            strokeLinejoin="round"
          />
          <path
            d="M3.40625 2.90417H6.23958L10.0079 20.4992C10.1462 21.1436 10.5047 21.7196 11.0218 22.1281C11.539 22.5367 12.1824 22.7522 12.8412 22.7375H26.6963C27.3411 22.7365 27.9663 22.5155 28.4685 22.1111C28.9708 21.7067 29.3201 21.1431 29.4588 20.5133L31.7963 9.98751H7.75542"
            stroke="#818181"
            strokeWidth="2.83333"
            strokeLinecap="round"
            strokeLinejoin="round"
          />
        </svg>
      </div>

      <h2 className="text-lg font-medium text-gray-900 mb-2">
        {tr['you_have_no_items_in_your_cart']}
      </h2>

      <p className="text-gray-500 text-sm mb-6">
        {tr['start_adding_products_to_your_cart']}
      </p>

      <Button
        href={route('react.products')}
      >
        {tr['browse_products']}
      </Button>
    </div>
  )
}
