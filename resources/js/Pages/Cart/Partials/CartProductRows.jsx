import ProductPrice from "@/components/Shared/ProductPrice";
import React from "react";
// start lang
import { Link } from "@inertiajs/react";
import { useTranslation } from "@/contexts/TranslationContext";
// end lang


function QuantityControl({ product, cartQuantities, increaseQuantity, decreaseQuantity , tr , containerClassName}){
  return (
    <div className={"flex flex-col items-stretch gap-1 text-sm lg:text-base " + containerClassName}>
      <div className="flex justify-between items-center ">
        <button className="cursor-pointer" onClick={() => decreaseQuantity(product.id)}>
          <svg
            className="w-[28px] h-[28px]"
            viewBox="0 0 32 33"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <rect
              x="0.571429"
              y="1.07143"
              width="30.8571"
              height="30.8571"
              rx="15.4286"
              fill="white"
            />
            <rect
              x="0.571429"
              y="1.07143"
              width="30.8571"
              height="30.8571"
              rx="15.4286"
              stroke="#E5E7EB"
              strokeWidth="1.14286"
            />
            <path
              d="M9.14453 16.5H22.8588"
              stroke="black"
              strokeWidth="1.5"
              strokeLinecap="round"
              strokeLinejoin="round"
            />
          </svg>
        </button>
        <div className="mx-2 text-center max-sm:flex-1">
          {`${cartQuantities?.[product.id] || 0} ${tr["boxes"]}`} <br className="max-sm:hidden lg:hidden" /> {`( ${(cartQuantities?.[product.id] || 0) * product.box_qty} ${tr["pcs"]} )`}
        </div>
        <button className="cursor-pointer" onClick={() => increaseQuantity(product.id, product.qty, product.box_qty)}>
          <svg
            className="w-[28px] h-[28px]"
            viewBox="0 0 32 33"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <rect
              x="0.571429"
              y="1.07143"
              width="30.8571"
              height="30.8571"
              rx="15.4286"
              fill="white"
            />
            <rect
              x="0.571429"
              y="1.07143"
              width="30.8571"
              height="30.8571"
              rx="15.4286"
              stroke="#E5E7EB"
              strokeWidth="1.14286"
            />
            <path
              d="M22.8549 16.5001H15.9978M15.9978 16.5001H9.14062M15.9978 16.5001V9.64294M15.9978 16.5001V23.3572"
              stroke="black"
              strokeWidth="1.5"
              strokeLinecap="round"
              strokeLinejoin="round"
            />
          </svg>
        </button>
      </div>
      <div className="text-center">
        {product?.qty >= ((cartQuantities?.[product.id] ?? 0) * product?.box_qty) && product?.qty != 0
          ? <>{tr["available_now"]} <br className="max-sm:hidden lg:hidden" /> <span className='text-[#009B22]'>({tr["stock"]}: {product.qty} {tr["pcs"]})</span></>
          : <span className='text-[#B00E0E]'>{tr["out_of_stock"]}</span>}
      </div>
    </div>
  );
}

export default function CartProductRows({ brand, products, quantityControl, selectControl }) {

  // Start language & currency
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();
  // end lang

  const { cartQuantities, increaseQuantity, decreaseQuantity, removeItem } = quantityControl;
  const { selectedItems, selectedBrands, toggleSelectBrandItems, toggleSelectProductItem } = selectControl;

  return (
    <div className="bg-white  ">
      <div className="bg-[#F2F4F7] py-3 px-4 font-medium  rounded-t-lg">
        <div className="flex items-center gap-5">
          <input
            id="checkbox"
            type="checkbox"
            className="w-4 h-4 accent-primary-500"
            onChange={toggleSelectBrandItems}
            data-brand-name={brand}
            checked={selectedBrands.includes(brand)}
          />
          <span className="text-[#9CA3AF]">{brand}</span>
        </div>
      </div>

      <div className="sm:divide-y divide-[#E5E7EB] sm:border-b border-[#E5E7EB] py-2">
        {products.map((product) => (
          <div key={product.id} >
            {cartQuantities[product.id] > 0 &&
            <div className="flex max-sm:flex-col max-sm:mb-5 relative flex-1">
              {/* close btn */}
              <div onClick={() => removeItem(product.id)} className="cursor-pointer absolute top-2 ltr:right-2 rtl:left-2 p-1 hover:bg-gray-200 rounded-full">
                <svg
                  width="15"
                  height="15"
                  viewBox="0 0 20 20"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M10.0013 9.99996L15.8346 15.8333M10.0013 9.99996L4.16797 4.16663M10.0013 9.99996L4.16797 15.8333M10.0013 9.99996L15.8346 4.16663"
                    stroke="black"
                    strokeWidth="2"
                    strokeLinecap="round"
                    strokeLinejoin="round"
                  />
                </svg>
              </div>

              {/* Product Info Row */}
              <div className="flex gap-5 justify-items-stretch items-start px-4 flex-1 py-2">
                <div className="sm:self-center">
                  <input
                    id="checkbox"
                    type="checkbox"
                    checked={selectedItems.includes(product.id)}
                    onChange={toggleSelectProductItem}
                    data-product-id={product.id}
                    className="w-4 h-4 accent-primary-500 mt-2"
                  />
                </div>
                <div className="flex sm:gap-4 items-stretch justify-items-stretch sm:min-h-36 sm:max-h-40 flex-1">
                  <a href={product.image} target="_blank" className="grid place-content-center sm:h-full">
                    <img src={product.image} alt="product image" className="w-31 max-w-31 min-w-31 max-sm:hidden sm:min-h-36 sm:max-h-40 object-contain bg-gray-50" />
                  </a>
                  <div className="flex flex-col gap-1 justify-center flex-1">
                    <h5 className="text-sm text-[#9CA3AF]  max-sm:hidden">{product.brand}</h5>
                    <h4 className="font-medium text-sm sm:text-base lg:text-lg leading-tight mb-1 max-sm:pe-5" title={product.name}>
                      <Link
                        href={route("react.product", { product: product?.id })}
                        className="line-clamp-2 max-w-75 xl:max-w-120"
                      >
                        {product.name}
                      </Link>
                    </h4>
                    <h5 className="text-sm text-[#9CA3AF] max-sm:hidden"> {tr["SKU"]} : {product.sku}</h5>
                    <h5 className="text-sm text-[#9CA3AF] max-sm:hidden"> {tr["barcode"]} : {product.barcode}</h5>
                    <p className="text-sm max-sm:hidden"><span className="text-[#565656]">{tr["MOQ"]} &nbsp;</span> 1 {tr["box"]} ({product.box_qty}{tr["pcs"]})</p>
                    <hr className="border-gray-200 sm:hidden" />
                    {/* Quantity and Price Row */}
                    <QuantityControl { ...{ product, cartQuantities, increaseQuantity, decreaseQuantity, tr }} containerClassName=" sm:hidden " />
                    <hr className="border-gray-200 sm:hidden" />
                  </div>
                </div>
                <QuantityControl { ...{ product, cartQuantities, increaseQuantity, decreaseQuantity, tr }} containerClassName=" max-sm:hidden self-center !min-w-48 " />
              </div>
            </div>}
          </div>
        ))}
      </div>
    </div>
  );
}
