import ProductPrice from '@/components/Shared/ProductPrice'
import Pagination from '@/components/ui/Pagination'
import React from 'react'
import Button from "@/components/ui/Button";
import { Link, usePage } from '@inertiajs/react';
// start lang
import { useTranslation } from '@/contexts/TranslationContext';
import toggleNotifyMeWhenStockAvailable from '@/utils/toggleNotifyMeWhenStockAvailable';
import { BlackDecreaseIcon, BlackIncreaseIcon, GrayBellOffIcon, WhiteBellIcon, WhiteCartIcon } from '@/components/Shared/Icons';
// end lang
export default function ProductCard({ products, selectedItemsState, quantityControl }) {

  // Start language & currency
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();
  // end lang

  const { auth: { user } } = usePage().props;
  const [selectedItems, setSelectedItems] = selectedItemsState;

  const {
    cartQuantities,
    increaseQuantity,
    decreaseQuantity,
    addSelectedItemsToCart,
  } = quantityControl;

  if (!products || products.length === 0) {
    return (
      <div className='container mx-auto sm:hidden my-20'>
        <div className="text-center text-gray-500">
          {tr['no_products_found']}
        </div>
      </div>
    )
  }

  const isAuthenticated = usePage().props?.auth?.user != undefined;
  const notifyMeProducts = usePage().props?.notifyMeProducts ?? [];

  return (
    <div className=' mx-auto sm:hidden '>
      <div className='bg-[#F2F4F7] p-6 font-bold text-xl'>
        {tr['product']}
      </div>
      <div className="space-y-4">
        {products.data.map((product) => (
          <div key={product.id} className=" border-b border-[#E5E7EB] p-4">
            {/* Product Image and Basic Info */}
            <div className="flex items-start gap-4 mb-4">
              {product?.qty > 0 && (
                <div className={" self-center " + (isAuthenticated ? '' : ' hidden')}>
                  <input
                    id="checkbox"
                    type="checkbox"
                    name="selectedProducts[]"
                    className="w-4 h-4 accent-primary-500 product-checkbox cursor-pointer"
                    checked={selectedItems.includes(product.id)}
                    onChange={e => setSelectedItems(e.target.checked ? [...selectedItems, product.id] : selectedItems.filter(id => id !== product.id))}
                  />
                </div>
              )}
              <Link href={route('react.product', { product: product.id })}>
                <div className="h-20 w-20 flex-shrink-0">
                  <img
                    className="h-20 w-20 rounded object-cover"
                    src={product.image || '/public/assets/img/placeholder.jpg'}
                    alt={product.name}
                  />
                </div>
              </Link>

              <div className="flex-1 min-w-0">
                <Link href={route('react.product', { product: product.id })}>
                  <div className="text-xs font-medium text-[#9CA3AF] mb-1 uppercase">
                    {product.brand}
                  </div>
                  <div className="text-sm font-semibold text-gray-900 mb-1 line-clamp-2" title={product.name}>
                    {product.name}
                  </div>
                  <div className="text-xs text-[#9CA3AF]">
                    {tr['SKU']}: {product.sku}
                  </div>
                  <div className="text-xs text-[#9CA3AF]">
                    {tr['barcode']}: {product.barcode}
                  </div>
                </Link>
              </div>
            </div>

            {/* Product Details Grid */}
            <div className={"grid  gap-5  items-center justify-stretch " + (isAuthenticated ? 'sm:grid-cols-2' : ' ')}>
              {/* MOQ */}
              {isAuthenticated && (
                <div className="flex justify-between items-center">
                  <span className="text-sm text-[#7E7E87]">{tr['MOQ']} ({tr['pcs']})</span>
                  <span className="text-sm font-medium">{product.box_qty}</span>
                </div>
              )}

              {/* Lead Time */}
              {isAuthenticated && (
                <div className="flex justify-between items-center text-sm">
                  <span className="text-sm text-[#7E7E87]">{tr['availability']}</span>
                  <div className="text-right">
                    {product?.qty >= ((cartQuantities?.[product.id] ?? 0) * product?.box_qty) && product?.qty !== 0 ? (
                      <>
                        {tr['available_now']}{" "}
                        <span className="text-[#009B22]">
                          ({tr['stock']}: {product.qty} {tr['pcs']})
                        </span>
                      </>
                    ) : (
                      <span className="text-[#B00E0E]">
                        {tr['out_of_stock']}
                      </span>
                    )}
                  </div>
                </div>
              )}

              {/* Box Quantity */}
              {isAuthenticated && (
                <div className="flex justify-between items-center">
                  <span className="text-sm text-[#7E7E87]">{tr['box_quantity']}</span>
                  <div className="flex flex-col ">
                    <div className="flex items-center space-x-2">
                      <button
                        onClick={() => decreaseQuantity(product.id)}
                        className="w-7 h-7 rounded-full border border-[#E1E4EA] flex items-center justify-center hover:bg-gray-100 cursor-pointer">
                        <BlackDecreaseIcon />
                      </button>

                      <div className="text-sm font-medium rounded px-9 py-1 border border-[#E1E4EA] min-w-[40px] text-center">{cartQuantities?.[product.id] || 0}</div>

                      <button
                        onClick={() => increaseQuantity(product.id, product.qty, product.box_qty)}
                        className="w-7 h-7 rounded-full border border-[#E1E4EA] flex items-center justify-center hover:bg-gray-100 cursor-pointer">
                        <BlackIncreaseIcon />
                      </button>
                    </div>
                    <div className="text-xs text-center text-gray-500 mt-1">
                      ({tr['total']} {(cartQuantities?.[product.id] ?? 0) * product.box_qty} {tr['pcs']})
                    </div>
                  </div>
                </div>
              )}

              {isAuthenticated && (
                product?.qty >= (+(cartQuantities?.[product.id] || 0) * product?.box_qty) && product?.qty != 0 ? (
                  <Button
                    type="submit"
                    variant="primary"
                    fullWidth
                    className="cursor-pointer"
                    onClick={() => addSelectedItemsToCart([product.id])}
                  >
                    <div className='flex items-center gap-2'>
                      {tr['add_to_cart']}
                      <WhiteCartIcon />
                    </div>
                  </Button>
                ) : (
                  (() => {
                    const isSubscribed = notifyMeProducts.includes(product.id);
                    return (
                      <Button
                        onClick={(e) =>
                          toggleNotifyMeWhenStockAvailable(product.id)
                        }
                        variant={isSubscribed ? 'out_of_stock_unsubscribe' : 'out_of_stock'}
                        className={`flex items-center gap-2 `}
                      >
                        <span>
                          {isSubscribed
                            ? tr['unsubscribe']
                            : tr['notify_me']}
                        </span>
                        <span>
                          {isSubscribed ? (
                            <GrayBellOffIcon />
                          ) : (
                            <WhiteBellIcon />
                          )}
                        </span>
                      </Button>
                    );
                  })()
                )
              )}


              {!isAuthenticated && (
                <div className=" bg-[#F8F8F8] p-4 m-4">
                  <div className="flex items-center justify-center gap-2">
                    <div className="py-4">
                      <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.039 19.125C5.647 19.125 5.31333 18.9873 5.038 18.712C4.76267 18.4367 4.625 18.1033 4.625 17.7119V10.0381C4.625 9.64729 4.76267 9.31392 5.038 9.038C5.31333 8.76267 5.647 8.625 6.039 8.625H7.25V6.875C7.25 5.90025 7.58979 5.07337 8.26937 4.39437C8.94837 3.71479 9.77525 3.375 10.75 3.375C11.7248 3.375 12.5519 3.71479 13.2315 4.39437C13.9111 5.07396 14.2506 5.90083 14.25 6.875V8.625H15.4619C15.8527 8.625 16.1861 8.76267 16.462 9.038C16.7373 9.31333 16.875 9.647 16.875 10.039V17.7119C16.875 18.1027 16.7373 18.4361 16.462 18.712C16.1867 18.9873 15.8533 19.125 15.4619 19.125H6.039ZM6.039 18.25H15.4619C15.6188 18.25 15.7477 18.1995 15.8486 18.0986C15.9495 17.9977 16 17.8688 16 17.7119V10.0381C16 9.88121 15.9495 9.75229 15.8486 9.65138C15.7477 9.55046 15.6188 9.5 15.4619 9.5H6.03812C5.88121 9.5 5.75229 9.55046 5.65137 9.65138C5.55046 9.75229 5.5 9.8815 5.5 10.039V17.7119C5.5 17.8688 5.55046 17.9977 5.65137 18.0986C5.75229 18.1995 5.8815 18.25 6.039 18.25ZM10.75 15.1875C11.1192 15.1875 11.4302 15.0609 11.6827 14.8077C11.9359 14.5552 12.0625 14.2443 12.0625 13.875C12.0625 13.5057 11.9359 13.1948 11.6827 12.9423C11.4296 12.6897 11.1187 12.5631 10.75 12.5625C10.3813 12.5619 10.0704 12.6885 9.81725 12.9423C9.56408 13.1948 9.4375 13.5057 9.4375 13.875C9.4375 14.2443 9.56408 14.5552 9.81725 14.8077C10.0698 15.0609 10.3808 15.1875 10.75 15.1875ZM8.125 8.625H13.375V6.875C13.375 6.14583 13.1198 5.52604 12.6094 5.01562C12.099 4.50521 11.4792 4.25 10.75 4.25C10.0208 4.25 9.40104 4.50521 8.89062 5.01562C8.38021 5.52604 8.125 6.14583 8.125 6.875V8.625Z" fill="#535353" />
                      </svg>
                    </div>
                    <p>{tr['login_to_view_more_details']}</p>
                  </div>
                  <Button
                    type="submit"
                    variant="outline"
                    href={route('react.login')}
                    fullWidth
                    className="cursor-pointer rounded-md uppercase !border-[#CECECE] !bg-white"
                    onClick={() => addSelectedItemsToCart([product.id])}
                  >
                    {tr['login']}
                  </Button>
                </div>
              )}
            </div>

          </div>
        ))}
      </div>

      {/* Pagination */}
      <div className="mt-6">
        <Pagination totalPages={products.last_page} links={products.links} />
      </div>
    </div>
  )
}
