import Layout from "@/components/layout/Layout";
import TextInput from "@/components/ui/TextInput";
import PasswordInput from "@/components/ui/PasswordInput";
import Button from "@/components/ui/Button";
import { useEffect, useState } from "react";
import { Link, useForm, usePage } from "@inertiajs/react";

import Modal from "@/components/ui/Modal";

// start lang
import { useTranslation } from "@/contexts/TranslationContext";
// end lang
export default function Login() {
  // Start language
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();

  // end lang
  // Setup form with Inertia
  const { data, setData, post, processing } = useForm({
    email: "",
    password: "",
    remember: false,
  });

  const errors = usePage().props.errors;
  const { flash } = usePage().props;
  const { csrfToken } = usePage().props;
  const { contact_info: { contact_email, contact_phone } } = usePage().props;

  // Login Modal
  const [isRejectedModalOpen, setRejectedModalOpen] = useState(false);
  const [isPendingModalOpen, setPendingModalOpen] = useState(false);

  // Set up modals based on flash messages
  useEffect(() => {
    // Check for flash messages from server to show modals
    if (flash && flash.status === 'rejected') {
      setRejectedModalOpen(true);
    } else if (flash && flash.status === 'pending') {
      setPendingModalOpen(true);
    }
  }, [flash]);

  const isSkyAmerica = usePage().props.isSkyAmerica;

  return (
    <Layout ShowFooterSwiper={false} pageTitle={tr['login']}>
      <div className="max-w-2xl mx-5 sm:mx-auto px-6 sm:px-14 py-10 rounded-2xl border  border-[#E5E7EB] bg-white shadow-lg my-20">

        <div className="flex flex-col items-center gap-2 mb-8">
          <div className="flex text-xs sm:text-base items-center gap-2 font-semibold">{tr['login_to_access_all_features']}</div>
          <h1 className="text-[22px] sm:text-[32px] font-bold">{tr['login']}</h1>
        </div>

        <form method="post" action={route('login')}>
          <input
            type="hidden"
            name="_token"
            value={csrfToken} />

          <div className="space-y-4">
            <TextInput
              id="email"
              name="email"
              type="email"
              label={tr['your_email']}
              placeholder={tr['write_your_email']}
              value={data.email}
              onChange={(e) =>
                setData("email", e.target.value)
              }
              error={errors.email}

              required
            />

            <PasswordInput
              id="password"
              name="password"
              label={tr['your_password']}
              placeholder={tr['write_your_password']}
              value={data.password}
              onChange={(e) =>
                setData("password", e.target.value)
              }
              error={errors.password}
              required
            />
          </div>

          <div className="flex  gap-3 mb-6  justify-between">
            <label className="inline-flex items-center">
              <input
                type="checkbox"
                name="remember"
                className="accent-primary-500 "
                checked={data.remember}
                onChange={(e) =>
                  setData(
                    "remember",
                    e.target.checked
                  )
                }

              />
              <span className="mx-2 text-sm text-primary-300">
                {tr['remember_me']}
              </span>
            </label>
            <Link
              href={route("react.forgot-password")}
              className="text-sm text-blue-400 underline underline-offset-4"
            >
              {tr['forgot_password']}
            </Link>
          </div>
          <div className="flex flex-col gap-[16px] mt-12">
            <Button
              type="submit"
              variant="primary"
              className='w-full sm:min-w-[250px]'
            >
              {tr['login']}
            </Button>
            <Button
              href={route("react.register")}
              type="button"
              variant="outline"
              className='w-full sm:min-w-[250px]'
            >
              {tr['sign_up']}
            </Button>

          </div>
        </form>
      </div>
      {/* Rejected Modal */}
      <Modal
        isOpen={isRejectedModalOpen}
        onClose={() => setRejectedModalOpen(false)}
        status="rejected"
        title={tr['rejected_account_modal_title']}
      >
        <div className="">
          {tr['rejected_account_modal_desc']}<br />
          <span className="text-[#0088FF]">{contact_email}</span>
        </div>
      </Modal>

      {/* Pending Modal */}
      <Modal
        isOpen={isPendingModalOpen}
        onClose={() => setPendingModalOpen(false)}
        status="pending"
        title={tr['pending_account_modal_title']}
      >
        <div className="">
          {tr['pending_account_modal_desc']}<br />
          <span className="text-[#0088FF]">{contact_email}</span>
        </div>
      </Modal>
    </Layout>
  );
}
