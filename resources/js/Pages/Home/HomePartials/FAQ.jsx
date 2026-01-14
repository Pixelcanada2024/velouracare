import Accordion from "@/components/ui/Accordion";
import AccordionGroup from "@/components/ui/AccordionGroup";
import ContactUsForm from "@/components/Shared/ContactUsForm";
// start lang
import { useTranslation } from "@/contexts/TranslationContext";
// end lang

export default function FAQ({ faqs }) {
  // end lang
  const [{lang, currency, tr}, _setTranslation] = useTranslation();
  // end lang
  return (
    <div
      className={`sm:container relative mx-auto flex max-xl:flex-col xl:gap-16  overflow-hidden ${ faqs.length > 0 && 'min-h-420 max-h-450'} xl:min-h-240 xl:max-h-240 `}>

      <img src="/public/website-assets/home/FAQs.jpg" alt="" className="absolute -z-50 w-full h-full object-cover opacity-15" />
      <div className="absolute -z-40 bg-gray-500 w-full h-full object-cover opacity-15"></div>

      <div className="m-5 mt-8 sm:m-6 xl:my-18 xl:w-1/2">
        <div className="mb-8">
          <div className="flex items-center gap-2 max-xl:text-xs font-semibold"><div className="bg-[#0D0D0D] w-3 h-[2px]"></div>{tr['faqs']}</div>
          <h2 className="text-2xl sm:text-4xl font-bold mt-2">{tr['have_any_questions']}</h2>

          <p className="mt-2">
            {tr['faq_home_section_desc']}
          </p>
        </div>

        <div className="mb-12">
          <AccordionGroup>
            {faqs && faqs.map(faq => (
              <Accordion key={faq.id} title={faq.question}>
                <p>{faq.answer}</p>
              </Accordion>
            ))}
          </AccordionGroup>
        </div>
      </div>

      <div className="xl:w-1/2 m-5 sm:m-6">
        <ContactUsForm page="home" />
      </div>
    </div>
  )
}
