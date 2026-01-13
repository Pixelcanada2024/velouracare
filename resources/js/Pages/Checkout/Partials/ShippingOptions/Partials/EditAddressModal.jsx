import Button from "@/components/ui/Button"
import TextInput from "@/components/ui/TextInput"
import PhoneInput from "@/components/ui/PhoneInput"
import PrimarySelect from "@/components/ui/PrimarySelect"
import { useEffect, useState } from "react"
import { useTranslation } from "@/contexts/TranslationContext"

export default function EditAddressModal({ isOpen, onClose, SelectedAddressState, countries = [] }) {
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();

  if (!isOpen) return;
  const [selectedAddress, setSelectedAddress] = SelectedAddressState;
  const type = selectedAddress.type;
  const address = selectedAddress[type];

  const initialVal = {
    first_name: "",
    last_name: "",
    email: "",
    phone: "",
    address_line_one: "",
    address_line_two: "",
    country_id: "",
    state: "",
    city: "",
    postal_code: "",
  };

  const [data, setData] = useState({ ...initialVal });

  const reset = () => setData({ ...initialVal });

  const setFormValueData = (field, value) => {
    setData((prevData) => ({
      ...prevData,
      [field]: value,
    }));
  }


  useEffect(() => {
    const address = selectedAddress[type];
    if (address) {
      setData({
        first_name: address.first_name || "",
        last_name: address.last_name || "",
        email: address.email || "",
        phone: address.phone || "",
        address_line_one: address.address_line_one || "",
        address_line_two: address.address_line_two || "",
        country_id: address.country_id || "",
        state: address.state || "",
        city: address.city || "",
        postal_code: address.postal_code || "",
      });
    } else {
      reset();
    }
  }, [selectedAddress, isOpen]);

  const handleSubmit = (e) => {
    const form = document.getElementById("edit-address-form");
    const formData = new FormData(form);
    const raw = Object.fromEntries(formData.entries());

    // Exclude "address_line_two" from validation
    const addressIsFilled = Object.entries(raw)
      .filter(([key]) => key !== "address_line_two" && key !== "postal_code")
      .every(([, value]) => !!value);

    // Example: show validation messages manually
    if (!addressIsFilled) {
      for (const [key, value] of Object.entries(raw)) {
        if (key === "address_line_two" || key === "postal_code") continue; // skip optional field
        const input = form.querySelector(`[name="${key}"]`);
        if (input && !value) {
          // Show your required message
          input.classList.add("border-red-500"); // example styling
          // input.nextElementSibling?.remove(); // remove old error
          const error = document.createElement("span");
          error.textContent = tr["this_field_is_required"];
          error.className = "text-red-500 text-sm my-2 block";
          input.insertAdjacentElement("afterend", error);
          setTimeout(() => {
            input.classList.remove("border-red-500");
            error.remove();
          }, 3000);
        }
      }
    } else {
      console.log("All required fields are filled");
    }

    setData(raw);

    if (addressIsFilled) {
      const newSelectedAddress = {
        ...selectedAddress,
        [type]: {
          ...raw,
          country: countries.find(c => c.id == raw.country_id),
        },
      };
      setSelectedAddress(newSelectedAddress);
      setTimeout(() => onClose(), 500);
    }
  };

  const handleCancel = () => {
    reset();
    onClose();
  };

  return (
    <div
      onClick={onClose}
      className="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm">
      <div
        onClick={(e) => e.stopPropagation()}
        className="relative w-full max-w-2xl sm:m-4 bg-white shadow-lg sm:rounded-lg">

        {/* Close Button */}
        <button
          onClick={onClose}
          className="absolute cursor-pointer top-4 right-4 hover:text-gray-700 z-10"
        >
          <svg className="max-xl:scale-85 max-sm:scale-65 " width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M16.9948 17L26.9115 26.9167M16.9948 17L7.07812 7.08337M16.9948 17L7.07812 26.9167M16.9948 17L26.9115 7.08337" stroke="black" strokeWidth="3" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        </button>

        {/* Modal Content */}
        <div className="p-6">
          {/* Header */}
          <div className="text-center mb-6">
            <h2 className="text-[20px] sm:text-[24px] xl:text-[26px] font-bold">
              {!!address ? tr["edit_address"] : tr["add_new_address"]}
            </h2>
          </div>

          {/* Form */}
          <form id="edit-address-form">
            <div className="sm:space-y-4 overflow-y-auto max-h-[60vh]">
              {/* First Name and Last Name */}
              <div className="grid sm:grid-cols-2 sm:gap-4">
                <TextInput
                  id="first_name"
                  name="first_name"
                  label={tr["first_name"]}
                  value={data.first_name}
                  onChange={(e) => setFormValueData("first_name", e.target.value)}
                  placeholder={tr["first_name"]}
                  required
                />

                <TextInput
                  id="last_name"
                  name="last_name"
                  label={tr["last_name"]}
                  value={data.last_name}
                  placeholder={tr["last_name"]}
                  onChange={(e) => setFormValueData("last_name", e.target.value)}
                  required
                />
              </div>

              {/* Email */}
              <TextInput
                id="email"
                name="email"
                label={tr["email"]}
                type="email"
                placeholder={tr["email"]}
                value={data.email}
                onChange={(e) => setFormValueData("email", e.target.value)}
                required
              />

              {/* Phone Number */}
              <PhoneInput
                id="phone"
                name="phone"
                label={tr["phone_number"]}
                value={data.phone}
                onChange={(e) => setFormValueData("phone", e.target.value)}
                required
              />

              {/* Country */}
              <PrimarySelect
                id="country_id"
                name="country_id"
                label={tr["country"]}
                value={data.country_id}
                onChange={(e) => setFormValueData("country_id", e.target.value)}
                options={countries.map(country => ({
                  value: country.id,
                  label: country.name
                }))}
                required
              />

              {/* Address Line 1 */}
              <TextInput
                id="address_line_one"
                name="address_line_one"
                label={tr["address_line_1"]}
                placeholder={tr["address_line_1"]}
                value={data.address_line_one}
                onChange={(e) => setFormValueData("address_line_one", e.target.value)}
                required
              />

              {/* Address Line 2 */}
              <TextInput
                id="address_line_two"
                name="address_line_two"
                label={tr["address_line_2"]}
                placeholder={tr["address_line_2"]}
                value={data.address_line_two}
                onChange={(e) => setFormValueData("address_line_two", e.target.value)}
              />

              {/* City and State */}
              <div className="grid grid-cols-2 gap-4">
                <TextInput
                  id="city"
                  name="city"
                  label={tr["city"]}
                  placeholder={tr["city"]}
                  value={data.city}
                  onChange={(e) => setFormValueData("city", e.target.value)}
                  required
                />
                <TextInput
                  id="state"
                  name="state"
                  label={tr["state"]}
                  placeholder={tr["state"]}
                  value={data.state}
                  onChange={(e) => setFormValueData("state", e.target.value)}
                  required
                />
              </div>

              {/* Zip Code */}
              <TextInput
                id="postal_code"
                name="postal_code"
                label={tr["postal_code"]}
                placeholder={tr["postal_code"]}
                value={data.postal_code}
                onChange={(e) => setFormValueData("postal_code", e.target.value)}
              />
            </div>

            {/* Bottom Buttons */}
            <div className="flex gap-3 sm:gap-10 mt-3 sm:mt-8 ">
              <Button
                type="button"
                variant="primary"
                fullWidth
                onClick={handleSubmit}
              >
                {tr["save"]}
              </Button>
              <Button
                type="button"
                onClick={handleCancel}
                variant="outline"
                fullWidth
              >
                {tr["cancel"]}
              </Button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
}
