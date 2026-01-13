import TextInput from "@/components/ui/TextInput";
import PhoneInput from "@/components/ui/PhoneInput";
import PrimarySelect from "@/components/ui/PrimarySelect";
import PasswordInput from "@/components/ui/PasswordInput";
import { Link, usePage } from "@inertiajs/react";

// start lang
import { useTranslation } from "@/contexts/TranslationContext";
// end lang

export default function PersonalInfo({ countryData, data, setData, errorsReactive }) {
  // Start language
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();
  // end lang
  const { isSkyAmerica } = usePage().props;

  const errors = errorsReactive ?? usePage().props.errors;

  // Country options
  const countryOptions = countryData ? countryData : [];

  return (
    <>
      <div className="flex flex-col gap-4">

        <TextInput
          id="name"
          name="name"
          type="text"
          label={tr['your_name']}
          placeholder={tr['enter_your_name']}
          value={data.name}
          onChange={(e) => setData("name", e.target.value)}
          error={errors.name}
          required
        />

        <TextInput
          id="email"
          name="email"
          type="email"
          label={tr['email_address']}
          placeholder={tr['write_email_address']}
          value={data.email}
          onChange={(e) => setData("email", e.target.value)}
          error={errors.email}
          required

        />

        <PhoneInput
          id="phone"
          name="phone"
          label={tr['phone_number']}
          placeholder="+11234567890"
          value={data.phone}
          onChange={(e) => setData("phone", e.target.value)}
          error={errors.phone}
          required
        />

        <PasswordInput
          id="password"
          label={tr['password']}
          placeholder={tr['enter_your_password']}
          value={data.password}
          onChange={(e) => setData("password", e.target.value)}
          error={errors.password}
          required
        />

        <TextInput
          id="address_line_one"
          name="address_line_one"
          type="text"
          label={tr['address_line_1']}
          placeholder={tr['enter_address_line_1']}
          value={data.address_line_one}
          onChange={(e) => setData("address_line_one", e.target.value)}
          error={errors.address_line_one}
        />

        <TextInput
          id="address_line_two"
          name="address_line_two"
          type="text"
          label={tr['address_line_2']}
          placeholder={tr['enter_address_line_2']}
          value={data.address_line_two}
          onChange={(e) => setData("address_line_two", e.target.value)}
          error={errors.address_line_two}
        />

        <PrimarySelect
          id="country_id"
          name="country_id"
          type="country_id"
          label={tr['country']}
          options={countryOptions}
          value={data.country_id}
          onChange={(e) => setData("country_id", e.target.value)}
          error={errors.country_id}
          hasTranslation={true}
          required
        />

        <div className="flex max-sm:flex-col items-center sm:gap-8">
          {isSkyAmerica && <TextInput
            id="state"
            name="state"
            type="text"
            label="State"
            placeholder="Enter your state"
            className="w-full"
            value={data.state}
            onChange={(e) => setData("state", e.target.value)}
            error={errors.state}
          />}

          <TextInput
            id="city"
            name="city"
            type="text"
            label={tr['city']}
            placeholder={tr['enter_your_city']}
            className="w-full"
            value={data.city}
            onChange={(e) => setData("city", e.target.value)}
            error={errors.city}
            required

          />
        </div>
        <TextInput
          id="postal_code"
          name="postal_code"
          type="text"
          label={tr['zip_code']}
          placeholder={tr['enter_zip_code']}
          value={data.postal_code}
          onChange={(e) => setData("postal_code", e.target.value)}
          error={errors.postal_code}
          className="w-full"
        />


        <div className="flex items-start mb-4">

          <div className="flex items-center h-5">
            <label className="relative inline-flex items-center cursor-pointer">
              <input
                id="agree"
                type="checkbox"
                name="agree"
                checked={data.agree}
                onChange={(e) => setData("agree", e.target.checked)}
                required
                className={
                  "peer w-4 h-4 appearance-none border rounded-sm transition-all " +
                  (errors.agree
                    ? "border-red-500 bg-red-50"
                    : "border-gray-300 hover:border-primary-400") +
                  " checked:bg-primary-500 checked:border-primary-500"
                }
              />
              {/* Custom check icon */}
              <svg
                className="absolute left-[2px] top-[2px] w-3 h-3 text-white opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                strokeWidth={3}
              >
                <path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7" />
              </svg>
            </label>
          </div>

          <label
            htmlFor="agree"
            className="mx-2 text-sm text-primary-400 space-x-2"
          >
            {tr['i_agree']} <span className="underline"> <Link href={route('custom-pages.show_custom_page', { slug: 'privacy-policy' })}>{tr['privacy_policy']}</Link></span>{tr['and']}<span className="underline"> <Link href={route('custom-pages.show_custom_page', { slug: 'terms' })}>{tr['sales_conditions']}</Link> </span>
          </label>
        </div>

      </div>


    </>
  )
}
