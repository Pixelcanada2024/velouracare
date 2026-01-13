import Layout from "@/components/Layout/Layout";
import Button from "@/components/ui/Button";
import CartProductRows from "./Partials/CartProductRows";
import React, { useState, useEffect } from "react";
import axios from "axios";
import { useCart } from "@/contexts/CartItemsContext";
import { Link, router, usePage } from "@inertiajs/react";
import Modal from "@/components/ui/Modal";
import EmptyCart from "./Partials/EmptyCart";
import DragFileInput from "@/components/ui/DragFileInput";
import { useTranslation } from "@/contexts/TranslationContext";
import { increaseQuantityFunction } from "@/utils/cartSharedFunctions";

export default function Cart({ countries }) {
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();

  const { csrfToken } = usePage().props;

  const [isUploadModalOpen, setIsUploadModalOpen] = React.useState(false);
  const [cartUpdateFile, setCartUpdateFile] = React.useState(null);

  const [selectedItems, setSelectedItems] = React.useState([]);
  const [selectedBrands, setSelectedBrands] = React.useState([]);

  const [isLoading, setIsLoading] = React.useState(false);
  const [uploadSuccessMsg, setUploadSuccessMsg] = React.useState(null);
  const [isUploading, setIsUploading] = useState(false);
  const [uploadError, setUploadError] = useState(null);

  const [productItems, setProductItems] = React.useState([]);

  //  get user cart items
  const { cartData, updateCart } = useCart();
  const userId = usePage().props?.auth?.user?.id ?? null;
  const cartItems = cartData?.find(item => item.user_id === userId)?.cart_items ?? {};

  // add cart items into state for easily dynamic update
  const [cartQuantities, setCartQuantities] = React.useState({});

  // Group products by brand
  const [groupedProducts, setGroupedProducts] = useState({});

  const checkQty = (productItemsArr, cartQuantitiesObj) => {
    // "id" : stockBoxQty
    if (productItemsArr.length === 0 || Object.keys(cartQuantitiesObj).length === 0) return {};
    const max = Object.values(cartQuantitiesObj).reduce((accumulator, currentValue) => {
      return Math.max(accumulator, +currentValue);
    }, cartQuantitiesObj[0]);
    if (max === 0) return {};
    const productStockCurrentQuantities = productItemsArr.reduce((acc, item) => ({ ...acc, [+item.id]: +Math.floor(+item.qty / +item.box_qty) }), {});
    const newCartQuantities = Object.fromEntries(Object.entries(cartQuantitiesObj)
      .map(([id, cartBoxCount]) => {
        const stockBoxes = +productStockCurrentQuantities[+id];
        return ["" +id, +cartBoxCount > stockBoxes ? stockBoxes : +cartBoxCount]
      })
      .filter(([id, cartBoxCount]) => {
        const stockBoxes = +productStockCurrentQuantities[+id];
        return Object.keys(productStockCurrentQuantities).includes("" + id) && +cartBoxCount != 0 && +cartBoxCount <= stockBoxes
      })
    );
    // update cart on initial if no valid filtered products
    if (Object.keys(newCartQuantities).length === 0) {
      updateCart({ user_id: userId, cartItems: cartQuantities });
    }
    return newCartQuantities;
  }

  const increaseQuantity = (id , qty , box_qty) => {
    increaseQuantityFunction(id, qty, box_qty, cartQuantities, setCartQuantities);
  }

  const decreaseQuantity = (id) => {
    const newCartQuantities = { ...cartQuantities };
    if (!newCartQuantities?.[id] || newCartQuantities?.[id] < 1) return;
    newCartQuantities[id] = (newCartQuantities?.[id] ?? 1) - 1;
    const filteredNewCartQuantities = checkQty(productItems, newCartQuantities);
    setCartQuantities(prev => filteredNewCartQuantities);
  }

  const removeItem = (productId) => {
    const newCartQuantities = { ...cartQuantities };
    delete newCartQuantities[productId];
    setCartQuantities(prev => newCartQuantities);
  }

  const toggleSelectBrandItems = (e) => {
    const brandName = e.target.getAttribute("data-brand-name");
    const productIds = groupedProducts[brandName]?.map(product => product.id) ?? [];
    if (e.target.checked) {
      setSelectedItems(prev => [...prev, ...productIds]);
      setSelectedBrands(prev => [...prev, brandName]);
    } else {
      setSelectedItems(prev => {
        const prevCopy = [...prev];
        const filtered = prevCopy.filter(id => !productIds.includes(id));
        return filtered
      });
      setSelectedBrands(prev => {
        const prevCopy = [...prev];
        const filtered = prevCopy.filter(name => name !== brandName);
        return filtered
      });
    }
  }

  const toggleSelectProductItem = (e) => {
    if (e.target.checked) {
      setSelectedItems(prev => [...prev, Number(e.target.getAttribute("data-product-id"))]);
    } else {
      setSelectedItems(prev => {
        const prevCopy = [...prev];
        const filtered = prevCopy.filter(id => Number(id) !== Number(e.target.getAttribute("data-product-id")));
        return filtered
      });
    }
  }

  const quantityControl = {
    cartQuantities,
    increaseQuantity,
    decreaseQuantity,
    removeItem,
  }

  const selectControl = {
    selectedItems,
    selectedBrands,
    toggleSelectBrandItems,
    toggleSelectProductItem,
  }

  const getProductItems = () => {
    // fetch required data
    const productIds = Object.keys(cartItems);
    setIsLoading(true);
    axios
      .post(route("react.cart-data", { productIds }))
      .then(response => {
        if (response.data.success) {
          setProductItems(prev => response.data.productItems);
          setCartQuantities(prev => checkQty(response.data.productItems, prev));
          setTimeout(() => {
            setIsLoading(false);
          }, 500)
        }
      }).catch(error => {
        console.error(error);
      });
  }

  const handleUploadCartFile = async  (e) => {
    // return console.log( cartUpdateFile );

    if (!cartUpdateFile) {
      alert("Please select a file first.");
      return;
    }

    const formData = new FormData();
    formData.append("cart_file", cartUpdateFile);

    // Reset states
    setIsUploading(true);
    setUploadError(null);
    setUploadSuccessMsg("");

    try {
      const response = await axios.post(
        route("react.upload-cart-file"),
        formData,
        {
          headers: {
            "Content-Type": "multipart/form-data",
            "X-CSRF-TOKEN": csrfToken
          },
        }
      );

      const responseData = response.data;
      const data = responseData.data;

      // Handle success
      if (responseData.success && data.valid_barcodes_count > 0) {
        // Merge new quantities with existing cart
        const filteredQuantities = data.filtered_quantities;
        const newCartQuantities = { ...cartQuantities, ...filteredQuantities };

        // Update cart
        setCartQuantities(prev => newCartQuantities);
        updateCart({ user_id: userId, cartItems: newCartQuantities });

        // Build success message
        let successMsg = `${data.valid_barcodes_count} ${tr["valid_items_added_of"]} ${data.total_rows}.`;

        // Add warning if some rows were skipped
        if (data.skipped_rows > 0) {
          successMsg += ` ${data.skipped_rows} ${tr["rows_skipped"] || "rows skipped"}.`;
        }

        successMsg += ` ${tr["please_wait"]}`;
        setUploadSuccessMsg(successMsg);

        // Reload after delay
        setTimeout(() => {
          router.visit( route("react.cart"), { preserveScroll: true });
        }, 2000);

      } else if (responseData.success && data.valid_barcodes_count === 0) {
        // No valid items found
        setUploadError(tr["no_valid_items_found"] || "No valid items found in the file.");
      } else {
        // Generic error
        setUploadError(responseData.message || tr["upload_failed"] || "Upload failed.");
      }

    } catch (error) {
      console.error("Upload error:", error);
      setUploadError(tr["unexpected_error"] || "An unexpected error occurred.");
    } finally {
      setIsUploading(false);
    }
  }

  useEffect(() => {
    if (Object.keys(cartQuantities)?.length > 0) return;
    if (!Object.keys(cartItems)?.length) return;
    setCartQuantities(cartItems);
    getProductItems();
  }, [Object.keys(cartItems)?.length]);

  useEffect(() => {
    if (!!productItems?.length) {
      setGroupedProducts(Object.groupBy(productItems, (p) => p?.brand));
    }
  }, [productItems?.length, Object.keys(cartQuantities)?.length]);

  useEffect(() => {
    if (!selectedItems?.length) setSelectedItems(productItems?.map(product => product.id) ?? []);
    if (!selectedBrands?.length) setSelectedBrands(Object.keys(groupedProducts) ?? []);
  }, [productItems?.length, groupedProducts]);

  useEffect(() => {
    localStorage.setItem("selectedItems_" + userId, JSON.stringify(selectedItems));
  }, [selectedItems]);

  useEffect(() => {
    if (
      (!Object.keys(cartQuantities)?.length && Object.keys(cartItems)?.length > 1)
      || Object.values(cartItems).toString() === Object.values(cartQuantities).toString()
      || !productItems?.length
    ) return;
    updateCart({ user_id: userId, cartItems: cartQuantities });
  }, [cartQuantities, productItems?.length]);

  useEffect(() => {
    if (isUploadModalOpen) return;
    setCartUpdateFile(null);
  }, [isUploadModalOpen]);

  useEffect(() => {
    if (!Object.keys(cartQuantities)?.length) setGroupedProducts({})
  }, [Object.keys(cartQuantities)?.length]);

  return (
    <Layout
      pageTitle={tr["cart"]}
      breadcrumbs={[
        { label: tr["home"], url: route("react.home") },
        { label: tr["cart"], url: "#" },
      ]}
    >
      <div className="container py-8 mx-auto">
        <div className="flex items-center justify-between mb-6 pb-4 border-b border-[#E5E7EB]">
          <h1 className="text-2xl font-semibold">
            {tr["cart"]}
          </h1>
          <Button
            className="!bg-blue-800 !rounded-lg max-sm:text-sm !px-4 sm:!px-10 !py-2 flex items-center gap-2"
            type="button"
            onClick={() => setIsUploadModalOpen(true)}
          >
            <span className="">
              {tr["upload_items"]}
            </span>
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M9 2.25V11.25M9 2.25L12.75 6M9 2.25L5.25 6M15.75 11.25V14.25C15.75 14.6478 15.592 15.0294 15.3107 15.3107C15.0294 15.592 14.6478 15.75 14.25 15.75H3.75C3.35218 15.75 2.97064 15.592 2.68934 15.3107C2.40804 15.0294 2.25 14.6478 2.25 14.25V11.25" stroke="white" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
            </svg>
          </Button>
        </div>
        <div className="grid grid-cols-1 gap-5">
          {/* Product List */}
          <div className="flex flex-col ">
            {
              isLoading
                ?
                <div className="mb-10 bg-gray-100 min-h-64 flex flex-1 items-center justify-center">
                  <span className="animate-pulse ">
                    {tr["loading"]} ...
                  </span>
                </div>
                : <>
                  {!Object.keys(cartQuantities)?.length || !Object.keys(groupedProducts)?.length ? (
                    <EmptyCart />
                  ) : (
                    <div className="flex flex-1 flex-col">
                      {Object.entries(groupedProducts).map(
                        ([brand, products]) => (
                          <CartProductRows
                            key={brand}
                            brand={brand}
                            products={products}
                            quantityControl={quantityControl}
                            selectControl={selectControl}
                          />
                        )
                      )}
                    </div>
                  )}
                </>}
          </div>

          {!!Object.keys(groupedProducts)?.length && <div className="w-full flex justify-end items-center max-xl:flex-col gap-5 mt-2">
            {/* <div className="flex max-sm:flex-col gap-5 max-sm:w-full sm:gap-10 bg-gray-100 p-3 rounded-lg">
              <input type="text" className="bg-white rounded-md px-6 sm:w-100 py-2" placeholder={tr["coupon_code"]} />
              <Button
                className="px-5 !sm:px-10 !py-2 !rounded-lg"
                disabled={true}
              >
                {tr['apply']}
              </Button>
            </div> */}
            <div className="flex max-sm:flex-col max-sm:w-full max-sm:items-stretch gap-5">
              <Button
                href={route("react.checkout")}
                className="max-sm:w-full !text-sm !py-4  !min-w-65">
                {tr["proceed_to_order_request"]}
              </Button>
              <Button
                variant="outline"
                className="max-sm:w-full !text-sm px-15 !border-2 !border-gray-200 !py-4 !min-w-65"
                onClick={_ => { setCartQuantities({}); updateCart({ user_id: userId, cartItems: {} }); }}
              >
                {tr["clear_cart"]}
              </Button>
            </div>
          </div>}
        </div>
      </div>
      <Modal
        withoutHeaderBg={true}
        isOpen={isUploadModalOpen}
        onClose={() => setIsUploadModalOpen(false)}
        title={tr["upload_items_via_sheets"]}>

        <a href="/public/download/cart_upload_demo.xlsx" download target="_blank" className="py-3 px-6  bg-[#F2F2F2] flex items-center justify-center rounded-md mb-6 gap-5 cursor-pointer" >
          <span>
            {tr["download_file_template"]}
          </span>
          <svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M6 8L11 13M11 13L16 8M11 13V1M21 13V17C21 17.5304 20.7893 18.0391 20.4142 18.4142C20.0391 18.7893 19.5304 19 19 19H3C2.46957 19 1.96086 18.7893 1.58579 18.4142C1.21071 18.0391 1 17.5304 1 17V13" stroke="black" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        </a>

        {isUploadModalOpen && <DragFileInput
          id="cart_update_file"
          onChange={(file) => setCartUpdateFile(file)}
          error={null}
          accept=".xlsx"
          required
        />}

        {isUploading && (
          <div className="text-blue-600 mt-2">
            {tr["uploading"] || "Uploading..."}
            <span className="animate-pulse">...</span>
          </div>
        )}

        {!!uploadSuccessMsg &&
          <p className="text-center text-green-600">{uploadSuccessMsg}</p>
        }

        {uploadError && (
          <div className="text-red-600 mt-2">
            {uploadError}
          </div>
        )}

        <div className="flex justify-stretch gap-5 items-center w-full mt-12">
          <Button
            className="!w-1/2"
            onClick={handleUploadCartFile}
          >
            {tr["submit"]}
          </Button>
          <Button
            variant="outline"
            className="!w-1/2"
            onClick={() => setIsUploadModalOpen(false)}
          >
            {tr["cancel"]}
          </Button>
        </div>
      </Modal>
    </Layout>
  );
}
