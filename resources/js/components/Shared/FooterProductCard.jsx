import { Link, usePage } from "@inertiajs/react";
import ProductPrice from "./ProductPrice";
import { useTranslation } from "@/contexts/TranslationContext";

export default function FooterProductCard({ product }) {

  const { auth: { user } } = usePage().props;

  // Start language
  const [{lang, currency, tr}, _setTranslation] = useTranslation();
  // end lang

  return (
    <div className="w-full h-30 max-w-sm bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 flex max-h-[132px]">
      {/* Image Section - Left Side */}

      <Link href={route('react.product', { product: product.product_id })} className="w-1/3  flex items-center justify-center ">
        {product.image && (
          <img
            src={product.image}
            alt={product.name}
            className="h-full w-full object-cover p-2"
          />
        )}
      </Link>

      {/* Product Info - Right Side */}
      <div className="w-2/3 ltr:pl-1 ltr:pr-3 rtl:pl-1 rtl:pr-3 py-3 flex flex-col justify-between" >

        <p className="text-[13px] sm:text-[14px] text-[#9CA3C1]">{product.brand}</p>

        <Link href={route('react.product', { product: product.product_id })}>
          <h3 className="text-md font-semibold text-[#222222] line-clamp-2 text-sm sm:text-base" title={product.name} >{product.name}</h3>
        </Link>

        {user && (
          <div className="flex items-center text-[13px] sm:text-[14px]">
            <div className="flex items-end gap-2">
              <div className="font-inter flex gap-2 ">
                <span> MOQ </span>
                <span> 1 {tr['box']}  ({product.box_qty} {tr['pcs']}) </span>
              </div>
            </div>
          </div>
        )}
      </div>

    </div>
  );
}
