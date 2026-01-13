import React from "react";
import Modal1 from "./ModalContent/Modal1";

const Modal = ({ children, onClose, isOpen,status, title, description, type, modalClassName = "" ,withoutHeaderBg=false}) => {
  if (!isOpen) return null;

  return (
    <div
      onClick={onClose}
      className="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-xs font-inter">
      <Modal1
        onClose={onClose}
        status={status}
        title={title}
        description={description}
        type={type}
        modalClassName={modalClassName}
        children={children}
        withoutHeaderBg={withoutHeaderBg}
      />
    </div>
  );
};

export default Modal;
