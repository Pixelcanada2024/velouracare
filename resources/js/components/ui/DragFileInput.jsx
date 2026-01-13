import React, { useState, useRef, useEffect } from 'react';
// start lang
import ar from '@/translation/ar'
import en from '@/translation/en'
import { usePage } from '@inertiajs/react'
// end lang
export default function DragFileInput({
  id,
  label,
  onChange,
  accept,
  error,
  required = false,
  valid = false,
  className = '',
  helperText = '',
  value = null,
  keyValue = null,
  disabled = false,
  multiple = false,
  maxFiles = 5,
  ...rest
}) {
  // Start language
  const lang = usePage().props.locale;
  const tr = lang === 'ar' ? ar : en;
  // end lang

  const fileInputRef = useRef(null);
  const [selectedFileValue, setSelectedFileValue] = useState(keyValue?.name ?? null);
  const [selectedFiles, setSelectedFiles] = useState(multiple ? (value || []) : null);
  const [isDragOver, setIsDragOver] = useState(false);

  // Clear the input ref when value is null or empty
  useEffect(() => {
    if (multiple) {
      if (!value?.length && fileInputRef.current) {
        fileInputRef.current.value = '';
      }
    } else {
      if (!value && fileInputRef.current) {
        fileInputRef.current.value = '';
      }
    }
  }, [value, multiple]);

  useEffect(() => {
    if (multiple) {
      setSelectedFiles(value || []);
    } else {
      setSelectedFileValue(keyValue?.name ?? null);
    }
  }, [value, keyValue, multiple]);

  // Determine input state for styling
  const hasError = !!error;
  const isValid = valid && !hasError;

  const handleFileChange = (e) => {
    if (disabled) { return 0 }

    if (multiple) {
      const files = Array.from(e.target.files);
      handleFiles(files);
    } else {
      const file = e.target.files[0];
      if (file) {
        setSelectedFileValue(file.name);
        if (onChange) {
          onChange(file);
        }
      }
    }
  };

  const handleFiles = (files) => {
    if (!multiple || files.length === 0) return;

    const newFiles = [...selectedFiles];

    files.forEach(file => {
      if (newFiles.length < maxFiles) {
        newFiles.push(file);
      }
    });

    setSelectedFiles(newFiles);
    if (onChange) {
      onChange(newFiles);
    }
  };

  const removeFile = (index) => {
    if (disabled || !multiple) return;
    const newFiles = selectedFiles.filter((_, i) => i !== index);
    setSelectedFiles(newFiles);
    if (onChange) {
      onChange(newFiles);
    }
  };

  const handleDragOver = (e) => {
    if (disabled) { return 0 }
    e.preventDefault();
    setIsDragOver(true);
  };

  const handleDragLeave = (e) => {
    e.preventDefault();
    setIsDragOver(false);
  };

  const handleDrop = (e) => {
    if (disabled) { return 0 }

    e.preventDefault();
    setIsDragOver(false);

    const files = Array.from(e.dataTransfer.files);

    if (multiple) {
      handleFiles(files);
    } else {
      if (files.length > 0) {
        const file = files[0];
        setSelectedFileValue(file.name);
        if (onChange) {
          onChange(file);
        }
      }
    }
  };

  const handleChooseFile = () => {
    if (disabled) return;
    fileInputRef.current?.click();
  };

  useEffect(() => {
    if (!multiple) {
      setSelectedFileValue(keyValue?.name ?? null);
    }
  }, [keyValue, multiple]);

  // Container classes based on state
  const containerClasses = `
  ${disabled ? 'bg-gray-100 cursor-not-allowed' : 'bg-white cursor-pointer'}
    relative flex flex-col items-center justify-center p-8 border-2 border-[#CECECE] rounded-lg  transition-colors
    ${isDragOver ? 'border-blue-400 bg-blue-50' :
      hasError ? 'border-red-300 hover:border-red-400' :
        (isValid ? 'border-green-300 hover:border-green-400' :
          'border-gray-300 hover:border-gray-400')}
  `;

  return (
    <div className={`mb-4 ${className}`}>
      {label && (
        <label htmlFor={id} className="block mb-2 text-black">
          {label} {required && <span className="text-red-500">*</span>}
        </label>
      )}

      <div className="relative">
        <div
          className={containerClasses}
          onDragOver={handleDragOver}
          onDragLeave={handleDragLeave}
          onDrop={handleDrop}
          onClick={handleChooseFile}
        >
          <input
            ref={fileInputRef}
            id={id}
            type="file"
            accept={accept}
            onChange={handleFileChange}
            className="hidden"
            disabled={disabled}
            multiple={multiple}
            {...rest}
          />

          <div className="mb-4">
            <div>
              <svg width="51" height="51" viewBox="0 0 51 51" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.6639 21.6903C8.07747 22.7809 4.66602 26.9048 4.66602 31.8246C4.66602 37.5778 9.32956 42.2413 15.0827 42.2413C16.0691 42.2413 17.0243 42.1038 17.9296 41.8475M38.0566 21.6903C42.6431 22.7809 46.0535 26.9048 46.0535 31.8246C46.0535 37.5778 41.39 42.2413 35.6368 42.2413C34.6504 42.2413 33.6952 42.1038 32.791 41.8475M37.9994 21.408C37.9994 14.5048 32.4025 8.90796 25.4994 8.90796C18.5962 8.90796 12.9993 14.5048 12.9993 21.408M18.2754 29.6173L25.4994 22.3705L32.9285 29.7413M25.4994 40.158V26.0559" stroke="#222222" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
              </svg>
            </div>
          </div>

          {/* Text Content */}
          <div className="text-center">
            {multiple ? (
              <div className="space-y-2">
                <p className="text-sm text-black">
                  {tr['drag_drop_files']}{' '}
                  <span className="underline">{tr['choose_files']}</span>
                </p>
                <p className="text-xs text-black">
                  {tr['you_can_upload']} {maxFiles} {tr['files']}
                </p>
                {accept && (
                  <p className="text-xs text-black">
                    {tr['supported_formats']} {accept}
                  </p>
                )}
              </div>
            ) : selectedFileValue ? (
              <div className="space-y-2">
                <p className="text-sm font-medium text-gray-900">
                  {tr['selected']}: {selectedFileValue}
                </p>
                <p className="text-xs text-gray-500">
                  {tr['click_to_change']}
                </p>
              </div>
            ) : (
              <div className="space-y-2">
                <p className="text-sm text-black">
                  {tr['drag_drop_file']}{' '}
                  <span className="underline">{tr['choose_file']}</span>
                </p>
                {accept && (
                  <p className="text-xs text-black">
                    {tr['supported_formats']} {accept}
                  </p>
                )}
              </div>
            )}
          </div>
        </div>

        {/* Status icon for validation states */}
        {isValid && (
          <div className="absolute transform -translate-y-1/2 right-3 top-1/2">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M8 0C3.58862 0 0 3.58862 0 8C0 12.4114 3.58862 16 8 16C12.4114 16 16 12.4114 16 8C16 3.58862 12.4114 0 8 0ZM11.8047 6.4L7.59864 10.6061C7.55178 10.6529 7.49695 10.69 7.43693 10.7154C7.37691 10.7407 7.31287 10.7538 7.24819 10.7538C7.18351 10.7538 7.11947 10.7407 7.05945 10.7154C6.99944 10.69 6.9446 10.6529 6.89775 10.6061L4.19526 7.90358C4.09809 7.80642 4.04362 7.67405 4.04362 7.53585C4.04362 7.39764 4.09809 7.26528 4.19526 7.16811C4.29242 7.07095 4.42479 7.01648 4.56299 7.01648C4.7012 7.01648 4.83356 7.07095 4.93073 7.16811L7.24819 9.48557L11.0693 5.66446C11.1664 5.5673 11.2988 5.51283 11.437 5.51283C11.5752 5.51283 11.7076 5.5673 11.8047 5.66446C11.9019 5.76163 11.9564 5.894 11.9564 6.0322C11.9564 6.1704 11.9019 6.30277 11.8047 6.39993V6.4Z" fill="#10B981" />
            </svg>
          </div>
        )}
      </div>

      {/* Selected files display for multiple mode */}
      {/* Selected files display for multiple mode */}
      {multiple && selectedFiles?.length > 0 && (
        <div className="mt-3 space-y-2">
          <p className="text-sm font-medium text-gray-900">
            {tr['selected_files']} ({selectedFiles.length}/{maxFiles}):
          </p>
          <div className="space-y-1">
            {selectedFiles.map((file, index) => {
              const isImage = file.type.startsWith('image/');
              const isPDF = file.type === 'application/pdf';

              return (
                <div key={index} className="flex items-center justify-between p-2 bg-gray-50 rounded border">
                  <div className="flex items-center space-x-2 flex-1 min-w-0">
                    {isImage ? (
                      <div className="flex-shrink-0 h-8 w-8 rounded overflow-hidden">
                        <img
                          src={URL.createObjectURL(file)}
                          alt={file.name}
                          className="h-full w-full object-cover"
                        />
                      </div>
                    ) : isPDF ? (
                      <div className="flex-shrink-0 h-8 w-8 flex items-center justify-center">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z" stroke="#222222" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
                          <path d="M14 2V8H20" stroke="#222222" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
                          <path d="M16 13H8" stroke="#222222" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
                          <path d="M16 17H8" stroke="#222222" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
                          <path d="M10 9H9H8" stroke="#222222" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
                        </svg>
                      </div>
                    ) : (
                      <div className="flex-shrink-0 h-8 w-8 flex items-center justify-center">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z" stroke="#222222" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
                          <path d="M14 2V8H20" stroke="#222222" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
                        </svg>
                      </div>
                    )}
                    <span className="text-sm text-gray-700 truncate">
                      {file.name}
                    </span>
                  </div>
                  <button
                    type="button"
                    onClick={(e) => {
                      e.stopPropagation();
                      removeFile(index);
                    }}
                    className="ml-2 text-red-500 hover:text-red-700 p-1 cursor-pointer"
                    disabled={disabled}
                  >
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                      <path d="M8 16A8 8 0 1 1 8 0a8 8 0 0 1 0 16zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />
                    </svg>
                  </button>
                </div>
              );
            })}
          </div>
        </div>
      )}

      {/* Helper text */}
      {helperText && !hasError && !isValid && (
        <div className="mt-1 text-sm text-primary-300">
          {helperText}
        </div>
      )}

      {/* Display error message */}
      {hasError && (
        <div className="mt-1 text-sm text-red-600">
          {error}
        </div>
      )}

      {/* Display validation success message */}
      {isValid && !hasError && (
        <div className="mt-1 text-sm text-green-600">
          {multiple
            ? `${selectedFiles?.length || 0} ${tr['files_uploaded_successfully']}`
            : tr['file_uploaded_successfully']
          }
        </div>
      )}
    </div>
  );
}
