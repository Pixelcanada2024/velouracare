import React from "react";
import { Head } from "@inertiajs/react";

export default function Fallback({ message }) {
  return (
    <>
      <Head title="Something went wrong" />

      <div className="min-h-screen flex flex-col items-center justify-center bg-gray-50 px-6 text-center">
        <div className="max-w-md w-full bg-white shadow-lg rounded-2xl p-8 border border-gray-100">
          <div className="text-red-500 text-6xl mb-4">⚠️</div>
          <h1 className="text-2xl font-semibold mb-2 text-gray-800">
            Oops! Something went wrong
          </h1>

          {message && (
            <p className="text-gray-600 text-sm mb-6">
              {message}
            </p>
          )}

          <div className="flex items-center justify-center gap-3">
            <button
              onClick={() => window.location.reload()}
              className="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
            >
              Reload Page
            </button>
            <a
              href="/"
              className="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition"
            >
              Go Home
            </a>
          </div>
        </div>

        <p className="mt-6 text-xs text-gray-400">
          If the problem persists, please contact support.
        </p>
      </div>
    </>
  );
}
