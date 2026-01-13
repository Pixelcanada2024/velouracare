import { Link, usePage } from "@inertiajs/react";

const PopUpMarketingModal = ({ isOpen = false, onClose }) => {
  const marketingProduct = usePage().props.pop_up_marketing;

  const handleClose = () => {
    if (onClose) onClose();
  };

  const handleHidden = () => {
    document.cookie = "popUpMarketingModal=true; max-age=86400; path=/";
    if (onClose) onClose();
  };

  if (
    !isOpen ||
    !marketingProduct ||
    !marketingProduct.image ||
    !marketingProduct.product ||
    !marketingProduct.product.image
  ) {
    return null;
  }

  return (
    <div className="fixed inset-0 z-100 flex items-center justify-center bg-black/70 backdrop-blur-sm">
      <div className="relative w-full max-w-lg mx-5 bg-white rounded-lg shadow-2xl overflow-hidden">
        {/* Close Button */}
        <div className="flex justify-between items-center h-16 px-4">

          <button onClick={handleHidden} className="cursor-pointer underline underline-offset-3">
            Don’t show 24 Hours
          </button>
          <button
            onClick={handleClose}
            className=" cursor-pointer"
          >
            <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M17.0026 17.0007L26.9193 26.9173M17.0026 17.0007L7.08594 7.08398M17.0026 17.0007L7.08594 26.9173M17.0026 17.0007L26.9193 7.08398" stroke="black" strokeWidth="3" strokeLinecap="round" strokeLinejoin="round" />
            </svg>
          </button>
        </div>

        {/*  Main Image */}
        <div>
          <img
            src={marketingProduct.image}
            alt="Popup Banner"
            className="w-full object-cover"
          />
        </div>

        {/* Product Image */}
        <div className="h-28 gap-4 px-4 flex justify-start items-center">
          <img
            src={marketingProduct.product?.image}
            alt={marketingProduct.product?.name}
            className="w-24 h-24 object-contain"
          />
          <div className="space-y-2 ">
            <p className="text-lg font-semibold truncate max-w-40 sm:max-w-70 lg:max-w-90" title={marketingProduct.product?.name}>{marketingProduct.product?.name}</p>
            <Link className="underline underline-offset-3 " href={route('react.product', { product: marketingProduct.product?.id })}>

              <div className="flex gap-3 items-center">
                <span>Learn More</span>
                <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M3.95831 9.5H14.8437" stroke="black" strokeLinecap="round" strokeLinejoin="round" />
                  <path d="M10.7957 13.8541L15.1499 9.49998L10.7957 5.14581" stroke="black" strokeLinecap="round" strokeLinejoin="round" />
                </svg>

              </div>
            </Link>
          </div>
        </div>

      </div>
    </div>
  );
};

export default PopUpMarketingModal;
