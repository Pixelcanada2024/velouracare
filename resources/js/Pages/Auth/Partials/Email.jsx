// import { Link, useForm, usePage } from "@inertiajs/react";
import { useForm, usePage } from "@inertiajs/react";
import Button from "../../../components/ui/Button";
import TextInput from "../../../components/ui/TextInput";
import { useEffect } from "react";
// start lang
import { useTranslation } from "@/contexts/TranslationContext";
// end lang
export default function Email({ onChangeStep, setUserInput }) {

  // Start language
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();
  // end lang

  const pageFlash = usePage().props.flash

  const { data, setData, post, processing, errors } = useForm({
    email: "",
  });

  // Handle email form submission
  const handleEmailSubmit = (e) => {
    e.preventDefault();


    post(route("password.email"), {
      preserveScroll: true,
    });

  };

  useEffect(() => {
    if (pageFlash.status == 'success') {
      setUserInput((prev) => ({ ...prev, email: data.email }));
      onChangeStep("emailVerification");

    }
  }, [pageFlash]);

  return (
    <div className="w-full  mx-auto min-h-[300px] flex flex-col">
      <div className="flex items-center mb-6">

        <h2 className="flex-grow text-lg font-medium text-center sm:text-2xl text-primary-500">
          {tr['confirm_contact_information']}
        </h2>

      </div>

      <div className="mb-8 text-center">
        <p className="text-sm text-gray-600 sm:text-base " >
          {tr['confirm_contact_information_desc_line_1']}<br />
          {tr['confirm_contact_information_desc_line_2']}
        </p>
      </div>

      <form onSubmit={handleEmailSubmit} className="flex flex-col flex-grow"
      >
        <TextInput
          id="email"
          type="email"
          label={tr["email_address"]}
          value={data.email}
          onChange={(e) => setData("email", e.target.value)}
          placeholder={tr["enter_your_email_address"]}
          error={errors.email}
          required
        />

        {errors.email && (
          <div className="my-5 bg-red-50 p-4 space-y-1">
            <div className="text-sm text-error">
              {tr['validator_email_exists']}
            </div>
          </div>
        )}

        <div className="mt-8 space-y-5">
          <Button
            type="submit"
            variant="primary"
            fullWidth
            disabled={processing}
            className=" uppercase"
          >
            {tr['send_code']}
          </Button>

          <Button
            href={route('react.login')}
            type="submit"
            variant="outline"
            fullWidth
            disabled={processing}
            className=" uppercase"

          >
            {tr['go_back']}
          </Button>
        </div>
      </form>
    </div>
  );
}
