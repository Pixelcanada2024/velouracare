import TextInput from "@/components/ui/TextInput";
import BusinessTypeCard from '@/components/ui/BusinessTypeCard';
import report_1 from '/public/website-assets/register/report_1.png';
import report_2 from '/public/website-assets/register/report_2.png';
import report_3 from '/public/website-assets/register/report_3.png';
import DragFileInput from '../../../components/ui/DragFileInput';
// start lang
import { useTranslation } from "@/contexts/TranslationContext";
// end lang

export default function BusinessInfo({ data, setData, errorsReactive }) {

  // Start language
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();

  // end lang
  const errors = errorsReactive;

  const OfflineIcon = () => (
    <svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M3.50073 11.6666L10.8507 4.31659C11.1608 4.00464 11.5296 3.75715 11.9358 3.58838C12.342 3.41961 12.7775 3.3329 13.2174 3.33325H27.1174C27.5573 3.3329 27.9928 3.41961 28.399 3.58838C28.8052 3.75715 29.174 4.00464 29.4841 4.31659L36.8341 11.6666M6.83407 19.9999V33.3333C6.83407 34.2173 7.18526 35.0652 7.81038 35.6903C8.4355 36.3154 9.28334 36.6666 10.1674 36.6666H30.1674C31.0515 36.6666 31.8993 36.3154 32.5244 35.6903C33.1495 35.0652 33.5007 34.2173 33.5007 33.3333V19.9999" stroke="currentColor" strokeWidth="1.66667" strokeLinecap="round" strokeLinejoin="round" />
      <path d="M25.1674 36.6665V29.9998C25.1674 29.1158 24.8162 28.2679 24.1911 27.6428C23.566 27.0177 22.7181 26.6665 21.8341 26.6665H18.5007C17.6167 26.6665 16.7688 27.0177 16.1437 27.6428C15.5186 28.2679 15.1674 29.1158 15.1674 29.9998V36.6665M3.50073 11.6665H36.8341V16.6665C36.8341 17.5506 36.4829 18.3984 35.8578 19.0235C35.2326 19.6486 34.3848 19.9998 33.5007 19.9998C32.527 19.9462 31.597 19.5777 30.8507 18.9498C30.6519 18.8061 30.4128 18.7288 30.1674 18.7288C29.922 18.7288 29.6829 18.8061 29.4841 18.9498C28.7379 19.5777 27.8078 19.9462 26.8341 19.9998C25.8603 19.9462 24.9303 19.5777 24.1841 18.9498C23.9852 18.8061 23.7461 18.7288 23.5007 18.7288C23.2554 18.7288 23.0163 18.8061 22.8174 18.9498C22.0712 19.5777 21.1412 19.9462 20.1674 19.9998C19.1936 19.9462 18.2636 19.5777 17.5174 18.9498C17.3185 18.8061 17.0794 18.7288 16.8341 18.7288C16.5887 18.7288 16.3496 18.8061 16.1507 18.9498C15.4045 19.5777 14.4745 19.9462 13.5007 19.9998C12.527 19.9462 11.5969 19.5777 10.8507 18.9498C10.6519 18.8061 10.4128 18.7288 10.1674 18.7288C9.92204 18.7288 9.68293 18.8061 9.48407 18.9498C8.73785 19.5777 7.80784 19.9462 6.83407 19.9998C5.95001 19.9998 5.10216 19.6486 4.47704 19.0235C3.85192 18.3984 3.50073 17.5506 3.50073 16.6665V11.6665Z" stroke="currentColor" strokeWidth="1.66667" strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );

  const OnlineIcon = () => (
    <svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M15.2613 35.7142H25.7375M20.4994 35.7142V30.4761M7.57881 4.28564H33.4201C33.4201 4.28564 36.2137 4.28564 36.2137 7.0793V27.6825C36.2137 27.6825 36.2137 30.4761 33.4201 30.4761H7.57881C7.57881 30.4761 4.78516 30.4761 4.78516 27.6825V7.0793C4.78516 7.0793 4.78516 4.28564 7.57881 4.28564Z" stroke="currentColor" strokeWidth="1.42857" strokeLinecap="round" strokeLinejoin="round" />
      <path d="M19.5628 8.94532L13.2771 12.0882C12.9293 12.2622 12.6367 12.5295 12.4322 12.8604C12.2277 13.1912 12.1193 13.5724 12.1191 13.9613V20.8058C12.119 21.1949 12.2273 21.5764 12.4318 21.9075C12.6364 22.2386 12.929 22.5062 13.2771 22.6803L19.5628 25.8232C19.8538 25.9687 20.1747 26.0445 20.5001 26.0445C20.8255 26.0445 21.1464 25.9687 21.4374 25.8232L27.7231 22.6803C28.072 22.5058 28.3652 22.2373 28.5698 21.9052C28.7744 21.573 28.8822 21.1903 28.881 20.8002V13.9557C28.8809 13.5668 28.7725 13.1856 28.568 12.8548C28.3635 12.5239 28.0709 12.2566 27.7231 12.0826L21.4374 8.93973C21.1459 8.79505 20.8248 8.72023 20.4994 8.7212C20.1741 8.72217 19.8534 8.7989 19.5628 8.94532Z" stroke="currentColor" strokeWidth="1.42857" strokeLinecap="round" strokeLinejoin="round" />
      <path d="M28.5502 12.8328L20.5017 16.8556L12.4336 12.8621M20.5017 16.857V26.0384" stroke="currentColor" strokeWidth="1.42857" strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  )

  const BothIcon = () => (
    <svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M20.8319 36C22.5719 36 24.2519 35.72 25.8199 35.208C25.0435 34.2025 24.3895 33.1082 23.8719 31.948C22.8799 33.35 21.8039 34 20.8319 34C19.5179 34 18.0179 32.82 16.7879 30.184C16.3202 29.1587 15.9467 28.093 15.6719 27H22.8399C22.8653 26.3147 22.9566 25.648 23.1139 25H15.2439C14.9672 23.3478 14.8294 21.6753 14.8319 20C14.8319 18.234 14.9779 16.55 15.2439 15H26.4199C26.6599 16.408 26.8039 17.926 26.8279 19.516C27.4319 19.1027 28.0893 18.7693 28.7999 18.516C28.7468 17.3384 28.6286 16.1646 28.4459 15H33.9119C34.3319 16.098 34.6179 17.262 34.7499 18.472C35.4993 18.7293 36.1906 19.074 36.8239 19.506C36.7269 16.364 35.7064 13.3202 33.89 10.7547C32.0736 8.18911 29.5417 6.21543 26.6104 5.08007C23.6791 3.94471 20.4784 3.69796 17.4077 4.37063C14.337 5.04331 11.5326 6.6056 9.34439 8.86245C7.15622 11.1193 5.68133 13.9707 5.10385 17.0607C4.52638 20.1507 4.8719 23.3423 6.0973 26.2371C7.32269 29.1319 9.37365 31.6016 11.9941 33.3379C14.6146 35.0742 17.6884 36.0001 20.8319 36ZM20.8319 6C22.1459 6 23.6459 7.18 24.8759 9.816C25.3099 10.748 25.6879 11.82 25.9939 13H15.6699C15.9759 11.82 16.3539 10.748 16.7899 9.816C18.0159 7.18 19.5159 6 20.8319 6ZM14.9759 8.97001C14.3853 10.2636 13.9276 11.6139 13.6099 13H8.70392C10.3921 10.0815 13.0742 7.86799 16.2599 6.764C15.7779 7.424 15.3479 8.17201 14.9759 8.97001ZM13.2159 15C12.9573 16.6541 12.8289 18.3259 12.8319 20C12.8319 21.74 12.9659 23.424 13.2179 25H7.75192C7.14267 23.4035 6.83087 21.7089 6.83192 20C6.83192 18.24 7.15792 16.552 7.75192 15H13.2159ZM13.6099 27C13.9619 28.486 14.4239 29.844 14.9759 31.03C15.3479 31.828 15.7779 32.576 16.2599 33.236C13.0742 32.132 10.3921 29.9185 8.70392 27H13.6099ZM25.4039 6.764C28.5896 7.86799 31.2717 10.0815 32.9599 13H28.0519C27.7342 11.6139 27.2765 10.2636 26.6859 8.97001C26.3302 8.19492 25.9002 7.45613 25.4019 6.764M38.8319 27.364C38.8319 23.298 35.9019 20.002 31.8339 20.002C27.7659 20.002 24.8319 23.298 24.8319 27.364C24.8319 30.412 26.7959 34.424 31.3439 37.836C31.6339 38.056 32.0339 38.056 32.3239 37.836C36.8759 34.424 38.8319 30.412 38.8319 27.364ZM34.8319 27C34.8319 27.7957 34.5158 28.5587 33.9532 29.1213C33.3906 29.6839 32.6276 30 31.8319 30C31.0363 30 30.2732 29.6839 29.7106 29.1213C29.148 28.5587 28.8319 27.7957 28.8319 27C28.8319 26.2044 29.148 25.4413 29.7106 24.8787C30.2732 24.3161 31.0363 24 31.8319 24C32.6276 24 33.3906 24.3161 33.9532 24.8787C34.5158 25.4413 34.8319 26.2044 34.8319 27Z" fill="currentColor" />
    </svg>

  )

  const handleBusinessTypeChange = (type) => {
    setData("business_type", type);
  };

  const handleFindUsChange = (e) => {
    setData("find_us", e.target.value);
  };

  return (
    <>
      <div className="flex flex-col gap-4">
        <TextInput
          id="company_name"
          name="company_name"
          type="text"
          label={tr['company_name']}
          placeholder={tr['enter_company_name']}
          value={data.company_name}
          onChange={(e) => setData("company_name", e.target.value)}
          error={errors.company_name}
          required
          helperText={tr['enter_n_a']}
        />

        <div className="mb-6">
          <p className="mb-3 text-primary-300 font-medium">{tr['business_type']}:</p>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
            <BusinessTypeCard
              value="0"
              label={tr['offline'].toUpperCase()}
              description={tr['offline_type_desc']}
              icon={<OfflineIcon />}
              selected={data.business_type == '0'}
              onChange={handleBusinessTypeChange}
            />
            <BusinessTypeCard
              value="1"
              label={tr['online'].toUpperCase()}
              description={tr['online_type_desc']}
              icon={<OnlineIcon />}
              selected={data.business_type == '1'}
              onChange={handleBusinessTypeChange}
            />
            <BusinessTypeCard
              value="2"
              label={tr['both'].toUpperCase()}
              description={tr['both_type_desc']}
              icon={<BothIcon />}
              selected={data.business_type == '2'}
              onChange={handleBusinessTypeChange}
            />
          </div>
        </div>

        <TextInput
          id="store_link"
          name="store_link"
          type="text"
          label={tr['store_link']}
          placeholder={tr['enter_store_link']}
          value={data.store_link}
          onChange={(e) => setData("store_link", e.target.value)}
          error={errors.store_link}
          required
          helperText={tr['enter_store_help_text']}
        />

        <TextInput
          id="business_id"
          name="business_id"
          type="text"
          label={tr['business_id']}
          value={data.business_id}
          onChange={(e) => setData("business_id", e.target.value)}
          error={errors.business_id}
          required
          helperText={tr['business_proof_help_text']}
        />

        <div>
          <h3 className="mb-2 text-primary-300">{tr['business_proof']} <span className="text-error">*</span></h3>
          <div className='bg-[#F2F4F7] p-6'>
            <h3 className="font-semibold text-xl pb-4">{tr['examples_of_documents_accepted']}</h3>
            <p>{tr['examples_of_documents_accepted_desc']}</p>
            <div className="mt-4 grid grid-cols-3 gap-4">
              <img src={report_3} alt="" />
              <img src={report_1} alt="" />
              <img src={report_2} alt="" />
            </div>
          </div>
        </div>

        <DragFileInput
          id="business_proof_assets"
          name="business_proof_assets"
          value={data.business_proof_assets}
          onChange={(files) => setData("business_proof_assets", files)}
          error={errors.business_proof_assets}
          multiple={true}
          maxFiles={6}
          accept=".jpg,.jpeg,.png,.webp,.pdf"
          required
        />

        <div>
          <h3 className="text-lg font-medium text-gray-900 mb-4">{tr['how_did_you_find_us']}</h3>

          <div className="space-y-3">
            <label htmlFor="findUs_0" className="flex items-center cursor-pointer">
              <input
                type="radio"
                id="findUs_0"
                name="findUs"
                value="0"
                checked={data.find_us == "0"}
                onChange={handleFindUsChange}
                className="h-4 w-4 border-gray-300 accent-primary-500 bg-amber-50"
              />
              <span className="mx-3 text-black">{tr['find_us_search']}</span>
            </label>

            <label htmlFor="findUs_1" className="flex items-center cursor-pointer">
              <input
                type="radio"
                id="findUs_1"
                name="findUs"
                value="1"
                checked={data.find_us == "1"}
                onChange={handleFindUsChange}
                className="h-4 w-4 border-gray-300 accent-primary-500"
              />
              <span className="mx-3 text-black">{tr['find_us_social']}</span>
            </label>

            <label htmlFor="findUs_2" className="flex items-center cursor-pointer">
              <input
                type="radio"
                id="findUs_2"
                name="findUs"
                value="2"
                checked={data.find_us == "2"}
                onChange={handleFindUsChange}
                className="h-4 w-4 border-gray-300 accent-primary-500"
              />
              <span className="mx-3 text-black">{tr['find_us_webinar']}</span>
            </label>

            <label htmlFor="findUs_3" className="flex items-center cursor-pointer">
              <input
                type="radio"
                id="findUs_3"
                name="findUs"
                value="3"
                checked={data.find_us == "3"}
                onChange={handleFindUsChange}
                className="h-4 w-4 border-gray-300 accent-primary-500"
              />
              <span className="mx-3 text-black">{tr['find_us_trade']}</span>
            </label>

            <label htmlFor="findUs_4" className="flex items-center cursor-pointer">
              <input
                type="radio"
                id="findUs_4"
                name="findUs"
                value="4"
                checked={data.find_us == "4"}
                onChange={handleFindUsChange}
                className="h-4 w-4 border-gray-300 accent-primary-500"
              />
              <span className="mx-3 text-black">{tr['find_us_others']}</span>
            </label>

            {errors.find_us && <div className="text-sm text-error">{errors.find_us}</div>}
          </div>

        </div>

        <div>
          <div className='bg-[#F2F4F7] p-6 text-black'>
            <p>{tr['by_registering_1']}</p>
            <p>{tr['by_registering_2']}</p>
            <p>{tr['by_registering_3']}</p>

            <div className="flex items-center gap-4 mt-4">
              <label className="relative inline-flex items-center cursor-pointer">
                <input
                  id="wholesale_agree"
                  type="checkbox"
                  name="wholesale_agree"
                  checked={data.wholesale_agree}
                  onChange={(e) => setData("wholesale_agree", e.target.checked)}
                  required
                  className={
                    "peer w-4 h-4 appearance-none border rounded-sm transition-all " +
                    (errors.wholesale_agree
                      ? "border-red-500 bg-red-50"
                      : "border-gray-300 hover:border-primary-400") +
                    " checked:bg-primary-500 checked:border-primary-500"
                  }
                />
                {/* ✅ Custom check icon */}
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


              <label
                htmlFor="wholesale_agree"
                className="text-black"
              >
                {tr['wholesale_terms']}
              </label>
            </div>
          </div>
        </div>
      </div>
    </>
  )
}
