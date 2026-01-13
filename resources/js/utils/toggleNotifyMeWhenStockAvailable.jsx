import { router } from "@inertiajs/react";

export default function toggleNotifyMeWhenStockAvailable(productId) {
  return router.visit(route("toggle-stock-notify", productId) , {
    method: "get",
    preserveScroll: true,
  });
}
