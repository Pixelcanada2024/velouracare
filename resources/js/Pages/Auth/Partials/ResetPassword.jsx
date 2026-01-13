import { useState, useEffect } from "react";
import { useForm, usePage } from "@inertiajs/react";
import Button from "@/components/ui/Button";
import PasswordInput from "@/components/ui/PasswordInput";
// start lang
import { useTranslation } from "@/contexts/TranslationContext";
// end lang
export default function ResetPassword({ onChangeStep, email, code }) {

    // Start language
  const [{lang, currency, tr}, _setTranslation] = useTranslation();
    // end lang

  const [isModalOpen, setModalOpen] = useState(false);


  const { data, setData, post, processing, errors, reset } = useForm({
    email: email,
    code: code,
    password: "",
    password_confirmation: "",
  });


  // Handle form submission
  const handleSubmit = (e) => {
    e.preventDefault();

    post(route("reset.password"), {
      onSuccess: () => {
        setModalOpen(true);
        reset();
      },
      onError: (errors) => {
        console.error("Password reset failed:", errors);
      }
    });
  };


  return (
    <div className="w-full max-w-md mx-auto min-h-[400px] flex flex-col">
      <div className="flex items-center mb-6">
        <h2 className="flex-grow text-lg font-medium text-center sm:text-2xl text-primary-500">
          {tr['reset_your_password']}
        </h2>
      </div>

      <div className="mb-6 text-center">
        <p className="text-sm text-gray-600 sm:text-base">
          {tr['reset_your_password_desc']}
        </p>
      </div>


      <form
        onSubmit={handleSubmit}
        className="flex flex-col flex-grow">
        <PasswordInput
          id="password"
          label={tr['new_password']}
          value={data.password}
          onChange={(e) => setData("password", e.target.value)}
          placeholder={tr['write_new_password']}
          error={errors.password}
          required
        />


        <PasswordInput
          id="password_confirmation"
          label={tr['confirm_password']}
          value={data.password_confirmation}
          onChange={(e) => setData("password_confirmation", e.target.value)}
          placeholder={tr['write_confirm_new_password']}
          error={errors.password_confirmation}
          required
        />

        <div className="mt-10 space-y-4">
          <Button
            type="submit"
            variant="primary"
            fullWidth
            disabled={processing}
          >
            {processing ? tr['updating_password'] : tr['reset_password']}
          </Button>
          <Button
            variant="outline"
            fullWidth
            href={route("react.login")}
            disabled={processing}
          >
            {tr['go_back']}
          </Button>
        </div>
      </form>

    </div>
  );
}
