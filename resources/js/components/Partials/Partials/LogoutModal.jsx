import Modal from '@/components/ui/Modal'
import React from 'react'
// start lang
import ar from "@/translation/ar"
import en from "@/translation/en"
import { usePage } from '@inertiajs/react'
import Button from '@/components/ui/Button'
// end lang
export default function LogoutModal({ isModalOpen, setModalOpen, toggleModal }) {
  // Start language
  const lang = usePage().props.locale
  const tr = lang === "ar" ? ar : en
  const { csrfToken } = usePage().props;

  return (
    <Modal
      isOpen={isModalOpen}
      onClose={() => setModalOpen(false)}
      title={tr["logout"]}
      description={tr["are_you_sure_you_want_to_logout_from_this_account"]}
    >
      <form method="POST" action={route("logout")} >

        <input
          type="hidden"
          name="_token"
          value={csrfToken}
        />
        <div className="flex w-full justify-center  gap-6 sm:gap-12 xl:gap-16">
          <Button
            variant="primary"
            size="md"
            type='submit'
            className='min-w-36'
          >
            {tr["yes"]}
          </Button>
          <Button
            variant="outline"
            size="md"
            onClick={toggleModal}
            className='min-w-36'

          >
            {tr["cancel"]}
          </Button>
        </div>

      </form>

    </Modal>
  )
}

