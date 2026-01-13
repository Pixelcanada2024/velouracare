import { useState } from 'react';

import Layout from "@/components/Layout/Layout";
import TextInput from "@/components/ui/TextInput";
import PasswordInput from "@/components/ui/PasswordInput";
import DragFileInput from "@/components/ui/DragFileInput";
import Button from "@/components/ui/Button";
import AddressModal from "./Partials/AddressModal";
import EditAddressModal from "./Partials/EditAddressModal";

import { Link, useForm, usePage } from '@inertiajs/react';
import { useTranslation } from '@/contexts/TranslationContext';


export default function Dashboard() {

  // Start language
  const [{lang, currency, tr}, _setTranslation] = useTranslation();
  // end lang

  const { auth: { user }, businessInfo, businessProofAssets, availableShippingAddresses, availableBillingAddresses, shippingAddress, billingAddress, countries } = usePage().props;

  // Change Password
  const [changePassword, setChangePassword] = useState(false);
  const toggleShowSaveButton = () => {
    setChangePassword(!changePassword);
  }

  // Modal states
  const [showAddressModal, setShowAddressModal] = useState(false);
  const [showEditAddressModal, setShowEditAddressModal] = useState(false);
  const [editingAddress, setEditingAddress] = useState(null);
  const [addressModalType, setAddressModalType] = useState('shipping');

  // Address modal handlers
  const handleChangeAddress = (type = 'shipping') => {
    setAddressModalType(type);
    setShowAddressModal(true);
  };

  const handleEditAddress = (address = null) => {
    setEditingAddress(address);
    setShowEditAddressModal(true);
  };

  const handleAddNewAddress = () => {
    setEditingAddress(null);
    setShowEditAddressModal(true);
  };



  // Password
  const { data, setData, post, errors, reset } = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
  });

  const handleSubmit = (e) => {
    e.preventDefault();
    post(route("user.password-profile.update"), {
      onSuccess: () => {
        setChangePassword(false);
        reset()
      }
    });

  };


  return (
    <Layout pageTitle={tr['dashboard']}>
      <div className='bg-[#F2F4F7]'>
        <div className='container mx-auto py-6 flex flex-col gap-10'>
          <div className="text-sm text-gray-600 mb-2">
            <span><Link href={route('react.home')}>{tr['home']}</Link></span> / <span className="text-black"><Link href={route('react.dashboard')}>{tr['my_account']}</Link></span>
          </div>
          <h1 className="text-4xl font-bold " style={{ fontFamily: 'Times New Roman' }}>{tr['my_account']}</h1>
        </div>
      </div>
      <div className="container mx-auto py-8 mb-20">

        {/* Business Profile Section */}
        <div className="mb-8 grid grid-cols-4 gap-x-10 gap-y-5 items-center capitalize">

          <div className="border-b border-[#E5E7EB] py-5 gap-x-10 col-span-4 grid grid-cols-4">
            <div className="text-xl font-semibold  col-span-1">{tr['business_profile']}</div>
            <div className="col-span-3">{tr['modify_account_desc']}</div>
          </div>

          <div className="text-right font-bold ">({tr['email_address']})</div>
          <TextInput
            id="email"
            type="email"
            className="w-full col-span-3"
            value={user.email}
            disabled
            ShowError={false}
          />

          <div className="text-right font-bold">{tr['company']}</div>
          <TextInput
            id="company"
            className="w-full col-span-3"
            value={businessInfo?.company_name || ''}
            disabled
            ShowError={false}

          />

          <div className="text-right font-bold">{tr['name']}</div>
          <TextInput
            id="name"
            className="w-full col-span-3"
            value={user.name}
            disabled
            ShowError={false}

          />

          <div className="text-right font-bold">{tr['country']}</div>
          <TextInput
            id="country"
            className="w-full col-span-3"
            value={businessInfo?.country?.name || ''}
            disabled
            ShowError={false}

          />

          <div className="text-right font-bold">{tr['sales_channel']}</div>
          <TextInput
            id="salesChannel"
            className="w-full col-span-3"
            value={businessInfo?.store_link || ''}
            disabled
            ShowError={false}

          />

          {/* Business Registration Section */}
          <div className="text-right font-bold self-start">{tr['business_registration']}</div>

          <div className="max-lg:col-span-3 col-span-2">
            <DragFileInput
              id="businessRegistration"
              disabled={true}
            />
            <div className="flex max-sm:flex-col gap-5 border-b border-[#E5E7EB] py-5">
              {businessProofAssets && businessProofAssets.length > 0 ? (
                businessProofAssets.map((asset, idx) => (
                  <div key={idx} className="flex items-center">
                    {/* Render line only before the second item (index 1) */}
                    {idx === 1 && <div className='w-[1px] h-full bg-[#E5E7EB]'></div>}

                    <div className="text-center flex items-center gap-3 px-5">
                      {asset.path.includes('.pdf') ? (
                        <div className="w-16 h-16 bg-red-100 flex items-center justify-center rounded">
                          <span className="text-red-600 text-xs font-bold">PDF</span>
                        </div>
                      ) : (
                        <img src={asset.url} alt={asset.name} className="w-16 h-16 object-cover mx-auto rounded" />
                      )}
                      <div>
                        <div className="text-xs text-[#818181] font-inter">{tr['business_document']}</div>
                      </div>
                    </div>

                    {/* Render line only after the second item (index 1) */}
                    {idx === 1 && <div className='w-[1px] h-full bg-[#E5E7EB]'></div>}
                  </div>
                ))
              ) : (
                <div className="text-center text-[#818181] font-inter py-8">
                  {tr['no_business_documents_uploaded']}
                </div>
              )}
            </div>
          </div>

          {/* Password */}
          <div className="py-5 col-span-4 grid grid-cols-4 gap-x-10 ">
            <div className="text-xl font-bold col-span-1 text-right mt-3">{tr['password']}</div>
            <div className="px-2 col-span-3">
              {changePassword ? (
                <form onSubmit={handleSubmit}>

                  <div className='grid grid-cols-7 gap-5 items-center'>
                    <div className="text-right font-bold col-span-2">{tr['current_password']}</div>
                    <PasswordInput
                      id="current_password"
                      className="w-full col-span-5 sm:col-span-4 !mb-0"
                      value={data.current_password}
                      onChange={(e) =>
                        setData("current_password", e.target.value)
                      }
                      placeholder={tr['enter_your_current_password']}
                      error={errors.current_password}

                      required
                    />
                    <div className='hidden sm:block col-span-1'></div>

                    <div className="text-right font-bold col-span-2">{tr['new_password']}</div>
                    <PasswordInput
                      id="password"
                      className="w-full col-span-5 sm:col-span-4 !mb-0"
                      value={data.password}
                      onChange={(e) =>
                        setData("password", e.target.value)
                      }
                      placeholder={tr['enter_your_new_password']}
                      error={errors.password}
                      required
                    />
                    <div className=' hidden sm:block col-span-1'></div>


                    <div className="text-right font-bold col-span-2">{tr['confirm_password']}</div>
                    <PasswordInput
                      id="password"
                      className="w-full col-span-5 sm:col-span-4 !mb-0"
                      value={data.password_confirmation}
                      onChange={(e) =>
                        setData("password_confirmation", e.target.value)
                      }
                      placeholder={tr['enter_your_confirm_password']}
                      error={errors.password_confirmation}
                      required
                    />
                    <div className='hidden sm:block col-span-1'></div>


                    <Button
                      type="submit"
                      variant="primary"
                      className="rounded-lg min-w-40 mt-3"
                    >
                      {tr['save']}
                    </Button>
                  </div>
                </form>

              ) : (
                <>
                  <Button
                    type="button"
                    variant="primary"
                    className="rounded-lg min-w-40"
                    onClick={toggleShowSaveButton}
                  >
                    {tr['change']}
                  </Button>
                </>
              )}
            </div>
          </div>
        </div>



        {/* Invoice Address Section */}
        <div className="mb-8">
          <div className="flex justify-between items-center mb-4">
            <h2 className="text-2xl font-bold">{tr['invoice_address']}</h2>
            <button
              onClick={() => handleChangeAddress('billing')}
              className="text-black underline font-semibold cursor-pointer"
            >
              {tr['change_address']}
            </button>
          </div>
          <p className="text-[#7E7E87] mb-4">{tr['invoice_address_desc']}</p>

          <div className="border border-[#E5E7EB] p-6 rounded-lg">
            {billingAddress ? (
              <div className="space-y-1 text-[#7E7E87] font-inter">
                <div>{billingAddress.first_name} {billingAddress.last_name}</div>
                <div>{billingAddress.phone}</div>
                <div>{billingAddress.address}</div>
                <div>{billingAddress.city}, {billingAddress.state}</div>
                <div>{billingAddress.country?.name}</div>
                <div>{billingAddress.postal_code}</div>
              </div>
            ) : (
              <div className="text-center text-[#818181] font-inter py-8">
                {tr['no_invoice_address_desc']}

              </div>
            )}
          </div>
        </div>

        {/* Delivery Address Section */}
        <div className="mb-8">
          <div className="flex justify-between items-center mb-4">
            <h2 className="text-2xl font-bold">{tr['delivery_address']}</h2>
            <button
              onClick={() => handleChangeAddress('shipping')}
              className="text-black underline font-semibold cursor-pointer"
            >
              {tr['change_address']}
            </button>
          </div>
          <p className="text-[#7E7E87] mb-4">{tr['delivery_address_desc']}</p>

          <div className="border border-[#E5E7EB] p-6 rounded-lg">
            {shippingAddress ? (
              <div className="space-y-1 text-[#7E7E87] font-inter">
                <div>{shippingAddress.first_name} {shippingAddress.last_name}</div>
                <div>{shippingAddress.phone}</div>
                <div>{shippingAddress.address}</div>
                <div>{shippingAddress.city}, {shippingAddress.state}</div>
                <div>{shippingAddress.country?.name}</div>
                <div>{shippingAddress.postal_code}</div>
              </div>
            ) : (
              <div className="text-center text-[#818181] font-inter py-8">
                {tr['no_delivery_address_desc']}
              </div>
            )}
          </div>
        </div>
      </div>

      {/* Address Selection Modal */}
      <AddressModal
        isOpen={showAddressModal}
        onClose={() => setShowAddressModal(false)}
        onEditAddress={handleEditAddress}
        onAddNewAddress={handleAddNewAddress}
        addresses={addressModalType === 'shipping' ? (availableShippingAddresses || []) : (availableBillingAddresses || [])}
        addressType={addressModalType}
        currentShippingAddress={shippingAddress}
        currentBillingAddress={billingAddress}
      />

      {/* Create / Edit Address Modal */}
      <EditAddressModal
        isOpen={showEditAddressModal}
        onClose={() => setShowEditAddressModal(false)}
        address={editingAddress}
        countries={countries || []}
      />
    </Layout>
  )
}
