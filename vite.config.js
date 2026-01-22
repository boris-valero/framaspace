import { createAppConfig } from "@nextcloud/vite-config";
import { join, resolve } from "path";

export default createAppConfig(
  {
    // main: resolve(join("src", "main.js")),
    "admin-settings": resolve(join("src", "admin-settings.js")),
    "admin-form": resolve(join("src", "admin-form.css")),
  },
  {
    createEmptyCSSEntryPoints: true,
    extractLicenseInformation: true,
    thirdPartyLicense: false,
  }
);
