import Layout from '@/components/Layout/Layout'
import React, { useEffect } from 'react'
import { Link, usePage } from '@inertiajs/react'
import Button from '@/components/ui/Button'
import RelatedProductsSwiper from './Partials/RelatedProductsSwiper';
import { useCart } from '@/contexts/CartItemsContext';

// start lang
import { useTranslation } from '@/contexts/TranslationContext';
import toggleNotifyMeWhenStockAvailable from '@/utils/toggleNotifyMeWhenStockAvailable';
import { increaseQuantityFunction } from '@/utils/cartSharedFunctions';
import { BlackDecreaseIcon, BlackIncreaseIcon, GrayBellOffIcon, WhiteBellIcon } from '@/components/Shared/Icons';
// end lang

export default function SingleProduct({ product = {}, frequentlyBoughtProducts = {} }) {

  // Start language & currency
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();
  // end lang

  const [showAddedSuccessMsg, setShowAddedSuccessMsg] = React.useState(false);

  //  get user cart items
  const { cartData, updateCart } = useCart();
  const user = usePage().props?.auth?.user ?? null;
  const userId = user?.id ?? null;
  const cartItems = cartData?.find(item => item.user_id === userId)?.cart_items ?? {};

  // add cart items into state for easily dynamic update
  const [cartQuantities, setCartQuantities] = React.useState({});

  useEffect(() => {
    setCartQuantities(cartItems);
  }, [Object.keys(cartItems)?.length]);

  const increaseQuantity = (id, qty, box_qty) => {
    increaseQuantityFunction(id, qty, box_qty, cartQuantities, setCartQuantities);
  }

  const decreaseQuantity = (id) => {
    const newCartQuantities = { ...cartQuantities };
    if (!newCartQuantities?.[id] || newCartQuantities?.[id] < 1) return;
    newCartQuantities[id] = (newCartQuantities?.[id] ?? 0) - 1;
    setCartQuantities(newCartQuantities);
    // updateCart({ user_id: userId, cartItems: newCartQuantities });
  }

  const addSelectedItemsToCart = (ids = []) => {
    const filteredEntries = Object.entries(cartQuantities).filter(([id, qty]) => {
      return ids.includes(+id) && qty > 0
    });
    const selectedCartItems = Object.fromEntries(filteredEntries);
    const newCartQuantities = { ...cartItems, ...selectedCartItems };
    updateCart({ user_id: userId, cartItems: newCartQuantities });
  }

  const notifyMeProducts = usePage().props?.notifyMeProducts ?? [];

  return (
    <Layout
      pageTitle={product.name}
      breadcrumbs={[
        { label: tr["home"], url: route('react.home') },
        { label: product.name, url: route('react.product', product.id) },
      ]}
    >
      <div className='container mx-auto my-[40px] sm:my-[56px]' style={{ fontFamily: 'Snarf' }}>

        <div className="grid grid-cols-1 lg:grid-cols-5 gap-10 xl:gap-12 ">
          {/* Left Product Image */}
          <div className="flex items-center  self-center lg:col-span-2 justify-center bg-white border border-[#CECECE] rounded-lg">
            <img
              src={product.image}
              alt={product.name}
              className="h-full w-full object-contain aspect-square"
            />
          </div>

          {/* Right Product Details */}
          <div className='flex flex-col lg:col-span-3'>

            <div className='space-y-4 flex-1 flex flex-col justify-start '>
              {/* Header */}
              <div>
                <p className="text-[#9CA3C1] font-medium underline underline-offset-4">
                  <Link href={route('react.products') + `?brands[]=${product.brand}`}>{product.brand}</Link></p>
                <h1 className="product-name text-[22px] sm:text-[30px]  font-semibold mb-4 mt-2">{product.name}</h1>
              </div>
              {/* info */}

              <div className="flex justify-between gap-2 items-center border-b border-[#E5E7EB] pb-2 mb-4  text-sm sm:text-base">
                <div className=''>{tr['box_quantity']}</div>
                {!!user && <div className='text-right flex items-center justify-end gap-4'>
                  <button
                    onClick={() => decreaseQuantity(product.id)}
                    className="cursor-pointer w-5 h-5 lg:w-8 lg:h-8 rounded-full border-2 border-[#E1E4EA] flex items-center justify-center hover:bg-gray-100">
                    <BlackDecreaseIcon />
                  </button>

                  <div className=" font-medium rounded-lg px-5 lg:px-8 py-1 border-2 border-[#E1E4EA]">{`${cartQuantities?.[product.id] || 0} ${tr['boxes']} (${(cartQuantities?.[product.id] || 0) * product.box_qty} ${tr['pcs']})`}</div>

                  <button
                    onClick={() => increaseQuantity(product.id, product.qty, product.box_qty)}
                    className="cursor-pointer w-5 h-5 lg:w-8 lg:h-8 rounded-full border-2 border-[#E1E4EA] flex items-center justify-center hover:bg-gray-100"
                  >
                    <BlackIncreaseIcon />
                  </button>
                </div>}
              </div>

              <div className="flex justify-between gap-4 items-center border-b border-[#E5E7EB] pb-4 mb-8 text-sm sm:text-base">
                <div>{tr['availability']}</div>
                {!!user && <div className='text-right'>
                  {product?.qty >= ((cartQuantities?.[product.id] ?? 0) * product?.box_qty) && product?.qty != 0
                    ? <>{tr['available_now']} <span className='text-[#009B22]'>({tr['stock']}: {product.qty} {tr['pcs']})</span></>
                    : <span className='text-[#B00E0E]'>{tr['out_of_stock']}</span>}
                </div>}
              </div>



              {/* Add to Cart */}
              <div className=' '>
                <div>
                  {!!user ? (
                    product?.qty > 0 ? (
                      <Button
                        fullWidth
                        onClick={() => addSelectedItemsToCart([product.id])}
                        className="sm:text-[20px]"
                      >
                        {tr['add_to_cart']}
                      </Button>
                    ) : (
                      (() => {
                        const isSubscribed = notifyMeProducts.includes(product.id);
                        return (
                          <Button
                            onClick={(e) =>
                              toggleNotifyMeWhenStockAvailable(product.id)
                            }
                            fullWidth
                            variant={isSubscribed ? 'out_of_stock_unsubscribe' : 'out_of_stock'}
                            className={`flex items-center gap-2 sm:text-[20px]`}
                          >
                            <span>
                              {isSubscribed
                                ? tr['unsubscribe']
                                : tr['notify_me_when_available']}
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
                  ) : (
                    <Button fullWidth href={route('react.login')} className="sm:text-[20px]">
                      {tr['login_for_more_details']}
                    </Button>
                  )}
                </div>

              </div>
            </div>

          </div>
        </div>

        {/* Product Info Table */}
        <div className="mt-16">
          <h2 className="text-[20px]  sm:text-[24px] font-bold mb-4">{tr['product_information']}</h2>
          <div className="overflow-x-auto">

            <table className="min-w-full max-2xl:hidden rounded-lg overflow-hidden text-sm sm:text-base">
              <tbody>
                <tr className='border border-[#E5E7EB]  '>
                  <td className="p-5 min-w-50 font-medium bg-[#F2F4F7]">{tr['MOQ']}</td>
                  <td className="p-5 min-w-50">{product.box_qty} {tr['pcs']} ({tr['no_moq_required']})</td>
                  <td className="p-5 min-w-50 font-medium bg-[#F2F4F7]">{tr['barcode']}</td>
                  <td className="p-5 min-w-50">{product.barcode}</td>
                  <td className="p-5 min-w-50 font-medium bg-[#F2F4F7]">{tr['SKU']}</td>
                  <td className="p-5 min-w-50">{product.sku}</td>
                </tr>

                <tr className="border border-[#E5E7EB]">
                  <td className="p-5  font-medium bg-[#F2F4F7]">{tr['weight']}</td>
                  <td className="p-5 ">{product.weight} {tr['kg']}</td>
                  <td className="p-5  font-medium bg-[#F2F4F7]">{tr['available_documents']}</td>
                  <td className="p-5 ">{product.available_document}</td>
                  <td className="p-5  font-medium bg-[#F2F4F7]">{tr['made_in']}</td>
                  <td className="p-5 ">{product.made_in_country}</td>
                </tr>

              </tbody>
            </table>

            <table className="min-w-full 2xl:hidden rounded-lg overflow-hidden text-sm sm:text-base">
              <tbody>
                <tr className='border border-[#E5E7EB]'>
                  <td className="p-5  font-medium bg-[#F2F4F7]">{tr['MOQ']}</td>
                  <td className="p-5 ">{product.box_qty} {tr['pcs']} ({tr['no_moq_required']})</td>
                </tr>
                <tr className='border border-[#E5E7EB]'>
                  <td className="p-5  font-medium bg-[#F2F4F7]">{tr['weight']}</td>
                  <td className="p-5 ">{product.weight} kg</td>
                </tr>
                <tr className='border border-[#E5E7EB]'>
                  <td className="p-5  font-medium bg-[#F2F4F7]">{tr['available_documents']}</td>
                  <td className="p-5 ">{product.available_document}</td>
                </tr>
                <tr className='border border-[#E5E7EB]'>
                  <td className="p-5  font-medium bg-[#F2F4F7]">{tr['barcode']}</td>
                  <td className="p-5 ">{product.barcode}</td>
                </tr>
                <tr className='border border-[#E5E7EB]'>
                  <td className="p-5  font-medium bg-[#F2F4F7]">{tr['SKU']}</td>
                  <td className="p-5 ">{product.sku}</td>
                </tr>
                <tr className='border border-[#E5E7EB]'>
                  <td className="p-5  font-medium bg-[#F2F4F7]">{tr['made_in']}</td>
                  <td className="p-5 ">{product.made_in_country}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        {frequentlyBoughtProducts.length > 0 && (
          <RelatedProductsSwiper frequently_bought_products={frequentlyBoughtProducts} />
        )}

      </div>

    </Layout>
  )
}
