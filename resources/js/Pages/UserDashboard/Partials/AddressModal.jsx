import React, { useEffect } from 'react'
import Button from '@/components/ui/Button'
import { useForm, router, usePage } from '@inertiajs/react'
// start lang
import { useTranslation } from '@/contexts/TranslationContext'
// end lang
export default function AddressModal({
  isOpen,
  onClose,
  onEditAddress,
  onAddNewAddress,
  addresses = [],
  addressType = 'shipping',
  currentShippingAddress = null,
  currentBillingAddress = null
}) {
  // Start language
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();
  // end lang
  const { data, setData, post, processing, errors, reset } = useForm({
    selected_address_id: '',
    address_type: addressType,
    is_same_billing: false
  });

  // Update address_type and select current address
  useEffect(() => {
    setData('address_type', addressType);
    setData('is_same_billing', false);

    if (isOpen) {
      if (addressType === 'shipping' && currentShippingAddress) {
        setData('selected_address_id', currentShippingAddress.id);
      } else if (addressType === 'billing' && currentBillingAddress) {
        setData('selected_address_id', currentBillingAddress.id);
      } else {
        setData('selected_address_id', '');
      }
    }
  }, [addressType, isOpen, currentShippingAddress, currentBillingAddress]);

  const handleEditAddress = (address) => {
    onEditAddress(address);
    onClose();
  };

  const handleAddNewAddress = () => {
    onAddNewAddress();
    onClose();
  };

  const handleSave = () => {
    if (data.selected_address_id || (addressType === 'shipping' && data.is_same_billing)) {
      post(route('user.address.update-type', data.selected_address_id), {
        address_type: data.address_type,
        is_same_billing: data.is_same_billing
      }, {
        onSuccess: () => {
          onClose();
        },

      });
    }
  };

  const handleDeleteAddress = (addressId, e) => {
    e.stopPropagation();
    if (confirm(tr['delete_address_confirm'])) {
      router.delete(route('user.address.delete', addressId), {
        onSuccess: () => {
          onClose();
        },
      });
    }
  };

  if (!isOpen) return null;

  return (
    <div
      onClick={onClose}
      className="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm">
      <div
        onClick={(e) => e.stopPropagation()}
        className="relative w-full sm:max-w-2xl sm:m-4 bg-white shadow-lg sm:rounded-lg max-h-[90vh] overflow-y-auto">

        {/* Close Button */}
        <button
          onClick={onClose}
          className="absolute cursor-pointer top-4 right-4 hover:text-gray-700 z-10"
        >
          <svg className='max-xl:scale-85 max-sm:scale-65' width="34" height="34" viewBox="0 0 34 34" fill="none">
            <path d="M16.9948 17L26.9115 26.9167M16.9948 17L7.07812 7.08337M16.9948 17L7.07812 26.9167M16.9948 17L26.9115 7.08337" stroke="black" strokeWidth="3" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        </button>

        {/* Debug Errors */}
        <div className="p-6">
          {/* Header */}
          <div className="text-center mb-6">
            <h2 className="text-[20px] sm:text-[24px] xl:text-[26px] font-bold">
              {addressType === 'shipping' ? tr['delivery_address'] : tr['invoice_address']}
            </h2>
          </div>

          {/* Add New Address Button */}
          <div className="mb-6">
            <button
              onClick={handleAddNewAddress}
              className="flex items-center gap-2 justify-center cursor-pointer font-medium w-full border border-[#E5E7EB] rounded-lg p-2"
            >
              <div className='flex items-center gap-5'>
                <span>{tr['add_new_address']}</span>
                <span className="text-lg">
                  <svg width="25" height="24" viewBox="0 0 25 24" fill="none">
                    <path d="M18.5 12H12.5M12.5 12H6.5M12.5 12V6M12.5 12V18" stroke="#222222" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                  </svg>
                </span>
              </div>
            </button>
          </div>

          {/* "Same as Billing" checkbox - only shows if billing address exists */}
          {addressType === 'shipping' && currentBillingAddress && (
            <div className="mb-6 flex items-center">
              <input
                type="checkbox"
                id="is_same_billing"
                checked={data.is_same_billing}
                onChange={(e) => {
                  setData('is_same_billing', e.target.checked);
                  if (e.target.checked) {
                    setData('selected_address_id', currentBillingAddress.id);
                  }
                }}
                className="w-5 h-5 accent-primary-500 mx-3 cursor-pointer"
              />
              <label htmlFor="is_same_billing" className="cursor-pointer">
                Same as Invoice Address
              </label>
            </div>
          )}

          {/* Address List */}
          {!(addressType === 'shipping' && data.is_same_billing) && (
            <div className="space-y-4 mb-6">
              {addresses.length > 0 ? addresses.map((address) => (
                <div key={address.id} className="relative">
                  <div className="flex items-start p-5 gap-3 font-inter border border-[#E5E7EB] pb-4 rounded-xl">
                    <input
                      type="radio"
                      name="selectedAddress"
                      value={address.id}
                      checked={data.selected_address_id === address.id}
                      onChange={() => setData('selected_address_id', address.id)}
                      className="w-8 h-8 accent-primary-500 self-center cursor-pointer"
                    />

                    {/* Address Details */}
                    <div className='flex  items-end w-full'>
                      <div className='flex-1'>
                        <div className="text-[13px] sm:text-sm text-[#7E7E87] sm:space-y-1">
                          <div>{address.first_name} {address.last_name}</div>
                          <div>{address.phone}</div>
                          <div>{address.address}</div>
                          <div>{address.city}, {address.state}, ({address.postal_code})</div>
                          <div>{address.country?.name}</div>
                        </div>
                      </div>

                      {/* Action Buttons */}
                      <div className="flex  gap-3 xl:gap-2 lrt:ml-4 ">

                        {/* Edit Button */}
                        <button
                          onClick={() => handleEditAddress(address)}
                          className="cursor-pointer"
                        >
                          <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="40" height="40" rx="20" fill="#F2F4F7" />
                            <path d="M24 20L16 28H12V24L20 16L22.869 13.131L22.87 13.13C23.265 12.735 23.463 12.537 23.691 12.463C23.8918 12.3977 24.1082 12.3977 24.309 12.463C24.537 12.537 24.734 12.735 25.129 13.129L26.869 14.869C27.265 15.265 27.463 15.463 27.537 15.691C27.6023 15.8918 27.6023 16.1082 27.537 16.309C27.463 16.537 27.265 16.735 26.869 17.131L24 20.001L20 16.001" stroke="#818181" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                          </svg>
                        </button>

                        {/* Delete Button */}
                        <button
                          onClick={(e) => handleDeleteAddress(address.id, e)}
                          className="cursor-pointer"
                        >
                          <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="40" height="40" rx="20" fill="#F2F4F7" />
                            <path d="M27.5 13.5L26.88 23.525C26.722 26.086 26.643 27.367 26 28.288C25.6826 28.7432 25.2739 29.1273 24.8 29.416C23.843 30 22.56 30 19.994 30C17.424 30 16.139 30 15.18 29.415C14.7059 29.1257 14.2972 28.7409 13.98 28.285C13.338 27.363 13.26 26.08 13.106 23.515L12.5 13.5M11 13.5H29M24.056 13.5L23.373 12.092C22.92 11.156 22.693 10.689 22.302 10.397C22.2151 10.3323 22.1232 10.2748 22.027 10.225C21.594 10 21.074 10 20.035 10C18.969 10 18.436 10 17.995 10.234C17.8975 10.2862 17.8045 10.3464 17.717 10.414C17.322 10.717 17.101 11.202 16.659 12.171L16.053 13.5M17.5 24.5V18.5M22.5 24.5V18.5" stroke="#E40707" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                          </svg>
                        </button>

                      </div>
                    </div>
                  </div>
                </div>
              )) : (
                <div className="text-center text-[#818181] font-inter py-8">
                  {tr['no_addresses_found']}
                </div>
              )}
            </div>
          )}

          {/* Bottom Buttons */}
          <div className="flex  gap-5 sm:gap-10">
            <Button
              onClick={handleSave}
              variant="primary"
              fullWidth
              disabled={processing || (!data.selected_address_id && !(addressType === 'shipping' && data.is_same_billing))}
            >
              {processing ? tr['saving'] : tr['save']}
            </Button>
            <Button
              onClick={onClose}
              variant="outline"
              fullWidth
            >
              {tr['close']}
            </Button>
          </div>
        </div>
      </div>
    </div>
  );
}
