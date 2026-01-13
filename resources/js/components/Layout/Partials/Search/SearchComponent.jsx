
import { Link, router, usePage } from "@inertiajs/react";
import axios from "axios";
import React from "react";
// start lang
import { useTranslation } from "@/contexts/TranslationContext";
// end lang

export default function SearchComponent({
  modalOpen: modalOpenProp,
  setModalOpen: setModalOpenProp
}) {
  const [query, setQuery] = React.useState("");
  const [brands, setBrands] = React.useState([]);
  const [products, setProducts] = React.useState([]);
  const [loading, setLoading] = React.useState(false);
  const [modalOpen, setModalOpen] = typeof modalOpenProp === 'boolean' && typeof setModalOpenProp === 'function'
    ? [modalOpenProp, setModalOpenProp]
    : React.useState(false);

  function ajaxSearch(e) {
    const value = e.target.value.trim();
    setQuery(value);
    if (value.length > 0) {
      setLoading(true);
      axios.get(route("product-ajax-search", { search: value }), {
        headers: {
          'Accept': 'application/json',
        },
      }).then((response) => {
        setBrands(response.data.brands || []);
        setProducts(response.data.products || []);
        setLoading(false);
      }).catch(() => setLoading(false));
    } else {
      setBrands([]);
      setProducts([]);
    }
  }

  function closeModal() {
    setModalOpen(false);
    setQuery("");
    setBrands([]);
    setProducts([]);
  }

  function openModal() {
    setModalOpen(true);
    setQuery("");
    setBrands([]);
    setProducts([]);
  }

  const handleKeyDown = (e) => {
    if (e.key === "Enter") {
      e.preventDefault();
      router.get(route('react.products') + "?search=" + query);
    }
  }

  // Start language
  const [{lang, currency, tr}, _setTranslation] = useTranslation();
  // end lang

  return (
    <>

      {/* Search Button */}
      <button
        type="button"
        className="flex items-center gap-2 py-2 rounded cursor-pointer"
        onClick={openModal}
      >
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M12.9611 12.9609C13.5075 12.4145 13.9409 11.7659 14.2366 11.0521C14.5322 10.3382 14.6844 9.57313 14.6844 8.80047C14.6844 8.02781 14.5322 7.26271 14.2366 6.54886C13.9409 5.83502 13.5075 5.1864 12.9611 4.64005C12.4148 4.0937 11.7662 3.6603 11.0523 3.36462C10.3385 3.06893 9.57337 2.91675 8.80071 2.91675C8.02805 2.91675 7.26295 3.06893 6.54911 3.36462C5.83526 3.6603 5.18665 4.0937 4.64029 4.64005C3.53688 5.74346 2.91699 7.24001 2.91699 8.80047C2.91699 10.3609 3.53688 11.8575 4.64029 12.9609C5.7437 14.0643 7.24025 14.6842 8.80071 14.6842C10.3612 14.6842 11.8577 14.0643 12.9611 12.9609ZM12.9611 12.9609L16.667 16.6667" stroke="#F4F4F4" strokeWidth="1.33333" strokeLinecap="round" strokeLinejoin="round" />
        </svg>
        <span className='max-sm:hidden text-white'>{tr['search']}</span>
      </button>

      {/* Modal */}
      {modalOpen && (
        <div className="fixed inset-0 flex items-start justify-center text-black z-[500]   " >
          <div className="absolute inset-0 bg-black/90 backdrop-blur-sm z-[500]"></div>

          <div className="flex  max-sm:flex-col max-sm:items-center max-sm:justify-between justify-center items-start max-sm:h-[85%] mt-20 w-full m-5">

            <div className=" bg-white rounded-lg shadow-lg w-full max-w-2xl mx-4  z-[550] " >
              <div >
                <div className="p-2 flex justify-between items-center">
                  <input
                    type="text"
                    autoFocus
                    placeholder={tr['search_product_or_brand']}
                    value={query}
                    onChange={ajaxSearch}
                    onKeyDown={handleKeyDown}
                    className="w-full px-4 py-2 rounded-md focus:outline-none text-black bg-white"
                  />

                  <Link href={route('react.products') + "?search=" + query}>
                    <svg className="cursor-pointer" width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M22.034 22.0334C22.9628 21.1046 23.6996 20.002 24.2022 18.7884C24.7049 17.5749 24.9636 16.2742 24.9636 14.9607C24.9636 13.6472 24.7049 12.3465 24.2022 11.133C23.6996 9.91943 22.9628 8.81679 22.034 7.88799C21.1052 6.95918 20.0026 6.22242 18.789 5.71976C17.5755 5.21709 16.2748 4.95837 14.9613 4.95837C13.6478 4.95837 12.3471 5.21709 11.1336 5.71976C9.92004 6.22242 8.8174 6.95918 7.8886 7.88799C6.0128 9.76378 4.95898 12.3079 4.95898 14.9607C4.95898 17.6135 6.0128 20.1576 7.8886 22.0334C9.76439 23.9092 12.3085 24.963 14.9613 24.963C17.6141 24.963 20.1582 23.9092 22.034 22.0334ZM22.034 22.0334L28.3339 28.3333" stroke="black" strokeWidth="2.26667" strokeLinecap="round" strokeLinejoin="round" />
                    </svg>
                  </Link>

                </div>
                {loading && (
                  <div className="my-6 flex items-center justify-center">
                    <div className="w-8 h-8 border-4 border-gray-300 border-t-gray-600 rounded-full animate-spin"></div>
                    {/* <span className="ml-3 text-gray-600">{tr['searching']}</span> */}
                  </div>
                )}


                {!loading && query.length > 0 && (
                  <div className="max-h-80 overflow-y-auto">
                    {/* Brands Section */}
                    <>
                      <div className="font-semibold px-6 py-3 border-y border-black bg-[#F4F4F5] text-gray-700  uppercase ">{tr['brands']}</div>
                      {brands.length === 0 ? (
                        <div className="text-gray-400 text-sm px-6 py-3">{tr['no_brands_found']}</div>
                      ) : (
                        <ul className="divide-y divide-gray-200 ">
                          {brands.map((brand) => (
                            <li key={brand.id} className="px-6 py-3 cursor-pointer hover:bg-gray-100 font-medium">
                              <Link href={route('react.products') + "?brands[0]=" + brand.name} className="text-black hover:underline block">
                                {brand.name}
                              </Link>
                            </li>
                          ))}
                        </ul>
                      )}
                    </>

                    {/* Products Section */}
                    <>
                      <div className="font-semibold px-6 py-3 border-y border-black bg-[#F4F4F5] text-gray-700 ">{tr['products']}</div>
                      {products.length === 0 ? (
                        <div className="text-gray-400 text-sm px-6 py-3">{tr['no_products_found']}</div>
                      ) : (
                        <ul className="divide-y divide-gray-200">
                          {products.map((product) => (
                            <li key={product.id} className="px-6 py-3 cursor-pointer hover:bg-gray-100 font-medium">
                              <Link href={route('react.product', { product: product.id })} className="text-black hover:underline block">
                                {product.name}
                              </Link>
                            </li>
                          ))}
                        </ul>
                      )}
                    </>
                  </div>
                )}
              </div>
            </div>

            <button
              className="cursor-pointer right-8 text-gray-500 hover:text-gray-700 text-3xl font-bold"
              onClick={closeModal}
              aria-label="Close search modal"
              style={{ zIndex: 1100 }}
            >
              <svg width="54" height="54" viewBox="0 0 54 54" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="54" height="54" rx="27" fill="#5F5F5F" />
                <path d="M26.9997 27L36.9163 36.9167M26.9997 27L17.083 17.0834M26.9997 27L17.083 36.9167M26.9997 27L36.9163 17.0834" stroke="white" strokeWidth="3" strokeLinecap="round" strokeLinejoin="round" />
              </svg>
            </button>
          </div>

        </div>
      )}
    </>
  );
}
