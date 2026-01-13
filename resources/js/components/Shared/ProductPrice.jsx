import React from 'react';
// start lang
import ar from '@/translation/ar'
import en from '@/translation/en'
import { usePage } from '@inertiajs/react';
// end lang

export default function ProductPrice({ product, isPriceBreakLine = false }) {

  // Start language & currency
  const lang = usePage().props.locale;
  const tr = lang === 'ar' ? ar : en;
  const currencySymbol = usePage().props?.currency ?? 'SAR';
  const currency = tr[currencySymbol] || currencySymbol;
  // end lang

  const hasDiscount = product?.originalPrice && product.originalPrice !== product.price;

  return (
    <div className="">
      {hasDiscount && (
        <>
          <span className="mr-2 text-gray-500 line-through text-xs" dir='ltr'>
            {currency}{product.originalPrice}
          </span>
          {isPriceBreakLine && (<br />)}
        </>
      )}

      <span
        className={` ${hasDiscount ? 'text-[#009623]' : 'text-primary-500'
          }`}
      >
        {product?.price ?
        <span dir='ltr'>
          {currency}{product.price}
        </span>
        : ""}
      </span>
    </div>
  );
}
