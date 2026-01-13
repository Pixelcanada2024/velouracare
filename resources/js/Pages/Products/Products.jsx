import Layout from "@/components/Layout/Layout"
import { Link, usePage } from "@inertiajs/react"
import React, { useEffect } from "react"
import BrandHeader from "./Partials/BrandHeader"
import ProductCard from "./Partials/ProductCard"
import ProductTable from "./Partials/ProductTable"
import Filters from "./Partials/Filters"
import ProductGridCard from "./Partials/ProductGridCard"
import { useCart } from "@/contexts/CartItemsContext"
import PromotionCard from "./Partials/PromotionCard"

// start lang
import { useTranslation } from "@/contexts/TranslationContext"
import { increaseQuantityFunction } from "@/utils/cartSharedFunctions"
// end lang

export default function Products({ brand = null, promotion = null, paginatedProducts = [], filterOptionsData = {} }) {

  // Start language
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();
  // end lang
  
  const queryParams = usePage().props.queryParams ?? {};
  let isPromotionPage = queryParams?.["promotion"] ? true : false;

  let pageName = (!!promotion && isPromotionPage ? tr["promotions"] : null) ?? brand?.name
    ?? (!!queryParams?.categories?.[0] ? (tr[queryParams?.categories?.[0]] ? tr[queryParams?.categories?.[0]] : queryParams?.categories?.[0]) : null)
    ?? queryParams?.search ?? tr["products"];

  const [filterData, setFilterData] = React.useState({
    brand: queryParams?.brands?.[0] ?? "",
    category: queryParams?.categories?.[0] ?? "",
    availability: queryParams?.availability ?? "",
    sortBy: queryParams?.sortBy ?? "",
    displayStyle: queryParams?.displayStyle ?? "table",
  });

  const productIds = paginatedProducts?.data?.map(product => product.id) ?? [];

  const [selectedItems, setSelectedItems] = React.useState([]);

  const toggleSelect = (e) => {
    if (selectedItems.length === productIds.length) {
      setSelectedItems([]);
      return;
    }
    setSelectedItems(productIds);
  }

  //  get user cart items
  const { cartData, updateCart } = useCart();

  const userId = usePage().props?.auth?.user?.id ?? null;
  const cartItems = cartData?.find(item => item.user_id === userId)?.cart_items ?? {};

  // add cart items into state for easily dynamic update
  const [cartQuantities, setCartQuantities] = React.useState({});

  useEffect(() => {
    if (Object.keys(cartQuantities)?.length > 0) return;
    setCartQuantities(cartItems);
  }, [Object.keys(cartItems)?.length]);

  useEffect(() => {
    const btn = document.querySelector("#select-all-product-btn");
    const addToCartBtn = document.querySelector("#add-selected-to-cart-btn");
    if (!!addToCartBtn) {
      if (!selectedItems?.length) {
        addToCartBtn.style.opacity = "0.5";
        addToCartBtn.style.cursor = "not-allowed";
      } else {
        addToCartBtn.style.opacity = "1";
        addToCartBtn.style.cursor = "pointer";
      }
    }
    if (!!btn) {
      if (selectedItems.length === productIds.length) {
        btn.innerHTML = tr["deselect_all_products"];
        btn.style.color = "black";
        btn.style.background = "white";
        return;
      }
      btn.innerHTML = tr["select_all_products"];
      btn.style.color = "white";
      btn.style.background = "black";
    }
  }, [selectedItems?.length]);

  const increaseQuantity = (id , qty , box_qty) => {
    increaseQuantityFunction(id, qty, box_qty, cartQuantities, setCartQuantities);
  }

  const decreaseQuantity = (id) => {
    const newCartQuantities = { ...cartQuantities };
    if (!newCartQuantities?.[id] || newCartQuantities?.[id] < 1) return;
    newCartQuantities[id] = (newCartQuantities?.[id] ?? 0) - 1;
    setCartQuantities(newCartQuantities);
    // updateCart({ user_id: userId, cartItems: newCartQuantities });
  }

  const addSelectedItemsToCart = (ids = [...selectedItems]) => {
    if (!ids.length) return;
    const filteredEntries = Object.entries(cartQuantities).filter(([id, qty]) => {
      return ids.includes(+id)
    });
    const selectedCartItems = Object.fromEntries(filteredEntries);
    const newCartQuantities = { ...cartItems, ...selectedCartItems };
    updateCart({ user_id: userId, cartItems: newCartQuantities });
  }

  const quantityControl = {
    cartQuantities,
    increaseQuantity,
    decreaseQuantity,
    addSelectedItemsToCart,
  }

  return (
    <Layout
      pageTitle={`${pageName}`}
      hasTitleHeader={true}
      breadcrumbs={[
        { label: tr["home"], url: route("react.home") },
        { label: `${pageName}`, url: "#" },
      ]}
    >
      {/* Promotion */}
      {isPromotionPage && !!promotion && <PromotionCard promotion={promotion} />}

      {/* Header */}
      {!!brand && !isPromotionPage && <BrandHeader brand={brand} />}

      {/* Filters */}
      <Filters filterOptionsData={filterOptionsData} queryParams={queryParams} filterDataState={[filterData, setFilterData]} toggleSelect={toggleSelect} addSelectedItemsToCart={addSelectedItemsToCart} isEmptyProducts={!paginatedProducts.data.length} />

      {/* Stock List */}
      {!!paginatedProducts.data.length && filterData.displayStyle === "table" ?
        <>
          <ProductCard products={paginatedProducts} selectedItemsState={[selectedItems, setSelectedItems]} quantityControl={quantityControl} />
          <ProductTable products={paginatedProducts} selectedItemsState={[selectedItems, setSelectedItems]} quantityControl={quantityControl} />
        </>
        :
        <ProductGridCard products={paginatedProducts} selectedItemsState={[selectedItems, setSelectedItems]} quantityControl={quantityControl} />
      }

      {
        !paginatedProducts.data.length &&
        <div className="text-center text-gray-500 text-md
          container bg-gray-100 rounded-xl py-10 -mt-10 mb-20">
          <pre>
            {pageName == queryParams?.search ?
              tr["no_search_results_found"] + " \n " + " \n " + tr["try_different_Words"]
              :
              tr["no_products_found"]
            }
          </pre>
        </div>
      }

    </Layout>
  )
}
