import Layout from "@/components/layout/Layout";
import Steps from "./RegisterPartials/Steps";
import PersonalInfo from "./RegisterPartials/PersonalInfo";
import BusinessInfo from "./RegisterPartials/BusinessInfo";
import { useForm, usePage } from "@inertiajs/react";
import { useState } from "react";
import Modal from "@/components/ui/Modal";
import Button from "@/components/ui/Button";

// start lang
import { useTranslation } from "@/contexts/TranslationContext";
// end lang

export default function Register({ countryData }) {
  // Start language
  const [{lang, currency, tr}, _setTranslation] = useTranslation();

  // end lang

  const defaultCountryCode = usePage().props.preferences?.country?.toUpperCase() || (isSkyAmerica ? 'US' : 'SA');
  const defaultCountryId = countryData.filter(item => item.code === defaultCountryCode).map(item => item.value)[0] || countryData[0]?.value || "";

  const { data, setData, post, processing, errors, setError, clearErrors, reset } = useForm({
    name: "",
    email: "",
    phone: "",
    password: "",

    address_line_one: "",
    address_line_two: "",
    country_id: defaultCountryId || "",
    state: "",
    city: "",
    postal_code: "",
    agree: false,

    // Business Info fields
    company_name: "",
    store_link: "",
    business_id: "",
    business_proof_assets: [],
    business_type: "offline",
    find_us: "",
    wholesale_agree: false,
  });

  // Step management state
  const [currentStep, setCurrentStep] = useState(1);


  const steps = [
    {
      title: tr['contact_information'],
      component: <PersonalInfo countryData={countryData} data={data} setData={setData} errorsReactive={errors} />,
    },
    {
      title: tr['business_information'],
      component: <BusinessInfo data={data} setData={setData} errorsReactive={errors} />,
    },
  ];

  const [isModalOpen, setModalOpen] = useState(false);
  const handleSubmit = (e) => {
    e.preventDefault();
    post(route("register"), {
      onSuccess: () => {
        setModalOpen(true);
        reset()
      }
    });
  };


  return (
    <Layout ShowFooterSwiper={false} pageTitle={tr['sign_up']}>
      <div className="max-w-2xl mx-5 sm:mx-auto px-6 sm:px-14 py-10 rounded-2xl border  border-[#E5E7EB] bg-white shadow-lg my-20">
        <div className="flex flex-col items-center gap-2 mb-8">
          <div className="flex text-xs sm:text-base items-center gap-2 font-semibold">{tr['register_for_a_wholesale_account']}</div>
          <h1 className="text-[22px] sm:text-[32px] text-center">{tr['let_s_create_your_account']}</h1>
        </div>
        <form onSubmit={handleSubmit}>
          <Steps
            steps={steps}
            currentStep={currentStep}
            setCurrentStep={setCurrentStep}
            processing={processing}
            data={data}
            setError={setError}
            clearErrors={clearErrors}
            errorsReactive={errors}
          />
        </form>

      </div>

      <Modal
        isOpen={isModalOpen}
        onClose={() => setModalOpen(false)}
        TitleBg={true}
        title={tr['thank_you_for_applying']}
        description={tr['register_complete']}

      ><div className="flex items-center gap-6  xl:gap-8">
          <Button
            variant="primary"
            size="md"
            fullWidth
            href={route('login')}
          >
            {tr['ok']}
          </Button>
          <Button
            variant="outline"
            size="md"
            fullWidth
            href={route('home')}
          >
            {tr['back_to_home_page']}
          </Button>
        </div>
      </Modal>
    </Layout>
  );
}
