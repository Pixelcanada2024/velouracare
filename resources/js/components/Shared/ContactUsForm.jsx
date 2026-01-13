import React from 'react'
import TextInput from "@/components/ui/TextInput";
import TextAreaInput from "@/components/ui/TextAreaInput";
import Button from "@/components/ui/Button";
import { useForm, usePage } from '@inertiajs/react';
// start lang
import { useTranslation } from '@/contexts/TranslationContext';
// end lang
export default function ContactUsForm({ page }) {
  // Start language
  const [{lang, currency, tr}, _setTranslation] = useTranslation();
  // end lang
  const { data, setData, post, processing } = useForm({
    name: "",
    company_name: "",
    email: "",
    phone: "",
    content: "",
  });

  const errors = usePage().props.errors;

  const handleSubmit = (e) => {
    e.preventDefault();
    post(route("contact-us"), {
    });
  };

  return (
    <div className="relative border border-white rounded-lg overflow-hidden bg-white/10 backdrop-blur-lg shadow-md p-4 sm:p-6 xl:p-12 max-h-230">
      <div className=" rounded-lg">
        {page === 'home' && (<>
          <div className="flex items-center gap-2 font-semibold"><div className="bg-[#004AAD] w-3 h-[2px]"></div>{tr['get_in_touch']}</div>
          <h2 style={{ fontFamily: 'Times New Roman' }} className="text-[22px] sm:text-[28px] xl:text-[34px] font-bold mt-2">{tr['contact_us']}</h2>
        </>)}
        {page === 'contact-us' && (<>
          <h2 style={{ fontFamily: 'Times New Roman' }} className="text-[22px] sm:text-[28px] xl:text-[34px] font-bold">{tr['send_us_message']}</h2>
        </>)}

        <h4 className="font-bold text-lg mb-4"></h4>
        <form onSubmit={handleSubmit}>
          <TextInput
            id="name"
            name="name"
            type="text"
            label={tr['your_name']}
            placeholder={tr['enter_your_name']}
            required
            onChange={(e) => setData("name", e.target.value)}
            value={data.name}
            error={errors.name}
          />
          <TextInput
            id="company"
            name="company"
            type="text"
            label={tr['company']}
            placeholder={tr['enter_company_name']}
            required
            onChange={(e) => setData("company_name", e.target.value)}
            value={data.company_name}
            error={errors.company_name}
          />
          <TextInput
            id="email"
            name="email"
            type="email"
            label={tr['email_address']}
            placeholder="mail@domain.com"
            required
            onChange={(e) => setData("email", e.target.value)}
            value={data.email}
            error={errors.email}
          />
          <TextInput
            id="phone"
            name="phone"
            type="text"
            label={tr['phone_number']}
            placeholder="+966 5123456789"
            required
            onChange={(e) => setData("phone", e.target.value)}
            value={data.phone}
            error={errors.phone}
          />
          <TextAreaInput
            id="content"
            name="content"
            type="text"
            label={tr['message']}
            placeholder={tr['write_message_here']}
            required
            onChange={(e) => setData("content", e.target.value)}
            value={data.content}
            error={errors.content}
          />
          <Button
            type="submit"
            variant="primary"
            fullWidth
            className="sm:mt-10"
          >
            {tr['submit_contact_message']}
          </Button>
        </form>
      </div>
    </div>
  )
}
