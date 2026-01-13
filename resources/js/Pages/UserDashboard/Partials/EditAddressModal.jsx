import Button from '@/components/ui/Button'
import TextInput from '@/components/ui/TextInput'
import PhoneInput from '@/components/ui/PhoneInput'
import PrimarySelect from '@/components/ui/PrimarySelect'
import { useForm, usePage } from '@inertiajs/react'
import { useEffect } from 'react'
// start lang
import { useTranslation } from '@/contexts/TranslationContext'
// end lang
export default function EditAddressModal({ isOpen, onClose, address = null, countries = [] }) {

  // Start language
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();
  // end lang

  const { data, setData, post, processing, errors, reset } = useForm({
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    address_line_one: '',
    address_line_two: '',
    country_id: '',
    state: '',
    city: '',
    postal_code: '',
  });

  useEffect(() => {
    if (address) {
      setData({
        first_name: address.first_name || '',
        last_name: address.last_name || '',
        email: address.email || '',
        phone: address.phone || '',
        address_line_one: address.address_line_one || '',
        address_line_two: address.address_line_two || '',
        country_id: address.country_id || '',
        state: address.state || '',
        city: address.city || '',
        postal_code: address.postal_code || '',
      });
    } else {
      reset();
    }
  }, [address, isOpen]);

  const handleSubmit = (e) => {
    e.preventDefault();

    if (address) {
      // Update existing address
      post(route('user.address.update', address.id), {
        data: data,
        onSuccess: () => {
          onClose();
          reset();
        }
      });
    } else {
      // Create new address
      post(route('user.address.create'), {
        data: data,
        onSuccess: () => {
          onClose();
          reset();

        }
      });
    }
  };

  const handleCancel = () => {
    reset();
    onClose();
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
          <svg className='max-xl:scale-85 max-sm:scale-65' width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M16.9948 17L26.9115 26.9167M16.9948 17L7.07812 7.08337M16.9948 17L7.07812 26.9167M16.9948 17L26.9115 7.08337" stroke="black" strokeWidth="3" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        </button>

        {/* Modal Content */}
        <div className="p-6">
          {/* Header */}
          <div className="text-center mb-6">
            <h2 className="text-[20px] sm:text-[24px] xl:text-[26px] font-bold">
              {address ? tr['edit_address'] : tr['add_new_address']}
            </h2>
          </div>

          {/* Form */}
          <form onSubmit={handleSubmit} className="sm:space-y-4">
            {/* First Name and Last Name */}
            <div className="grid sm:grid-cols-2 sm:gap-4">
              <TextInput
                id="first_name"
                label={tr['first_name']}
                value={data.first_name}
                onChange={(e) => setData('first_name', e.target.value)}
                placeholder={tr['first_name']}
                error={errors.first_name}
                required
              />
              <TextInput
                id="last_name"
                label={tr['last_name']}
                value={data.last_name}
                placeholder={tr['last_name']}
                onChange={(e) => setData('last_name', e.target.value)}
                error={errors.last_name}
                required
              />
            </div>

            {/* Email */}
            <TextInput
              id="email"
              label={tr['email_address']}
              type="email"
              placeholder={tr['enter_your_email_address']}
              value={data.email}
              onChange={(e) => setData('email', e.target.value)}
              error={errors.email}
              required
            />

            {/* Phone Number */}

            <PhoneInput
              id="phone"
              name="phone"
              label={tr['phone_number']}
              value={data.phone}
              onChange={(e) => setData("phone", e.target.value)}
              error={errors.phone}
              required
            />

            {/* Country */}
            <PrimarySelect
              id="country_id"
              label={tr['country']}
              value={data.country_id}
              onChange={(e) => setData('country_id', e.target.value)}
              error={errors.country_id}
              options={countries.map(country => ({
                value: country.id,
                label: country.name
              }))}
              required
            />

            {/* Address Line 1 */}
            <TextInput
              id="address_line_one"
              label={tr['address_line_1']}
              placeholder={tr['enter_address_line_1']}
              value={data.address_line_one}
              onChange={(e) => setData('address_line_one', e.target.value)}
              error={errors.address_line_one}
              required
            />

            {/* Address Line 2 */}
            <TextInput
              id="address_line_two"
              label={tr['address_line_2']}
              placeholder={tr['enter_address_line_2']}
              value={data.address_line_two}
              onChange={(e) => setData('address_line_two', e.target.value)}
              error={errors.address_line_two}
            />

            {/* City and State */}
            <div className="grid grid-cols-2 gap-4">
              <TextInput
                id="city"
                label={tr['city']}
                placeholder={tr['enter_your_city']}
                value={data.city}
                onChange={(e) => setData('city', e.target.value)}
                error={errors.city}
                required
              />
              <TextInput
                id="state"
                label={tr['state']}
                placeholder={tr['enter_your_state']}
                value={data.state}
                onChange={(e) => setData('state', e.target.value)}
                error={errors.state}
                required
              />
            </div>

            {/* Zip Code */}
            <TextInput
              id="postal_code"
              label={tr['postal_code'] || "Zip Code (Postal Code)"}
              placeholder={tr['enter_your_postal_code'] || "Zip Code (Postal Code)"}
              value={data.postal_code}
              onChange={(e) => setData('postal_code', e.target.value)}
              error={errors.postal_code}
            />

            {/* Bottom Buttons */}
            <div className="flex gap-3 sm:gap-10 mt-3 sm:mt-8 ">
              <Button
                type="submit"
                variant="primary"
                fullWidth
                disabled={processing}
              >
                {processing ? tr['saving'] : tr['save']}
              </Button>
              <Button
                type="button"
                onClick={handleCancel}
                variant="outline"
                fullWidth
              >
                {tr['cancel']}
              </Button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
}
