/**
 * Design Your Jacket — full configurator SPA.
 *
 * ES module loaded via <script type="module"> from functions.php.
 * Do NOT enqueue via wp_enqueue_script (strips type="module").
 * Imports Tailormate modules from ../../configurator/ using browser-native
 * module resolution — no bundler required.
 */

import {
  createTailormateVisualizer,
  loadSceneConfig,
  SCENE_MENU_ALIASES,
  TAILORMATE_API_KEY,
  TAILORMATE_API_BASE,
  slugifySceneValue,
} from "../../configurator/tailormate-visualizer.js";
import { createMonogramPreview } from "../../configurator/monogram-preview.js";

const container = document.getElementById("ar-design-app");
if (!container) throw new Error("ar-design-app container not found");

const scheduleUrl = container.dataset.scheduleUrl || "/schedule-a-call/";
const samplesUrl = container.dataset.samplesUrl || "/request-cloth-samples/";
const orderActionUrl = container.dataset.ajaxUrl || "";
const orderNonce = container.dataset.orderNonce || "";
const cartUrl = container.dataset.cartUrl || "";

// Fallback lifestyle images (replit CDN used by the original demo).
const asset = (path) => "https://alex-rose-web.replit.app" + path;

const DESIGN_TAGS = {
  fabrics: "website",
  lining: "lining",
  buttons: "buttons",
};
const DESIGN_PAGE_SIZE = 100;
const DESIGN_DEPTH = 2;
const REQUESTED_FABRIC_REFERENCE_ID = new URLSearchParams(window.location.search).get("c")?.trim() || "";

// Virtual try-on (Rendream "try before you buy" — overlays the 3D jacket
// snapshot onto the customer's uploaded photo).
const TRY_ON_API_URL = "https://api.rendream.com/tbb";
const TRY_ON_API_KEY = "e632c636-57fd-4a67-beea-b376669b3411";
const DRAFT_IMAGE_STORAGE_KEY = "draftImage";
const TRY_ON_RESULT_STORAGE_KEY = "virtualTryOnResult";
const VALID_TRY_ON_PERSON_FORMATS = ["image/jpeg", "image/png"];
const TRY_ON_PROGRESS_MESSAGES = ["Getting Ready", "Nearly There", "Just Putting the Finishing Touches"];
const TRY_ON_PROGRESS_INTERVAL = 8500;
const ROTATION_TIP_DURATION = 5000;

const CURRENCIES = {
  GBP: { symbol: "£", rate: 1 },
  EUR: { symbol: "€", rate: 1.18 },
  USD: { symbol: "$", rate: 1.27 },
};

const CATEGORY_ICONS = {
  fabrics: iconFabrics(),
  lining: iconLining(),
  buttons: iconButtons(),
  buttoning: iconButtoning(),
  pockets: iconPockets(),
  vents: iconVents(),
  monogram: iconMonogram(),
};

const BASE_CATEGORY_ORDER = [
  { id: "fabrics", label: "Fabrics" },
  { id: "lining", label: "Lining" },
  { id: "buttons", label: "Buttons" },
];
const MONOGRAM_CATEGORY = { id: "monogram", label: "Monogram" };

const CATEGORY_SUBTITLES = {
  fabrics: "Choose your cloth collection",
  lining: "Select your lining colour",
  buttons: "Choose your button material",
  buttoning: "Number of front buttons",
  pockets: "Choose your pocket style",
  vents: "Choose back vent style",
  monogram: "Add a personal inscription to your lining",
};

const state = {
  step: 1,
  mobileView: "preview",
  mobilePanelOpen: false,
  drawerOpen: false,
  showRotationTip: false,
  rotationTipShown: false,
  selectedCategory: "fabrics",
  selectedCollection: "",
  openCollection: "",
  selectedSwatch: "",
  selectedSwatchImg: "",
  selectedSwatchRef: "",
  lining: LININGS_DEFAULT(),
  buttons: "",
  buttoning: "",
  pockets: "",
  vents: "",
  monogram: "",
  modalOpen: false,
  zoomOpen: false,
  tryPhoto: "",
  trySubmitting: false,
  tryProgressIndex: 0,
  tryError: "",
  tryOnResult: loadSessionValue(TRY_ON_RESULT_STORAGE_KEY),
  tryForm: loadCustomer(),
  priceCurrency: loadCurrency(),
  measurementChoice: "",
  bodyUnits: "inches",
  weightUnits: "kg",
  checkoutDone: false,
  orderSubmitting: false,
  orderError: "",
  orderId: 0,
  show3dLoaderOnNextSync: false,
  measurements: {
    chest: "",
    waist: "",
    hips: "",
    height: "",
    weight: "",
    jacketSleeve: "",
    jacketShoulder: "",
    jacketBackLength: "",
    jacketSizeLabel: "",
    jacketHalfBack: "",
    jacketHalfWaist: "",
  },
  contact: Object.assign(loadCustomer(), { date: "", message: "" }),
};

const catalog = {
  fabrics: { items: [], grouped: [], loading: false, loaded: false, error: "" },
  lining: { items: [], loading: false, loaded: false, error: "" },
  buttons: { items: [], loading: false, loaded: false, error: "" },
};

const sceneCatalog = { menus: [], loading: false, loaded: false, error: "" };

const EMPTY_COLLECTION = {
  slug: "",
  name: "No Fabric Selected",
  eyebrow: "",
  heroImg: asset("/lifestyle-4.jpg"),
  clothImg: asset("/lifestyle-4.jpg"),
  tagline: "",
  swatches: [],
};
const EMPTY_LINING = { id: "", label: "Lining", color: "#f2ede6" };
const EMPTY_BUTTON = { id: "", label: "Buttons", img: "" };

function LININGS_DEFAULT() {
  return "";
}

const tailormate = createTailormateVisualizer();
let tailormateFrame = 0;
let previewResizeFrame = 0;
let tryProgressTimer = null;
let rotationTipTimer = null;
const monogramPreview = createMonogramPreview();
let monogramFrame = 0;

function loadCurrency() {
  try {
    const stored = localStorage.getItem("alexrose_currency");
    if (stored && CURRENCIES[stored]) return stored;
  } catch (_) {}
  return "GBP";
}

function saveCurrency(currency) {
  try { localStorage.setItem("alexrose_currency", currency); } catch (_) {}
}

// Shared customer profile — the name/email/phone entered in the fitting-room
// and order forms is persisted here so it prefills the rest of the order
// process (WooCommerce checkout, send-measurements). See assets/js/customer-prefill.js.
const CUSTOMER_STORAGE_KEY = "alexrose_customer";

function loadCustomer() {
  // Defaults from the logged-in WordPress account (set on the container in
  // template-parts/design/markup.php). A saved localStorage value wins when
  // present, otherwise the account details prefill the forms.
  const fromAccount = {
    name: container.dataset.userName || "",
    email: container.dataset.userEmail || "",
    phone: container.dataset.userPhone || "",
  };
  try {
    const data = JSON.parse(localStorage.getItem(CUSTOMER_STORAGE_KEY));
    if (data && typeof data === "object") {
      return {
        name: (typeof data.name === "string" && data.name) ? data.name : fromAccount.name,
        email: (typeof data.email === "string" && data.email) ? data.email : fromAccount.email,
        phone: (typeof data.phone === "string" && data.phone) ? data.phone : fromAccount.phone,
      };
    }
  } catch (_) {}
  return fromAccount;
}

function saveCustomerField(field, value) {
  if (field !== "name" && field !== "email" && field !== "phone") return;
  try {
    const current = loadCustomer();
    current[field] = value;
    localStorage.setItem(CUSTOMER_STORAGE_KEY, JSON.stringify(current));
  } catch (_) {}
}

// Keep the fitting-room and order forms in sync and persist to the profile.
function syncCustomerField(field, value) {
  if (field !== "name" && field !== "email" && field !== "phone") return;
  state.tryForm[field] = value;
  state.contact[field] = value;
  saveCustomerField(field, value);
}

function formatPrice(amount, currency) {
  const cur = currency || state.priceCurrency;
  const meta = CURRENCIES[cur] || CURRENCIES.GBP;
  const value = amount * meta.rate;
  return meta.symbol + Math.round(value).toLocaleString("en-GB");
}

function escapeHtml(value) {
  return String(value)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}

function stripFileExtension(value) {
  return String(value || "").replace(/\.[^.]+$/, "");
}

function loadSessionValue(key) {
  try {
    return sessionStorage.getItem(key) || "";
  } catch (_) {
    return "";
  }
}

function saveSessionValue(key, value) {
  try {
    sessionStorage.setItem(key, value);
  } catch (_) {}
}

function startTryProgress() {
  stopTryProgress();
  state.tryProgressIndex = 0;
  tryProgressTimer = setInterval(() => {
    if (state.tryProgressIndex >= TRY_ON_PROGRESS_MESSAGES.length - 1) {
      stopTryProgress();
      return;
    }
    state.tryProgressIndex += 1;
    render();
  }, TRY_ON_PROGRESS_INTERVAL);
}

function stopTryProgress() {
  if (tryProgressTimer) {
    clearInterval(tryProgressTimer);
    tryProgressTimer = null;
  }
}

function clearTryOnResult() {
  state.tryOnResult = "";
  state.tryError = "";
  try {
    sessionStorage.removeItem(TRY_ON_RESULT_STORAGE_KEY);
  } catch (_) {}
}

function dataUrlToFile(dataUrl, filename) {
  const parts = String(dataUrl || "").split(",");
  const meta = parts[0] || "";
  const base64 = parts[1] || "";
  const mimeType = (meta.match(/:(.*?);/) || [])[1] || "";
  if (!base64 || !mimeType) throw new Error("Invalid image data");

  const byteString = atob(base64);
  const arrayBuffer = new Uint8Array(byteString.length);
  for (let i = 0; i < byteString.length; i += 1) {
    arrayBuffer[i] = byteString.charCodeAt(i);
  }
  return new File([arrayBuffer], filename, { type: mimeType });
}

function blobToDataUrl(blob) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.onloadend = () => resolve(String(reader.result || ""));
    reader.onerror = () => reject(reader.error || new Error("Unable to read try-on image"));
    reader.readAsDataURL(blob);
  });
}

async function readTryOnApiError(response) {
  try {
    const payload = await response.clone().json();
    return payload.message || payload.error || "Virtual try-on failed with status " + response.status;
  } catch (_) {}
  try {
    const text = await response.text();
    return text || "Virtual try-on failed with status " + response.status;
  } catch (_) {}
  return "Virtual try-on failed with status " + response.status;
}

function resolveDesignUrl(value) {
  if (!value) return "";
  const origin = new URL(TAILORMATE_API_BASE, window.location.origin).origin;
  return new URL(value, origin + "/").toString();
}

function getDesignProperty(item, name) {
  const prop = Array.isArray(item.properties)
    ? item.properties.find((e) => String(e.Property || e.name || "").toLowerCase() === name.toLowerCase())
    : null;
  return prop ? prop.value || "" : "";
}

function getDesignCollectionMeta(item) {
  const prop = Array.isArray(item.properties)
    ? item.properties.find((e) => String(e.Property || e.name || "").toLowerCase() === "collection")
    : null;
  if (!prop) return null;
  return {
    value: prop.value || "",
    label: prop.label || "",
    thumbnail: resolveDesignUrl(prop.thumbnail),
    description: prop.description || "",
  };
}

function formatCollectionTitle(value) {
  const base = String(value || "Other Fabrics").trim();
  const title = /^the\s/i.test(base) ? base : "The " + base;
  return /collection$/i.test(title) ? title : title + " Collection";
}

function slugify(value) {
  return String(value || "")
    .toLowerCase()
    .replace(/^the\s+/, "")
    .replace(/\s+collection$/, "")
    .replace(/&/g, "and")
    .replace(/[^a-z0-9]+/g, "-")
    .replace(/^-+|-+$/g, "");
}

function normalizeDesignItem(item, type) {
  const referenceId = item.ReferenceId || item.FileName || item.Name || "";
  const cleanReferenceId = stripFileExtension(referenceId);
  const liningMatch = cleanReferenceId.match(/lining[_-]?(.+)/i);
  const id = type === "lining" && liningMatch ? liningMatch[1] : cleanReferenceId;
  const textureUrl = resolveDesignUrl(item.Url || item.WebpUrl);
  const thumbnailUrl = resolveDesignUrl(item.Url || item.WebpUrl);
  const repeat = Number(item.Repeat);
  return {
    id,
    label: item.Name || id,
    name: item.Name || id,
    img: thumbnailUrl,
    url: textureUrl,
    referenceId,
    cleanReferenceId,
    price: Number(item.Price || 0),
    repeat: Number.isFinite(repeat) ? repeat : undefined,
    collection: getDesignProperty(item, "Collection"),
    collectionMeta: getDesignCollectionMeta(item),
    properties: Array.isArray(item.properties) ? item.properties : [],
    raw: item,
  };
}

function groupFabrics(items) {
  const groups = new Map();
  items.forEach((item) => {
    const meta = item.collectionMeta;
    const collectionName = item.collection || "Other Fabrics";
    const key = slugify((meta && meta.value) || collectionName || "other-fabrics");
    if (!groups.has(key)) {
      const thumbnail = (meta && meta.thumbnail) || item.img || item.url;
      const description = (meta && meta.description) || "";
      groups.set(key, {
        slug: key,
        name: (meta && meta.label) || formatCollectionTitle(collectionName),
        eyebrow: description,
        heroImg: thumbnail,
        clothImg: thumbnail,
        tagline: description,
        description,
        swatches: [],
      });
    }
    groups.get(key).swatches.push(item);
  });
  return [...groups.values()];
}

async function fetchDesignPage(tag, page, limit) {
  page = page || 1;
  limit = limit || DESIGN_PAGE_SIZE;
  const params = new URLSearchParams({
    limit: String(limit),
    page: String(page),
    depth: String(DESIGN_DEPTH),
    tag,
  });
  const response = await fetch(TAILORMATE_API_BASE + "/designs?" + params.toString(), {
    headers: { Authorization: "Bearer " + TAILORMATE_API_KEY },
  });
  if (!response.ok) throw new Error("Design API failed with " + response.status);
  const payload = await response.json();
  if (payload.status === false) throw new Error(payload.message || "Design API failed");
  return payload;
}

function selectDefaultFabricIfNeeded() {
  const requestedFabric = REQUESTED_FABRIC_REFERENCE_ID && catalog.fabrics.grouped
    .map((collection) => ({
      collection: collection,
      swatch: collection.swatches.find((swatch) =>
        [swatch.referenceId, swatch.cleanReferenceId || stripFileExtension(swatch.referenceId) || swatch.name]
          .includes(REQUESTED_FABRIC_REFERENCE_ID)
      ),
    }))
    .find(({ swatch }) => swatch);

  if (requestedFabric) {
    const collection = requestedFabric.collection;
    const swatch = requestedFabric.swatch;
    state.selectedCollection = collection.slug;
    state.selectedSwatch = swatch.name;
    state.selectedSwatchImg = swatch.url || swatch.img;
    state.selectedSwatchRef = swatch.cleanReferenceId || stripFileExtension(swatch.referenceId) || swatch.name;
    return;
  }

  const selectedCollection = getCollection(state.selectedCollection);
  const selectedSwatch = selectedCollection
    ? selectedCollection.swatches.find((swatch) => {
        const ref = swatch.cleanReferenceId || stripFileExtension(swatch.referenceId) || swatch.name;
        return state.selectedSwatchRef ? state.selectedSwatchRef === ref : state.selectedSwatch === swatch.name;
      })
    : null;
  if (selectedSwatch) return;

  const collection = catalog.fabrics.grouped[0];
  const swatch = collection && collection.swatches[0];
  if (!collection || !swatch) {
    state.selectedCollection = "";
    state.selectedSwatch = "";
    state.selectedSwatchImg = "";
    state.selectedSwatchRef = "";
    return;
  }
  state.selectedCollection = collection.slug;
  state.selectedSwatch = swatch.name;
  state.selectedSwatchImg = swatch.url || swatch.img;
  state.selectedSwatchRef = swatch.cleanReferenceId || stripFileExtension(swatch.referenceId) || swatch.name;
}

async function fetchAllDesigns(tag, limit) {
  limit = limit || DESIGN_PAGE_SIZE;
  const firstPage = await fetchDesignPage(tag, 1, limit);
  const designs = Array.isArray(firstPage.data) ? firstPage.data.slice() : [];
  const totalPages = Number(firstPage.totalPages || 1);

  if (totalPages > 1) {
    const requests = [];
    for (let page = 2; page <= totalPages; page++) {
      requests.push(fetchDesignPage(tag, page, limit));
    }
    const pages = await Promise.all(requests);
    pages.forEach((payload) => {
      if (Array.isArray(payload.data)) designs.push.apply(designs, payload.data);
    });
  }
  return designs;
}

async function loadDesignCatalog() {
  Object.values(catalog).forEach((entry) => { entry.loading = true; entry.error = ""; });
  render();

  try {
    const results = await Promise.all([
      fetchAllDesigns(DESIGN_TAGS.fabrics),
      fetchAllDesigns(DESIGN_TAGS.lining),
      fetchAllDesigns(DESIGN_TAGS.buttons),
    ]);

    catalog.fabrics.items = results[0].map((item) => normalizeDesignItem(item, "fabrics"));
    catalog.fabrics.grouped = groupFabrics(catalog.fabrics.items);
    catalog.lining.items = results[1].map((item) => normalizeDesignItem(item, "lining"));
    catalog.buttons.items = results[2].map((item) => normalizeDesignItem(item, "buttons"));

    selectDefaultFabricIfNeeded();
    if (!getLiningOptions().some((item) => item.id === state.lining)) {
      state.lining = (getLiningOptions()[0] && getLiningOptions()[0].id) || state.lining;
    }
    if (!getButtonOptions().some((item) => item.id === state.buttons)) {
      state.buttons = (getButtonOptions()[0] && getButtonOptions()[0].id) || state.buttons;
    }
  } catch (error) {
    const msg = error.message || "Unable to load";
    catalog.fabrics.error = msg;
    catalog.lining.error = msg;
    catalog.buttons.error = msg;
    console.error("Design catalog load failed", error);
  } finally {
    Object.values(catalog).forEach((entry) => { entry.loading = false; entry.loaded = true; });
    render();
  }
}

function getSceneMenu(categoryId) {
  const aliases = SCENE_MENU_ALIASES[categoryId] || [categoryId];
  const slugs = aliases.map(slugifySceneValue);
  return sceneCatalog.menus.find((menu) => slugs.includes(slugifySceneValue(menu.label || menu.id))) || null;
}

function getFirstSceneMenuPosition() {
  return (sceneCatalog.menus[0] && sceneCatalog.menus[0].position) || null;
}

function getActiveMenuPosition() {
  if (state.selectedCategory === "fabrics" || state.selectedCategory === "lining" || state.selectedCategory === "buttons") {
    return getFirstSceneMenuPosition();
  }
  const menu = getSceneMenu(state.selectedCategory);
  return (menu && menu.position) || null;
}

function getVisibleSceneMenus() {
  return sceneCatalog.menus
    .filter((menu) => menu.displayInList)
    .slice()
    .sort((a, b) => a.sortOrder - b.sortOrder);
}

function getCategoryOrder() {
  const sceneCategories = getVisibleSceneMenus().map((menu) => ({
    id: menu.id,
    label: menu.label || menu.id,
    sceneMenu: menu,
  }));
  return BASE_CATEGORY_ORDER.concat(sceneCategories, MONOGRAM_CATEGORY);
}

function getCategoryIcon(categoryId) {
  return CATEGORY_ICONS[categoryId] || CATEGORY_ICONS.buttoning;
}

function getSceneOptions(categoryId) {
  const menu = getSceneMenu(categoryId);
  return (menu && menu.options) || [];
}

function getSceneOption(categoryId, optionId) {
  if (optionId === undefined) optionId = state[categoryId];
  const options = getSceneOptions(categoryId);
  return options.find((item) => item.id === optionId || item.referenceId === optionId) || options[0] || null;
}

function getSceneSelections() {
  return getVisibleSceneMenus().reduce((selections, menu) => {
    const section = menu.id;
    const option = getSceneOption(section);
    if (option) {
      selections[section] = { id: option.id, referenceId: option.referenceId };
    }
    return selections;
  }, {});
}

function selectDefaultSceneOptionsIfNeeded() {
  getVisibleSceneMenus().forEach((menu) => {
    const section = menu.id;
    const options = menu.options || [];
    if (!options.length) { state[section] = ""; return; }
    const selected = options.find((item) => item.id === state[section] || item.referenceId === state[section]);
    if (selected) { state[section] = selected.id; return; }
    state[section] = (menu.currentSelected && menu.currentSelected.id) || options[0].id;
  });
}

async function loadSceneCatalog() {
  sceneCatalog.loading = true;
  sceneCatalog.error = "";
  render();

  try {
    const result = await loadSceneConfig();
    sceneCatalog.menus = result.menus;
    selectDefaultSceneOptionsIfNeeded();
  } catch (error) {
    sceneCatalog.error = error.message || "Unable to load scene options";
    console.error("Scene config load failed", error);
  } finally {
    sceneCatalog.loading = false;
    sceneCatalog.loaded = true;
    render();
  }
}

function getCollection(slug) {
  return getFabricCollections().find((entry) => entry.slug === slug) || null;
}

function getSelectedCollection() {
  return getCollection(state.selectedCollection) || getFabricCollections()[0] || EMPTY_COLLECTION;
}

function getFabricCollections() { return catalog.fabrics.grouped; }
function getLiningOptions() { return catalog.lining.items; }
function getButtonOptions() { return catalog.buttons.items; }

function getLining() {
  return getLiningOptions().find((item) => item.id === state.lining) || getLiningOptions()[0] || EMPTY_LINING;
}

function getButtoning() {
  return getSceneOption("buttoning") || { id: "", label: "Buttoning", description: "" };
}

function getPocket() {
  return getSceneOption("pockets") || { id: "", label: "Pockets", description: "" };
}

function getVent() {
  return getSceneOption("vents") || { id: "", label: "Vents", description: "" };
}

function buildSummaryLine() {
  const parts = [];
  const collection = getSelectedCollection();
  if (state.selectedCollection) {
    const clothName = state.selectedSwatch || collection.name.replace(/^The\s/, "");
    parts.push(clothName + " (" + collection.name.replace(/^The\s/, "") + ")");
  }
  parts.push(getLining().label);
  parts.push(getButtoning().label);
  parts.push((getSelectedButton() && getSelectedButton().label) || "Buttons");
  parts.push(getPocket().label);
  parts.push(getVent().label);
  if (state.monogram) parts.push('"' + state.monogram + '"');
  return parts.join(" · ");
}

function heroImage() {
  const coll = getCollection(state.selectedCollection);
  return (coll && coll.heroImg) || asset("/lifestyle-4.jpg");
}

function getSelectedFabric() {
  const collection = getCollection(state.selectedCollection);
  if (!collection) return null;
  return collection.swatches.find((swatch) => {
    const ref = swatch.cleanReferenceId || stripFileExtension(swatch.referenceId) || swatch.name;
    return state.selectedSwatchRef ? state.selectedSwatchRef === ref : state.selectedSwatch === swatch.name;
  }) || null;
}

function getBasePrice() {
  const fabric = getSelectedFabric();
  return (fabric && fabric.price) || 595;
}

function getSelectedButton() {
  return getButtonOptions().find((item) => item.id === state.buttons) || getButtonOptions()[0] || EMPTY_BUTTON;
}

function getTailormateState() {
  const lining = getLining();
  const button = getSelectedButton();
  const fabric = getSelectedFabric();
  const fallbackFabric = (catalog.fabrics.items[0] && (catalog.fabrics.items[0].url || catalog.fabrics.items[0].img)) || "";
  return {
    selectedSwatchImg: state.selectedSwatchImg,
    selectedSwatch: state.selectedSwatch,
    selectedSwatchRef: state.selectedSwatchRef,
    selectedSwatchRepeat: fabric && fabric.repeat,
    fallbackFabricUrl: fallbackFabric,
    lining: state.lining,
    liningImg: (lining.url || lining.img) || "",
    liningRepeat: lining.repeat,
    liningColor: lining.color || "#f2ede6",
    liningLabel: lining.label,
    buttons: state.buttons,
    buttonImg: (button.url || button.img) || "",
    buttonLabel: button.label,
    buttonReferenceId: button.cleanReferenceId || stripFileExtension(button.referenceId) || state.buttons,
    buttoning: state.buttoning,
    pockets: state.pockets,
    vents: state.vents,
    sceneSelections: getSceneSelections(),
    activeMenu: state.selectedCategory,
    menuPosition: getActiveMenuPosition(),
    monogram: state.monogram,
    showLoader: state.show3dLoaderOnNextSync,
  };
}

function scheduleTailormateSync() {
  cancelAnimationFrame(tailormateFrame);
  tailormateFrame = requestAnimationFrame(() => {
    const mount = container.querySelector(".tm3d-shell--primary[data-tm3d-mount]") || container.querySelector("[data-tm3d-mount]");
    if (!mount) return;
    tailormate.attach(mount);
    const tmState = getTailormateState();
    state.show3dLoaderOnNextSync = false;
    tailormate.sync(tmState)
      .then(() => {
        if (mount.classList.contains("tm3d-shell--primary")) {
          showRotationTipAfterLoad();
        }
      })
      .catch((error) => {
        console.error("Tailormate3D sync failed", error);
      });
  });
}

function showRotationTipAfterLoad() {
  if (state.rotationTipShown || state.showRotationTip) return;
  state.rotationTipShown = true;
  state.showRotationTip = true;
  render();

  clearTimeout(rotationTipTimer);
  rotationTipTimer = setTimeout(() => {
    state.showRotationTip = false;
    render();
  }, ROTATION_TIP_DURATION);
}

function scheduleMonogramSync() {
  cancelAnimationFrame(monogramFrame);
  monogramFrame = requestAnimationFrame(() => {
    const visibleOrFirst = (selector) => {
      const mounts = Array.prototype.slice.call(container.querySelectorAll(selector));
      return mounts.find((el) => el.offsetParent !== null) || mounts[0] || null;
    };
    const textMount = visibleOrFirst("[data-monogram-text-preview]");
    const imageMount = visibleOrFirst("[data-monogram-image-preview]");
    const imageTarget = imageMount && !imageMount.hidden ? imageMount : null;
    if (!textMount && !imageTarget) return;
    monogramPreview.attach(textMount, imageTarget);
    monogramPreview.update(state.monogram);
  });
}

function schedulePreviewResize() {
  cancelAnimationFrame(previewResizeFrame);
  previewResizeFrame = requestAnimationFrame(() => {
    tailormate.resize();
    scheduleMonogramSync();
  });
}

function setStep(step) {
  state.step = Math.max(1, Math.min(5, step));
  window.scrollTo({ top: 0, behavior: "smooth" });
  render();
}

function setCategory(category) {
  state.selectedCategory = category;
  render();
}

function openMobileCategory(category) {
  state.selectedCategory = category;
  state.mobilePanelOpen = true;
  render();
}

function closeMobilePanel() {
  state.mobilePanelOpen = false;
  render();
}

function resetPreviewPosition() {
  tailormate.resetPosition().catch((error) => {
    console.error("Tailormate3D reset failed", error);
  });
}

function scrollMobileCarousel(direction) {
  const track = container.querySelector(".mobile-category-carousel__track");
  const card = track && track.querySelector(".mobile-category-card");
  if (!track || !card) return;

  const gap = Number.parseFloat(getComputedStyle(track).columnGap || getComputedStyle(track).gap || "0") || 0;
  const distance = card.getBoundingClientRect().width + gap;
  track.scrollBy({ left: direction * distance, behavior: "smooth" });
}

function syncMobileCarouselToSelectedCategory() {
  const selectedCard = container.querySelector(".mobile-category-card.is-selected");
  if (selectedCard) selectedCard.scrollIntoView({ block: "nearest", inline: "nearest" });
}

function setMobileView(view) {
  state.mobileView = view;
  render();
}

function setCurrency(currency) {
  state.priceCurrency = currency;
  saveCurrency(currency);
  render();
}

function chooseCollection(slug, swatch) {
  state.selectedCollection = slug;
  state.selectedSwatch = swatch.name;
  state.selectedSwatchImg = swatch.url || swatch.img;
  state.selectedSwatchRef = swatch.cleanReferenceId || stripFileExtension(swatch.referenceId) || swatch.name;
  state.mobileView = "preview";
  state.mobilePanelOpen = false;
  state.show3dLoaderOnNextSync = true;
  clearTryOnResult();
  render();
}

function chooseOption(key, value) {
  state[key] = value;
  state.mobilePanelOpen = false;
  state.show3dLoaderOnNextSync = true;
  clearTryOnResult();
  render();
}

function chooseMeasurementChoice(choice) {
  state.measurementChoice = choice;
  render();
}

function updateMeasurementField(field, value) {
  state.measurements[field] = value;
}

function updateContactField(field, value) {
  state.contact[field] = value;
  syncCustomerField(field, value);
}

// The full jacket configuration sent to WooCommerce on order. tryOnResult is
// reduced to a flag — the base64 image is too large to store as order meta.
function getSelectedOptions() {
  const collection = getSelectedCollection();
  const lining = getLining();
  const button = getSelectedButton();
  return {
    fabric: {
      name: state.selectedSwatch,
      image: state.selectedSwatchImg,
      referenceId: state.selectedSwatchRef,
      collection: (collection && collection.name) || "",
    },
    lining: { id: lining.id, label: lining.label },
    buttons: { id: button.id, label: button.label },
    buttoning: { id: getButtoning().id, label: getButtoning().label },
    pockets: { id: getPocket().id, label: getPocket().label },
    vents: { id: getVent().id, label: getVent().label },
    monogram: state.monogram,
    summary: buildSummaryLine(),
    tryOnResult: state.tryOnResult ? "yes" : "",
  };
}

// Hand the configured jacket to WooCommerce: a full-page POST to the wc-ajax
// endpoint adds it to the cart, then redirects to either the cart ("reserve")
// or the checkout ("checkout"). Falls back to the in-page flow if WooCommerce
// isn't wired up.
function submitJacketToCart(redirectTarget) {
  if (!cartUrl || !orderNonce) {
    setStep(state.step + 1);
    return;
  }

  const form = document.createElement("form");
  form.method = "POST";
  form.action = cartUrl;
  form.style.display = "none";

  const field = (name, value) => {
    const input = document.createElement("input");
    input.type = "hidden";
    input.name = name;
    input.value = value;
    form.appendChild(input);
  };

  field("ar_order_nonce", orderNonce);
  field("ar_redirect", redirectTarget);
  field("ar_price", String(getBasePrice()));
  field("ar_currency", state.priceCurrency);
  field("ar_options", JSON.stringify(getSelectedOptions()));
  field("ar_measurements", JSON.stringify(state.measurements));

  document.body.appendChild(form);
  form.submit();
}

// "Checkout Now" → add to cart, go straight to checkout.
function checkoutNow() {
  submitJacketToCart("checkout");
}

// "Reserve" → add to cart, go to the cart ("Reserved Jackets") page.
function reserveNow() {
  submitJacketToCart("cart");
}

function submitContact() {
  if (!state.contact.name || !state.contact.email) return;
  if (state.checkoutDone || state.orderSubmitting) return;

  // No backend wired (e.g. opened without the WordPress data attributes) —
  // keep the original client-only success behaviour.
  if (!orderActionUrl || !orderNonce) {
    state.checkoutDone = true;
    render();
    return;
  }

  state.orderSubmitting = true;
  state.orderError = "";
  render();

  const body = new FormData();
  body.append("action", "ar_create_jacket_order");
  body.append("ar_order_nonce", orderNonce);
  body.append("_ajax", "1");
  body.append("ar_name", state.contact.name);
  body.append("ar_email", state.contact.email);
  body.append("ar_phone", state.contact.phone);
  body.append("ar_date", state.contact.date);
  body.append("ar_message", state.contact.message);
  body.append("ar_price", String(getBasePrice()));
  body.append("ar_currency", state.priceCurrency);
  body.append("ar_options", JSON.stringify(getSelectedOptions()));
  body.append("ar_measurements", JSON.stringify(state.measurements));

  fetch(orderActionUrl, {
    method: "POST",
    body: body,
    credentials: "same-origin",
    headers: { "X-Requested-With": "XMLHttpRequest" },
  })
    .then((response) => response.json().catch(() => ({ ok: response.ok })))
    .then((payload) => {
      if (payload && payload.ok) {
        state.orderId = payload.order_id || 0;
        state.checkoutDone = true;
      } else {
        state.orderError = (payload && payload.message) ||
          "We couldn't submit your order. Please try again or call us.";
      }
    })
    .catch(() => {
      state.orderError = "We couldn't submit your order. Please try again or call us.";
    })
    .finally(() => {
      state.orderSubmitting = false;
      render();
    });
}

function openModal() { state.modalOpen = true; render(); }
function closeModal() { state.modalOpen = false; render(); }
function openZoom() { state.zoomOpen = true; render(); }
function closeZoom() { state.zoomOpen = false; render(); }

function updateTryField(field, value) {
  state.tryForm[field] = value;
  state.tryError = "";
  syncCustomerField(field, value);
}

function validateTryOnRequest() {
  if (!state.tryForm.name || !state.tryForm.email || !state.tryForm.phone) {
    return "Please enter your name, email and phone number.";
  }
  if (!state.tryPhoto) {
    return "Please upload a JPG or PNG photo.";
  }
  const personMimeType = (state.tryPhoto.split(",")[0].match(/:(.*?);/) || [])[1] || "";
  if (!VALID_TRY_ON_PERSON_FORMATS.includes(personMimeType)) {
    return "Invalid person image format. Please upload a JPG or PNG file.";
  }
  return "";
}

async function submitTryOnPreview() {
  if (state.trySubmitting) return;

  // Already generated — the button advances to the summary step.
  if (state.tryOnResult) {
    state.modalOpen = false;
    setStep(2);
    return;
  }

  const validationError = validateTryOnRequest();
  if (validationError) {
    state.tryError = validationError;
    render();
    return;
  }

  state.trySubmitting = true;
  state.tryError = "";
  startTryProgress();
  render();

  try {
    const draftImageBase64 = await tailormate.captureGarmentImage();
    if (!draftImageBase64) throw new Error("Draft image (garment) is missing");
    saveSessionValue(DRAFT_IMAGE_STORAGE_KEY, draftImageBase64);

    const personMimeType = (state.tryPhoto.split(",")[0].match(/:(.*?);/) || [])[1] || "";
    const personExtension = personMimeType === "image/jpeg" ? "jpg" : "png";
    const personImageFile = dataUrlToFile(state.tryPhoto, "personImage." + personExtension);
    const draftImageFile = dataUrlToFile(draftImageBase64, "garmentImage.png");

    const formData = new FormData();
    formData.append("garment", draftImageFile);
    formData.append("person", personImageFile);

    const response = await fetch(TRY_ON_API_URL, {
      method: "POST",
      body: formData,
      headers: { "api-key": TRY_ON_API_KEY },
    });

    if (!response.ok) {
      throw new Error(await readTryOnApiError(response));
    }

    const contentType = response.headers.get("content-type") || "";
    if (contentType.includes("application/json")) {
      const payload = await response.json();
      if (payload.error || payload.message) {
        throw new Error(payload.message || payload.error);
      }
    }

    const resultBase64 = await blobToDataUrl(await response.blob());
    state.tryOnResult = resultBase64;
    saveSessionValue(TRY_ON_RESULT_STORAGE_KEY, resultBase64);
  } catch (error) {
    console.error("Virtual try-on failed", error);
    state.tryError = error && error.message
      ? error.message
      : "An error occurred while generating your jacket preview.";
  } finally {
    stopTryProgress();
    state.trySubmitting = false;
    render();
  }
}

// ─── Render helpers ─────────────────────────────────────────────────────────

function renderTailormateMount(scope) {
  const monogramOverlay =
    scope === "primary"
      ? '<div class="tm3d-monogram" data-monogram-image-preview ' + (state.selectedCategory === "monogram" ? "" : "hidden") + '></div>'
      : "";
  const rotationTip = scope === "primary" && state.showRotationTip ? renderRotationTipOverlay() : "";
  return (
    '<div class="tm3d-shell tm3d-shell--' + scope + '" data-tm3d-mount>' +
      '<div class="tm3d-loader" data-tm3d-loader hidden><span></span></div>' +
      '<div class="tm3d-fallback">3D preview unavailable</div>' +
      rotationTip +
      monogramOverlay +
    "</div>"
  );
}

function renderRotationTipOverlay() {
  return (
    '<div class="tm3d-rotation-tip" aria-live="polite">' +
      '<div class="tm3d-rotation-tip__garment" aria-hidden="true">' +
        '<svg width="120" height="120" viewBox="0 0 24 24" fill="none">' +
          '<path d="M8.5 3 5 5.5 3 9l2.5 2 1.5-1v9h10v-9l1.5 1L21 9l-2-3.5L15.5 3a3.5 3.5 0 0 1-7 0Z"></path>' +
        "</svg>" +
      "</div>" +
      '<svg class="tm3d-rotation-tip__arrows" viewBox="0 0 512 190" fill="none" aria-hidden="true">' +
        '<path d="m203.43 185.54c-2.75-3.88-2.1-5.83 5.34-19.26 2.76-4.86 5.02-9.39 5.02-9.87 0-0.49-11.17-1.95-24.6-2.92-88.05-7.12-152.95-25.57-178.04-50.66-15.7-15.7-14.73-35.93 2.27-50.66 26.38-22.66 80.28-38.36 161.53-47.26 36.9-3.88 125.76-3.88 161.85 0 85.14 9.55 139.84 26.22 163.47 49.85 27.19 27.19 6.64 57.14-52.6 76.56-15.86 5.18-50.5 13.11-69.43 15.86-15.7 2.27-19.27 1.3-20.07-5.5-0.65-5.83 2.42-7.61 19.26-10.36 63.12-10.04 107.79-27.68 117.34-46.29 12.3-23.63-52.44-51.79-144.21-62.8-56-6.64-133.37-6.64-189.37 0-76.88 9.23-135.96 30.27-144.86 51.79-7.61 18.62 31.72 40.3 96.3 53.25 36.58 7.29 99.54 14.25 99.54 10.85 0-0.65-2.1-5.34-4.85-10.69-5.83-11.65-6.31-17.31-1.14-19.58 2.92-1.29 9.88 1.13 43.06 15.86 40.62 17.97 45.8 21.37 42.4 27.68-0.81 1.62-6.15 5.02-11.81 7.61-5.67 2.42-22.82 10.19-38.36 17.15-15.38 7.12-29.78 12.79-31.89 12.79-1.94 0-4.85-1.62-6.15-3.4z"></path>' +
      "</svg>" +
      '<div class="tm3d-rotation-tip__caption">' +
        "<p>Tip: You can swivel the jacket around to view it from all angles.</p>" +
      "</div>" +
    "</div>"
  );
}

const SWATCH_CHECK_BADGE =
  '<span class="swatch__check" aria-hidden="true">' +
    '<svg viewBox="0 0 24 24" width="8" height="8" fill="none">' +
      '<path d="M20 6 9 17l-5-5" stroke="#111" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />' +
    "</svg>" +
  "</span>";

function renderPanelHeader(categoryId) {
  const categories = getCategoryOrder();
  const category = categories.find((item) => item.id === categoryId) || categories[0];
  const title = (category && category.label) || "";
  const sub = CATEGORY_SUBTITLES[categoryId] || "";
  return (
    '<div class="panel__header">' +
      '<h2 class="panel__header-title">' + escapeHtml(title) + "</h2>" +
      (sub ? '<span class="panel__header-sub">' + escapeHtml(sub) + "</span>" : "") +
    "</div>"
  );
}

function renderSceneOptionPanel(categoryId, category) {
  const menu = getSceneMenu(categoryId);
  const options = getSceneOptions(categoryId);
  const title = (menu && menu.label) || category.label;
  const selectedId = state[categoryId];
  const subtitle =
    categoryId === "buttoning" ? "Front closure" :
    categoryId === "pockets"   ? "Shape and detail" :
    categoryId === "vents"     ? "Rear finish" : "Options";

  return (
    '<div class="panel__section">' +
      '<div class="section-title"><h3>' + escapeHtml(title) + "</h3><p>" + escapeHtml(subtitle) + "</p></div>" +
      (sceneCatalog.loading ? '<p class="panel__copy" style="margin-bottom:14px;">Loading live ' + escapeHtml(category.label.toLowerCase()) + "...</p>" : "") +
      (sceneCatalog.error ? '<p class="panel__copy" style="margin-bottom:14px;">Unable to load ' + escapeHtml(category.label.toLowerCase()) + ".</p>" : "") +
      (!sceneCatalog.loading && !sceneCatalog.error && !options.length ? '<p class="panel__copy" style="margin-bottom:14px;">No ' + escapeHtml(category.label.toLowerCase()) + " options found.</p>" : "") +
      '<div class="choice-grid">' +
        options.map((item) =>
          '<button type="button" class="choice-card ' + (selectedId === item.id ? "is-selected" : "") + '" data-action="option" data-key="' + categoryId + '" data-value="' + escapeHtml(item.id) + '">' +
            '<div class="choice-card__media">' +
              (item.img || item.image
                ? '<img src="' + escapeHtml(item.img || item.image) + '" alt="' + escapeHtml(item.label) + '" />'
                : '<span style="display:block;width:100%;height:100%;background:linear-gradient(135deg,rgba(200,169,106,0.18),rgba(13,13,13,0.08));"></span>'
              ) +
            "</div>" +
            '<p class="choice-card__title">' + escapeHtml(item.label) + "</p>" +
            '<p class="choice-card__copy">' + escapeHtml(item.description || item.desc || "") + "</p>" +
          "</button>"
        ).join("") +
      "</div>" +
    "</div>"
  );
}

function renderCategoryPanel(categoryId) {
  const categories = getCategoryOrder();
  const category = categories.find((item) => item.id === categoryId) || categories[0];

  if (categoryId === "fabrics") {
    const fabricCollections = getFabricCollections();
    const openCollection = fabricCollections.find((entry) => entry.slug === state.openCollection);
    const fabricStatus =
      (catalog.fabrics.loading ? '<p class="panel__copy" style="margin-bottom:14px;">Loading live fabrics...</p>' : "") +
      (catalog.fabrics.error ? '<p class="panel__copy" style="margin-bottom:14px;">Unable to load fabrics.</p>' : "") +
      (!catalog.fabrics.loading && !catalog.fabrics.error && !fabricCollections.length ? '<p class="panel__copy" style="margin-bottom:14px;">No fabrics found.</p>' : "");

    if (openCollection) {
      return (
        '<div class="panel__section">' +
          '<button type="button" class="collection-back" data-action="close-collection">← Collections</button>' +
          '<div class="collection-head">' +
            (openCollection.heroImg ? '<div class="collection-head__media"><img src="' + escapeHtml(openCollection.heroImg) + '" alt="' + escapeHtml(openCollection.name) + '" /></div>' : "") +
            '<div class="collection-head__body">' +
              '<h3 class="collection-head__title">' + escapeHtml(openCollection.name) + "</h3>" +
              (openCollection.description ? '<p class="collection-head__copy">' + escapeHtml(openCollection.description) + "</p>" : "") +
            "</div>" +
          "</div>" +
          '<div class="swatches">' +
            openCollection.swatches.map((swatch) => {
              const ref = swatch.cleanReferenceId || stripFileExtension(swatch.referenceId) || swatch.name;
              const isSelected =
                state.selectedCollection === openCollection.slug &&
                (state.selectedSwatchRef ? state.selectedSwatchRef === ref : state.selectedSwatch === swatch.name);
              return (
                '<button type="button" class="swatch ' + (isSelected ? "is-selected" : "") + '" ' +
                  'data-action="swatch" data-collection="' + openCollection.slug + '" ' +
                  'data-swatch="' + escapeHtml(swatch.name) + '" ' +
                  'data-img="' + escapeHtml(swatch.url || swatch.img) + '" ' +
                  'data-ref="' + escapeHtml(ref) + '">' +
                  '<div class="swatch__media">' +
                    '<img src="' + escapeHtml(swatch.img || swatch.url) + '" alt="' + escapeHtml(swatch.name) + '" />' +
                    (isSelected ? SWATCH_CHECK_BADGE : "") +
                  "</div>" +
                  '<div class="swatch__label">' + escapeHtml(swatch.name) + "</div>" +
                "</button>"
              );
            }).join("") +
          "</div>" +
          '<a class="inline-link" href="' + escapeHtml(samplesUrl) + '">Request free cloth samples →</a>' +
        "</div>"
      );
    }

    return (
      '<div class="panel__section">' +
        '<div class="section-title"><h3>Fabrics</h3><p>Choose your cloth collection</p></div>' +
        fabricStatus +
        '<div class="collection-grid">' +
          fabricCollections.map((entry) =>
            '<button type="button" class="collection-card ' + (state.selectedCollection === entry.slug ? "is-selected" : "") + '" data-action="open-collection" data-collection="' + entry.slug + '">' +
              '<div class="collection-card__media">' +
                (entry.heroImg ? '<img src="' + escapeHtml(entry.heroImg) + '" alt="' + escapeHtml(entry.name) + '" />' : "") +
              "</div>" +
              '<h4 class="collection-card__title">' + escapeHtml(entry.name) + "</h4>" +
              (entry.description ? '<p class="collection-card__copy">' + escapeHtml(entry.description) + "</p>" : "") +
            "</button>"
          ).join("") +
        "</div>" +
      "</div>"
    );
  }

  if (categoryId === "lining") {
    const liningOptions = getLiningOptions();
    return (
      '<div class="panel__section">' +
        '<div class="section-title"><h3>Lining</h3><p>Patterned &amp; plain options</p></div>' +
        (catalog.lining.loading ? '<p class="panel__copy" style="margin-bottom:14px;">Loading live linings...</p>' : "") +
        (catalog.lining.error ? '<p class="panel__copy" style="margin-bottom:14px;">Unable to load linings.</p>' : "") +
        (!catalog.lining.loading && !catalog.lining.error && !liningOptions.length ? '<p class="panel__copy" style="margin-bottom:14px;">No linings found.</p>' : "") +
        '<div class="swatches" style="grid-template-columns:repeat(auto-fill,minmax(92px,1fr));">' +
          liningOptions.map((item) => {
            const isSelected = state.lining === item.id;
            return '<button type="button" class="swatch ' + (isSelected ? "is-selected" : "") + '" data-action="option" data-key="lining" data-value="' + item.id + '">' +
              '<div class="swatch__media">' +
                (item.img || item.url
                  ? '<img src="' + escapeHtml(item.img || item.url) + '" alt="' + escapeHtml(item.label) + '" />'
                  : '<span style="display:block;width:100%;aspect-ratio:1;background:' + item.color + ';border:1px solid rgba(0,0,0,0.08);"></span>'
                ) +
                (isSelected ? SWATCH_CHECK_BADGE : "") +
              "</div>" +
              '<div class="swatch__label">' + escapeHtml(item.label) + "</div>" +
            "</button>";
          }).join("") +
        "</div>" +
      "</div>"
    );
  }

  if (categoryId === "buttons") {
    const buttonOptions = getButtonOptions();
    return (
      '<div class="panel__section">' +
        '<div class="section-title"><h3>Buttons</h3><p>Materials</p></div>' +
        (catalog.buttons.loading ? '<p class="panel__copy" style="margin-bottom:14px;">Loading live buttons...</p>' : "") +
        (catalog.buttons.error ? '<p class="panel__copy" style="margin-bottom:14px;">Unable to load buttons.</p>' : "") +
        (!catalog.buttons.loading && !catalog.buttons.error && !buttonOptions.length ? '<p class="panel__copy" style="margin-bottom:14px;">No buttons found.</p>' : "") +
        '<div class="choice-grid choice-grid--buttons" style="grid-template-columns:repeat(4,minmax(0,1fr));">' +
          buttonOptions.map((item) => {
            const isSelected = state.buttons === item.id;
            return '<button type="button" class="swatch ' + (isSelected ? "is-selected" : "") + '" data-action="option" data-key="buttons" data-value="' + item.id + '">' +
              '<div class="swatch__media">' +
                '<img src="' + escapeHtml(item.img || item.url) + '" alt="' + escapeHtml(item.label) + '" />' +
                (isSelected ? SWATCH_CHECK_BADGE : "") +
              "</div>" +
              '<div class="swatch__label">' + escapeHtml(item.label) + "</div>" +
            "</button>";
          }).join("") +
        "</div>" +
      "</div>"
    );
  }

  if (getSceneMenu(categoryId)) {
    return renderSceneOptionPanel(categoryId, category);
  }

  // Monogram
  return (
    '<div class="panel__section">' +
      '<div class="section-title"><h3>Monogram</h3><p>Personal detail</p></div>' +
      '<div class="monogram">' +
        '<label class="field">' +
          "<span>Monogram text</span>" +
          '<input class="monogram__input" type="text" maxlength="20" placeholder="e.g. Harold Rose, H.R., For the road..." value="' + escapeHtml(state.monogram) + '" data-action="monogram" />' +
          '<div class="monogram__counter" data-monogram-counter>' + state.monogram.length + "/20</div>" +
        "</label>" +
        '<div class="monogram__canvas" data-monogram-text-preview></div>' +
        '<p class="monogram__hint" style="margin-top:14px;">Inscribed on the inner breast pocket lining. Popular choices are initials, names, or a short personal motto.</p>' +
        '<p class="monogram__hint">Your tailor reviews every monogram before production begins.</p>' +
      "</div>" +
    "</div>"
  );
}

function renderStepOne() {
  const collection = getCollection(state.selectedCollection);
  const previewName = state.selectedSwatch || (collection ? collection.name.replace(/^The\s/, "") : "");
  const categories = getCategoryOrder();

  return (
    '<section class="step-1">' +
      '<aside class="rail">' +
        '<div class="rail__head"><span>Step 1<br />Design</span></div>' +
        categories.map((item) =>
          '<button type="button" class="rail__button ' + (state.selectedCategory === item.id ? "is-selected" : "") + '" data-action="category" data-value="' + item.id + '">' +
            '<div class="rail__icon-box">' + getCategoryIcon(item.id) + "</div>" +
            '<div class="rail__label">' + item.label + "</div>" +
          "</button>"
        ).join("") +
      "</aside>" +

      '<aside class="panel">' +
        renderPanelHeader(state.selectedCategory) +
        renderCategoryPanel(state.selectedCategory) +
      "</aside>" +

      '<div class="preview ' + (state.mobilePanelOpen ? "has-mobile-panel" : "") + '">' +
        renderTailormateMount("primary") +
        '<div class="preview__top">' +
          "<div>" +
            '<p class="preview__title">Alex Rose · Your Design</p>' +
            '<p class="preview__name ' + (previewName ? "" : "is-empty") + '">' + escapeHtml(previewName || "Tap a category below to begin") + "</p>" +
            (collection && state.selectedSwatch ? '<p class="preview__title" style="margin-top:5px;color:rgba(255,255,255,0.5);">' + escapeHtml(collection.name.replace(/^The\s/, "")) + "</p>" : "") +
          "</div>" +
          "<div>" +
            '<p class="preview__price-label">Starting From</p>' +
            '<p class="preview__price">' + formatPrice(getBasePrice()) + "</p>" +
          "</div>" +
        "</div>" +

        '<div class="currency-rail">' +
          Object.keys(CURRENCIES).map((id) =>
            '<button type="button" data-action="currency" data-value="' + id + '" class="' + (state.priceCurrency === id ? "is-selected" : "") + '">' + id + "</button>"
          ).join("") +
        "</div>" +

        '<div class="preview__tools" aria-label="Preview tools">' +
          '<button type="button" class="preview__tool" data-action="preview-reset" aria-label="Reset 3D model position">' +
            '<svg width="18" height="18" viewBox="0 0 512 512" aria-hidden="true">' +
              '<path fill="currentColor" d="M234.5 463.9c-79.1-9.6-142.3-57.7-172.1-130.8-4.8-11.7-5.4-16.5-2.4-20.3 2.4-3.2 7.2-5.2 10.3-4.4 4.9 1.2 7.4 4.5 11.2 14.3 21.5 55.8 66.3 97.2 123 113.4 18.9 5.5 26 6.3 51 6.3 19.7.1 24.7-.3 34.7-2.2 24.8-4.7 49-14.7 70.8-29.2 9.8-6.5 15.5-11.4 26.5-22.4 7.8-7.8 17-18.3 20.8-23.6 9.2-13 25.4-45.6 23.6-47.4-.2-.3-19.8 5.4-43.4 12.5-23.7 7.1-44.3 12.9-45.8 12.9-3.7 0-8.4-4.6-9.2-9.2-.8-4.2 1.2-8.8 4.7-10.6 3.9-2 109.8-33.2 112.7-33.2 3.7 0 11.1 3.9 13.4 7.2 3 4 34.9 111.6 34.2 115-1 4.3-5.9 7.8-10.9 7.8-7.7 0-7.9-.6-22.2-48.2-7.1-23.6-13.1-42.8-13.4-42.8-.3 0-1.2 1.7-1.9 3.8-2.9 8.2-12.3 26.3-18.8 36.2-33.3 50.3-82.8 83-140.8 93-14 2.4-43.6 3.4-56 1.9z"></path>' +
              '<path fill="currentColor" d="M54.5 220.6c-5.1-2.2-8.2-7-11.4-17.4-17.4-56.7-30.1-101-29.6-103.4.8-3.3 5.8-7.8 8.8-7.9 9.7-.5 9.6-.7 24.3 48.4 7.1 23.5 13.1 42.7 13.5 42.7.3 0 1.2-1.9 1.9-4.2 1.9-6.6 12.1-26.3 18.7-36.3 24.9-37.6 59.1-65.4 100.7-81.6 61.4-23.9 133.3-16.2 188.4 20.2 25.8 17.1 51 43.5 65.4 68.4 9.5 16.6 18.8 38.3 18.8 43.9 0 8.9-11.8 13.7-17.8 7.3-1.2-1.2-4.4-7.9-7.2-14.7-20.9-51.2-61-89.4-112.6-107.3-21.6-7.5-40-10.1-66.5-9.4-29.3.8-49.2 5.5-75.4 18.1-33.3 16-59.9 40.3-79.1 72.2-6.4 10.7-14 26.9-15.1 32.4-.5 2.7-.3 3.2.8 2.8 6.4-2.5 86.1-25.8 88.1-25.8 3.6 0 7.6 3.5 8.9 7.9.9 3.2.8 4.4-.5 7.3-.9 1.9-2.7 4-3.9 4.6-4.5 2.3-109.9 33.2-113 33.2-1.7-.1-4.6-.7-6.2-1.4z"></path>' +
            "</svg>" +
          "</button>" +
          '<button type="button" class="preview__tool preview__zoom" data-action="zoom-open" aria-label="Zoom jacket preview">' +
            '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" aria-hidden="true"><circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="1.8"></circle><path d="m21 21-4.35-4.35M11 8v6M8 11h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path></svg>' +
          "</button>" +
        "</div>" +

        '<div class="preview__bottom preview__bottom--desktop">' +
          '<div class="preview__actions">' +
            '<button type="button" class="preview__action preview__action--cart" data-action="reserve-now">Add to Cart</button>' +
            '<button type="button" class="preview__action preview__action--preview" data-action="modal-open">Preview My Jacket ' + iconArrowUpRight() + '</button>' +
          "</div>" +
        "</div>" +

        '<div class="preview__bottom preview__bottom--mobile">' +
          '<div class="mobile-category-rail">' +
            '<button type="button" class="mobile-category-nav" data-action="mobile-carousel-prev" aria-label="Previous design options"><span aria-hidden="true">‹</span></button>' +
            '<div class="mobile-category-carousel" aria-label="Design options">' +
              '<div class="mobile-category-carousel__track">' +
                categories.map((item) =>
                  '<button type="button" class="preview-tab mobile-category-card ' + (state.selectedCategory === item.id ? "is-selected" : "") + '" data-action="mobile-category" data-value="' + item.id + '">' +
                    getCategoryIcon(item.id) +
                    "<span>" + item.label + "</span>" +
                  "</button>"
                ).join("") +
              "</div>" +
            "</div>" +
            '<button type="button" class="mobile-category-nav" data-action="mobile-carousel-next" aria-label="Next design options"><span aria-hidden="true">›</span></button>' +
          "</div>" +
          '<div class="preview__actions">' +
            '<button type="button" class="preview__action preview__action--cart" data-action="reserve-now">Add to Cart</button>' +
            '<button type="button" class="preview__action preview__action--preview" data-action="modal-open">Preview My Jacket ' + iconArrowUpRight() + '</button>' +
          "</div>" +
        "</div>" +

        '<div class="mobile-options">' +
          '<div class="mobile-options__header">' +
            "<div>" +
              '<p class="mobile-options__eyebrow">Design Options</p>' +
              '<h2>' + escapeHtml((categories.find((item) => item.id === state.selectedCategory) || { label: "Options" }).label) + "</h2>" +
            "</div>" +
            '<button type="button" class="mobile-options__close" data-action="mobile-panel-close" aria-label="Close options panel"><span aria-hidden="true">×</span></button>' +
          "</div>" +
          '<div class="mobile-options__body">' + renderCategoryPanel(state.selectedCategory) + "</div>" +
        "</div>" +
      "</div>" +
    "</section>"
  );
}

function renderFlowFrame(content) {
  const collection = getCollection(state.selectedCollection);
  const name = state.selectedSwatch || (collection ? collection.name.replace(/^The\s/, "") : "Your Jacket");
  return (
    '<div class="flow">' +
      '<aside class="flow-preview">' +
        '<img src="' + heroImage() + '" alt="Jacket preview" />' +
        renderTailormateMount("flow") +
        '<div class="flow-preview__caption">' +
          '<p class="flow-preview__label">Selected Cloth</p>' +
          '<p class="flow-preview__name">' + escapeHtml(name) + "</p>" +
          (collection ? '<p class="flow-preview__collection">' + escapeHtml(collection.name.replace(/^The\s/, "")) + "</p>" : "") +
        "</div>" +
      "</aside>" +
      '<div class="flow-content">' + content + "</div>" +
    "</div>"
  );
}

function renderStepTwo() {
  const collection = getSelectedCollection();
  const clothName = state.selectedSwatch || collection.name.replace(/^The\s/, "");
  const clothImg = state.selectedSwatchImg || collection.clothImg;

  return renderFlowFrame(
    '<section class="panel__section panel__section--soft" style="border-bottom:1px solid rgba(229,223,214,0.95);">' +
      '<div class="step-form" style="max-width:860px;">' +
        '<p class="panel__eyebrow">Almost There</p>' +
        '<h1 class="panel__heading h1">Your Jacket Summary.</h1>' +
        '<p class="panel__copy" style="max-width:34rem;">A quick check of the design before you reserve your jacket.</p>' +
      "</div>" +
    "</section>" +
    '<section class="step-form" style="max-width:860px;">' +
      '<div class="summary-list">' +
        '<div class="summary-row" style="align-items:flex-start;">' +
          '<div style="display:flex;align-items:center;gap:16px;">' +
            '<img src="' + escapeHtml(clothImg) + '" alt="' + escapeHtml(collection.name) + '" style="width:64px;height:84px;object-fit:cover;border:1px solid rgba(229,223,214,0.95);" />' +
            "<div>" +
              '<div class="summary-row__label" style="margin-bottom:6px;">Selected Cloth</div>' +
              '<div class="summary-row__value" style="text-align:left;font-family:var(--serif);font-size:18px;">' + escapeHtml(clothName) + "</div>" +
              '<div class="summary-row__value" style="text-align:left;color:rgba(13,13,13,0.42);margin-top:4px;">' + escapeHtml(collection.name.replace(/^The\s/, "")) + "</div>" +
            "</div>" +
          "</div>" +
        "</div>" +
        [
          ["Lining", getLining().label],
          ["Buttons", (getSelectedButton() && getSelectedButton().label) || ""],
          ["Buttoning", getButtoning().label],
          ["Pockets", getPocket().label],
          ["Jacket Vent", getVent().label],
          ["Monogram", state.monogram || ""],
        ].map(([label, value]) =>
          '<div class="summary-row">' +
            '<div class="summary-row__label">' + escapeHtml(label) + "</div>" +
            '<div class="summary-row__value">' + escapeHtml(value) + "</div>" +
          "</div>"
        ).join("") +
      "</div>" +
      '<div class="price-block">' +
        "<div>" +
          '<p class="price-block__label">Starting From</p>' +
          '<p class="price-block__value">' + formatPrice(getBasePrice()) + "</p>" +
        "</div>" +
        '<div style="text-align:right;">' +
          '<p class="price-block__label">Includes</p>' +
          '<p class="price-block__label" style="margin:0;color:rgba(13,13,13,0.58);">Alterations · Delivery · Monogram</p>' +
        "</div>" +
      "</div>" +
      '<div class="button-stack">' +
        '<button class="action-button action-button--solid" type="button" data-action="checkout-now">Checkout Now ' + iconArrowUpRight() + '</button>' +
        '<button class="action-button action-button--outline" type="button" data-action="reserve-now">Reserve for Later</button>' +
        '<button class="action-button action-button--ghost" type="button" data-action="step" data-value="1">Edit Design</button>' +
        '<p class="small-note">No payment is taken yet. The next step confirms the fit and booking.</p>' +
      "</div>" +
    "</section>"
  );
}

function renderStepThree() {
  const collection = getSelectedCollection();
  const clothImg = state.selectedSwatchImg || collection.clothImg;
  return renderFlowFrame(
    '<section class="step-form">' +
      '<p class="panel__eyebrow">Final Step</p>' +
      '<h1 class="panel__heading h2">Reserve Your Jacket.</h1>' +
      '<p class="panel__copy">Your master tailor will review the design and get in touch to confirm everything.</p>' +
      '<div class="summary-list" style="margin-top:26px;">' +
        '<div class="summary-row" style="align-items:flex-start;">' +
          '<div style="display:flex;align-items:center;gap:16px;">' +
            '<img src="' + escapeHtml(clothImg) + '" alt="' + escapeHtml(collection.name) + '" style="width:56px;height:72px;object-fit:cover;border:1px solid rgba(229,223,214,0.95);" />' +
            "<div>" +
              '<div class="summary-row__label" style="margin-bottom:6px;">Selected Cloth</div>' +
              '<div class="summary-row__value" style="text-align:left;font-family:var(--serif);font-size:16px;">' + escapeHtml(state.selectedSwatch || collection.name.replace(/^The\s/, "")) + "</div>" +
              '<div class="summary-row__value" style="text-align:left;color:rgba(13,13,13,0.42);margin-top:4px;">' + escapeHtml(collection.eyebrow) + "</div>" +
            "</div>" +
          "</div>" +
        "</div>" +
        [
          ["Base Price", formatPrice(getBasePrice())],
          ["Alterations", "Included"],
          ["Free Cloth Samples", "Available on request"],
          ["Delivery", "Included · UK & worldwide"],
        ].map(([label, value]) =>
          '<div class="summary-row">' +
            '<div class="summary-row__label">' + escapeHtml(label) + "</div>" +
            '<div class="summary-row__value">' + escapeHtml(value) + "</div>" +
          "</div>"
        ).join("") +
      "</div>" +
      '<div class="price-block" style="padding-top:18px;">' +
        "<div>" +
          '<p class="price-block__label">Starting From</p>' +
          '<p class="price-block__value" style="font-size:clamp(28px,3.6vw,42px);">' + formatPrice(getBasePrice()) + "</p>" +
        "</div>" +
        '<div style="text-align:right;">' +
          '<p class="price-block__label">Includes</p>' +
          '<p class="price-block__label" style="margin:0;color:rgba(13,13,13,0.58);">Alterations · Delivery · Monogram</p>' +
        "</div>" +
      "</div>" +
      '<div class="button-stack" style="padding-left:0;padding-right:0;">' +
        '<button class="action-button action-button--solid" type="button" data-action="checkout-now">Checkout Now ' + iconArrowUpRight() + '</button>' +
        '<button class="action-button action-button--outline" type="button" data-action="reserve-now">Reserve for Later</button>' +
        '<button class="action-button action-button--ghost" type="button" data-action="step" data-value="1">Edit Design</button>' +
        '<div style="text-align:center;margin-top:14px;padding-top:14px;border-top:1px solid rgba(229,223,214,0.95);">' +
          '<p class="panel__copy" style="margin:0 0 6px;font-size:12px;">Not sure where to start?</p>' +
          '<a class="inline-link" href="' + escapeHtml(scheduleUrl) + '" style="margin-top:0;">Book a Call with Harold</a>' +
        "</div>" +
      "</div>" +
    "</section>"
  );
}

function renderStepFour() {
  return renderFlowFrame(
    '<section class="step-form">' +
      '<p class="panel__eyebrow">Step 4 of 5</p>' +
      '<h1 class="panel__heading h2">Your Measurements.</h1>' +
      '<p class="panel__copy">Choose how you want to submit them and then continue.</p>' +
      '<div class="option-chooser">' +
        '<button type="button" class="option-tile ' + (state.measurementChoice === "online" ? "is-selected" : "") + '" data-action="measure-choice" data-value="online">' +
          '<div class="option-tile__line"></div>' +
          '<p class="option-tile__title">Enter Online</p>' +
          '<p class="option-tile__copy">Submit your measurements now. We will send a tape measure if needed.</p>' +
        "</button>" +
        '<button type="button" class="option-tile ' + (state.measurementChoice === "inperson" ? "is-selected" : "") + '" data-action="measure-choice" data-value="inperson">' +
          '<div class="option-tile__line"></div>' +
          '<p class="option-tile__title">Tailor Measures You</p>' +
          '<p class="option-tile__copy">Your master tailor arranges a fitting, in person or via video.</p>' +
        "</button>" +
      "</div>" +
      (state.measurementChoice === "online" ? renderOnlineMeasurementForm() : "") +
      (state.measurementChoice === "inperson" ? renderInpersonMeasurementCard() : "") +
    "</section>"
  );
}

function renderOnlineMeasurementForm() {
  return (
    "<div>" +
      '<div class="measure-head">' +
        '<div class="panel__eyebrow" style="margin:0;color:var(--accent);">Body Measurements</div>' +
        '<div class="unit-toggle" aria-label="Units">' +
          ["inches", "cm"].map((unit) =>
            '<button type="button" class="' + (state.bodyUnits === unit ? "is-selected" : "") + '" data-action="unit" data-key="bodyUnits" data-value="' + unit + '">' + unit + "</button>"
          ).join("") +
        "</div>" +
      "</div>" +
      '<div class="form-grid form-grid--two" style="margin-bottom:24px;">' +
        [
          ["chest",  "Chest (" + state.bodyUnits + ")",       state.bodyUnits === "inches" ? "e.g. 40" : "e.g. 102"],
          ["waist",  "Waist (" + state.bodyUnits + ")",       state.bodyUnits === "inches" ? "e.g. 34" : "e.g. 86"],
          ["hips",   "Seat / Hips (" + state.bodyUnits + ")", state.bodyUnits === "inches" ? "e.g. 38" : "e.g. 96"],
          ["height", "Height",                                 "e.g. 6'1\" or 185cm"],
        ].map(([key, label, placeholder]) =>
          '<label class="field"><span>' + escapeHtml(label) + "</span>" +
            '<input type="text" placeholder="' + escapeHtml(placeholder) + '" data-action="measurement" data-key="' + key + '" value="' + escapeHtml(state.measurements[key]) + '" />' +
          "</label>"
        ).join("") +
      "</div>" +
      '<div class="measure-head">' +
        '<div class="panel__eyebrow" style="margin:0;color:var(--accent);">Weight</div>' +
        '<div class="unit-toggle" aria-label="Weight units">' +
          ["kg", "lbs"].map((unit) =>
            '<button type="button" class="' + (state.weightUnits === unit ? "is-selected" : "") + '" data-action="unit" data-key="weightUnits" data-value="' + unit + '">' + unit + "</button>"
          ).join("") +
        "</div>" +
      "</div>" +
      '<div class="form-grid" style="margin-bottom:26px;">' +
        '<label class="field"><span>Weight (' + state.weightUnits + ')</span>' +
          '<input type="text" placeholder="' + (state.weightUnits === "kg" ? "e.g. 80" : "e.g. 176") + '" data-action="measurement" data-key="weight" value="' + escapeHtml(state.measurements.weight) + '" />' +
        "</label>" +
      "</div>" +
      '<div class="panel__eyebrow" style="margin-top:12px;">Existing Jacket Measurements</div>' +
      '<p class="panel__copy" style="margin-top:6px;max-width:none;">Optional, helps refine your pattern.</p>' +
      '<div class="form-grid form-grid--two" style="margin-top:18px;">' +
        [
          ["jacketSleeve",      "Sleeve (" + state.bodyUnits + ")",       state.bodyUnits === "inches" ? "e.g. 24"  : "e.g. 61"],
          ["jacketShoulder",    "Shoulder (" + state.bodyUnits + ")",     state.bodyUnits === "inches" ? "e.g. 18"  : "e.g. 46"],
          ["jacketBackLength",  "Back length (" + state.bodyUnits + ")",  state.bodyUnits === "inches" ? "e.g. 30"  : "e.g. 76"],
          ["jacketSizeLabel",   "Label size",                              "e.g. 40R or 50"],
          ["jacketHalfBack",    "1/2 back (" + state.bodyUnits + ")",     state.bodyUnits === "inches" ? "e.g. 8.5" : "e.g. 21.5"],
          ["jacketHalfWaist",   "1/2 waist (" + state.bodyUnits + ")",   state.bodyUnits === "inches" ? "e.g. 17"  : "e.g. 43"],
        ].map(([key, label, placeholder]) =>
          '<label class="field"><span>' + escapeHtml(label) + "</span>" +
            '<input type="text" placeholder="' + escapeHtml(placeholder) + '" data-action="measurement" data-key="' + key + '" value="' + escapeHtml(state.measurements[key]) + '" />' +
          "</label>"
        ).join("") +
      "</div>" +
      '<button type="button" class="action-button action-button--solid" data-action="step" data-value="5" style="margin-top:4px;">Submit &amp; Continue ' + iconArrowUpRight() + '</button>' +
      '<button type="button" class="action-button action-button--ghost" data-action="step" data-value="3" style="margin-top:10px;">Back</button>' +
    "</div>"
  );
}

function renderInpersonMeasurementCard() {
  return (
    "<div>" +
      '<div class="option-tile is-selected" style="cursor:default;margin-bottom:20px;">' +
        '<div class="option-tile__line"></div>' +
        '<p class="option-tile__title">Your master tailor will arrange your fitting.</p>' +
        '<p class="option-tile__copy">In person in Leeds or via a remote video session.</p>' +
      "</div>" +
      '<button type="button" class="action-button action-button--solid" data-action="step" data-value="5">Confirm &amp; Continue</button>' +
      '<button type="button" class="action-button action-button--ghost" data-action="step" data-value="3" style="margin-top:10px;">Back</button>' +
    "</div>"
  );
}

function renderStepFive() {
  if (state.checkoutDone) {
    return renderFlowFrame(
      '<section class="success">' +
        '<div class="success__icon">' + checkIcon() + "</div>" +
        '<h1 class="success__heading">Order Received.</h1>' +
        '<p class="success__copy">Your master tailor will be in touch within one working day to confirm your design and arrange the next steps.</p>' +
        '<button type="button" class="pill" data-action="step" data-value="1">Back to Design</button>' +
      "</section>"
    );
  }

  return renderFlowFrame(
    '<section class="step-form">' +
      '<p class="panel__eyebrow">Final Step</p>' +
      '<h1 class="panel__heading h2">Book Your Consultation.</h1>' +
      '<p class="panel__copy">Your master tailor will review your design and get in touch to confirm everything.</p>' +
      '<div class="form-grid" style="margin-top:28px;">' +
        '<div class="form-grid form-grid--two">' +
          [
            ["name",  "Full Name",      "Your name"],
            ["email", "Email Address",  "your@email.com"],
          ].map(([key, label, placeholder]) =>
            '<label class="field"><span>' + escapeHtml(label) + "</span>" +
              '<input type="text" placeholder="' + escapeHtml(placeholder) + '" data-action="contact" data-key="' + key + '" value="' + escapeHtml(state.contact[key]) + '" />' +
            "</label>"
          ).join("") +
        "</div>" +
        '<label class="field"><span>Phone (optional)</span>' +
          '<input type="text" placeholder="+44 ..." data-action="contact" data-key="phone" value="' + escapeHtml(state.contact.phone) + '" />' +
        "</label>" +
        '<label class="field"><span>Preferred Contact Date</span>' +
          '<input type="text" placeholder="e.g. anytime, or a specific date" data-action="contact" data-key="date" value="' + escapeHtml(state.contact.date) + '" />' +
        "</label>" +
        '<label class="field"><span>Anything Else</span>' +
          '<textarea placeholder="Any questions or special requirements..." data-action="contact" data-key="message">' + escapeHtml(state.contact.message) + "</textarea>" +
        "</label>" +
      "</div>" +
      (state.orderError ? '<p class="small-note try-modal__error" style="padding:0;margin:16px 0 0;">' + escapeHtml(state.orderError) + "</p>" : "") +
      '<button type="button" class="action-button action-button--solid" style="margin-top:16px;" data-action="submit-contact"' + (state.orderSubmitting ? " disabled" : "") + ">" + (state.orderSubmitting ? "Submitting..." : "Submit My Order") + "</button>" +
      '<button type="button" class="action-button action-button--ghost" data-action="step" data-value="4" style="margin-top:10px;">Back</button>' +
      '<div style="margin-top:28px;padding-top:24px;border-top:1px solid rgba(229,223,214,0.95);">' +
        '<p class="panel__eyebrow" style="color:rgba(13,13,13,0.38);">Common Questions</p>' +
        '<div class="panel__copy" style="max-width:none;">Your master tailor reads every enquiry personally and replies directly with the next available time and any further guidance.</div>' +
      "</div>" +
    "</section>"
  );
}

function renderStage() {
  if (state.step === 1) return renderStepOne();
  if (state.step === 2) return renderStepTwo();
  if (state.step === 3) return renderStepThree();
  if (state.step === 4) return renderStepFour();
  return renderStepFive();
}

function renderZoomModal() {
  if (!state.zoomOpen) return "";
  const snapshot = tailormate.snapshot();
  return (
    '<div class="modal modal--dark" data-action="zoom-close">' +
      '<div class="modal__frame modal__frame--image" role="dialog" aria-modal="true" aria-label="Jacket preview">' +
        '<button class="modal__close" type="button" data-action="zoom-close">×</button>' +
        '<img class="modal__image" src="' + (snapshot || heroImage()) + '" alt="Jacket preview" />' +
      "</div>" +
    "</div>"
  );
}

function renderTryModal() {
  if (!state.modalOpen) return "";
  return (
    '<div class="modal" data-action="modal-backdrop">' +
      '<div class="try-modal ' + (state.tryOnResult ? "has-result" : "") + '" role="dialog" aria-modal="true" aria-label="Try before you buy">' +
        '<div class="try-modal__head">' +
          '<p class="panel__eyebrow">Try Before You Buy</p>' +
          "<h2>Upload your photo to see yourself wearing your new jacket.</h2>" +
          '<button class="modal__close modal__close--light" type="button" data-action="modal-close">×</button>' +
        "</div>" +
        '<div class="try-modal__body">' +
          '<div class="try-modal__form">' +
            [
              ["name",  "Your Name",  "Enter your full name",       "text"],
              ["email", "Your Email", "Enter your email address",   "email"],
              ["phone", "Your Phone", "Enter your phone number",    "tel"],
            ].map(([key, label, placeholder, type]) =>
              '<label class="field"><span>' + label + ' <b style="color:var(--accent);font-weight:400;">*</b></span>' +
                '<input type="' + type + '" placeholder="' + placeholder + '" data-action="try-field" data-key="' + key + '" value="' + escapeHtml(state.tryForm[key]) + '" />' +
              "</label>"
            ).join("") +
            '<p class="small-note" style="text-align:left;">Your photo and details are used only by your master tailor and are never shared or stored publicly.</p>' +
          "</div>" +
          '<div class="try-modal__upload">' +
            '<label class="field"><span>' + (state.tryOnResult ? "Your Preview" : "Your Photo") + "</span></label>" +
            (state.tryOnResult
              ? '<div class="try-modal__result"><img src="' + state.tryOnResult + '" alt="Virtual try-on result" /></div>'
              : state.trySubmitting
                ? '<div class="try-modal__loading">' +
                    '<span class="try-modal__spinner" aria-hidden="true"></span>' +
                    '<p class="try-modal__loading-text">' + escapeHtml(TRY_ON_PROGRESS_MESSAGES[state.tryProgressIndex] || "") + "…</p>" +
                  "</div>"
                : '<label class="upload-zone ' + (state.tryPhoto ? "has-photo" : "") + '">' +
                    '<input type="file" accept="image/jpeg,image/png" data-action="try-photo" />' +
                    (state.tryPhoto
                      ? '<img src="' + state.tryPhoto + '" alt="Your uploaded photo" /><span>Change Photo</span>'
                      : '<span class="upload-zone__icon">+</span><strong>Click to Upload</strong><em>Upload a high-resolution photo (*.jpg or *.png) that presents your full body in a well-defined pose.</em>'
                    ) +
                  "</label>"
            ) +
          "</div>" +
        "</div>" +
        (state.tryError ? '<p class="small-note try-modal__error">' + escapeHtml(state.tryError) + "</p>" : "") +
        '<div class="try-modal__foot">' +
          '<button type="button" class="action-button action-button--outline" data-action="modal-close">Edit Design</button>' +
          '<button type="button" class="action-button action-button--solid" data-action="try-continue"' + (state.trySubmitting ? " disabled" : "") + ">" +
            (state.trySubmitting
              ? "Generating Preview..."
              : state.tryOnResult
                ? "Continue to Summary " + iconArrowUpRight()
                : "Generate Preview " + iconArrowUpRight()
            ) +
          "</button>" +
        "</div>" +
      "</div>" +
    "</div>"
  );
}

function render() {
  container.innerHTML =
    '<div class="ar-stage">' + renderStage() + "</div>" +
    renderZoomModal() +
    renderTryModal();
  syncMobileCarouselToSelectedCategory();
  scheduleTailormateSync();
  scheduleMonogramSync();
}

// ─── Event handlers ──────────────────────────────────────────────────────────

function handleClick(event) {
  const target = event.target.closest("[data-action]");
  if (!target) return;

  const action = target.dataset.action;
  const value = target.dataset.value;

  if (action === "noop") { event.preventDefault(); return; }
  if (action === "step")          { setStep(Number(value)); return; }
  if (action === "category")      { setCategory(value); return; }
  if (action === "mobile-category") { openMobileCategory(value); return; }
  if (action === "mobile-panel-close") { closeMobilePanel(); return; }
  if (action === "mobile-carousel-prev") { scrollMobileCarousel(-1); return; }
  if (action === "mobile-carousel-next") { scrollMobileCarousel(1); return; }
  if (action === "view")          { setMobileView(value); return; }
  if (action === "currency")      { setCurrency(value); return; }
  if (action === "open-collection") { state.openCollection = target.dataset.collection || ""; render(); return; }
  if (action === "close-collection") { state.openCollection = ""; render(); return; }
  if (action === "measure-choice"){ chooseMeasurementChoice(value); return; }
  if (action === "unit")          { chooseOption(target.dataset.key, value); return; }
  if (action === "modal-open")    { openModal(); return; }
  if (action === "modal-close")   { closeModal(); return; }
  if (action === "modal-backdrop"){ if (event.target === target) closeModal(); return; }
  if (action === "zoom-open")     { openZoom(); return; }
  if (action === "preview-reset") { resetPreviewPosition(); return; }
  if (action === "zoom-close")    { closeZoom(); return; }
  if (action === "try-continue")  { submitTryOnPreview(); return; }
  if (action === "checkout-now") { checkoutNow(); return; }
  if (action === "reserve-now")  { reserveNow(); return; }
  if (action === "submit-contact"){ submitContact(); return; }

  if (action === "swatch") {
    chooseCollection(target.dataset.collection, {
      name: target.dataset.swatch,
      img: target.dataset.img,
      referenceId: target.dataset.ref,
      cleanReferenceId: target.dataset.ref,
    });
    return;
  }

  if (action === "option") {
    chooseOption(target.dataset.key, value);
    return;
  }
}

function handleInput(event) {
  const target = event.target.closest("[data-action]");
  if (!target) return;

  const action = target.dataset.action;

  if (action === "measurement") { updateMeasurementField(target.dataset.key, target.value); return; }
  if (action === "contact")     { updateContactField(target.dataset.key, target.value); return; }
  if (action === "try-field")   { updateTryField(target.dataset.key, target.value); return; }

  if (action === "monogram") {
    state.monogram = target.value.slice(0, 20);
    clearTryOnResult();
    container.querySelectorAll("[data-monogram-counter]").forEach((el) => {
      el.textContent = state.monogram.length + "/20";
    });
    container.querySelectorAll('[data-action="monogram"]').forEach((el) => {
      if (el !== target) el.value = state.monogram;
    });
    monogramPreview.update(state.monogram);
    scheduleTailormateSync();
  }
}

function handleChange(event) {
  const target = event.target.closest("[data-action]");
  if (!target || target.dataset.action !== "try-photo") return;
  const file = target.files && target.files[0];
  if (!file) return;
  if (!VALID_TRY_ON_PERSON_FORMATS.includes(file.type)) {
    state.tryPhoto = "";
    clearTryOnResult();
    state.tryError = "Invalid person image format. Please upload a JPG or PNG file.";
    render();
    return;
  }
  const reader = new FileReader();
  reader.onload = () => {
    state.tryPhoto = String(reader.result || "");
    state.tryError = "";
    clearTryOnResult();
    render();
  };
  reader.readAsDataURL(file);
}

// ─── SVG icons ───────────────────────────────────────────────────────────────

function checkIcon() {
  return '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path></svg>';
}

function iconArrowUpRight() {
  return '<svg class="btn-arrow" viewBox="0 0 24 24" width="13" height="13" fill="none" aria-hidden="true"><path d="M7 17 17 7M9 7h8v8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path></svg>';
}

function iconFabrics() {
  return '<svg viewBox="0 0 48 48" fill="none" aria-hidden="true"><rect x="4" y="4" width="40" height="40" rx="3" fill="rgba(200,169,106,0.15)"></rect><line x1="4" y1="12" x2="44" y2="12" stroke="#C8A96A" stroke-width="1.2"></line><line x1="4" y1="20" x2="44" y2="20" stroke="#C8A96A" stroke-width="1.2"></line><line x1="4" y1="28" x2="44" y2="28" stroke="#C8A96A" stroke-width="1.2"></line><line x1="4" y1="36" x2="44" y2="36" stroke="#C8A96A" stroke-width="1.2"></line><line x1="12" y1="4" x2="12" y2="44" stroke="rgba(200,169,106,0.4)" stroke-width="1.2"></line><line x1="20" y1="4" x2="20" y2="44" stroke="rgba(200,169,106,0.4)" stroke-width="1.2"></line><line x1="28" y1="4" x2="28" y2="44" stroke="rgba(200,169,106,0.4)" stroke-width="1.2"></line><line x1="36" y1="4" x2="36" y2="44" stroke="rgba(200,169,106,0.4)" stroke-width="1.2"></line></svg>';
}

function iconLining() {
  return '<svg viewBox="0 0 48 48" fill="none" aria-hidden="true"><circle cx="16" cy="16" r="9" fill="rgba(200,169,106,0.7)"></circle><circle cx="32" cy="16" r="9" fill="rgba(26,58,90,0.7)"></circle><circle cx="16" cy="32" r="9" fill="rgba(90,26,26,0.7)"></circle><circle cx="32" cy="32" r="9" fill="rgba(42,42,42,0.7)"></circle></svg>';
}

function iconButtons() {
  return '<svg viewBox="0 0 48 48" fill="none" aria-hidden="true"><circle cx="24" cy="20" r="8" stroke="#C8A96A" stroke-width="1.3" fill="rgba(200,169,106,0.12)"></circle><circle cx="24" cy="20" r="4.5" stroke="#C8A96A" stroke-width="0.9" fill="none"></circle><circle cx="22" cy="18.5" r="1" fill="#C8A96A"></circle><circle cx="26" cy="18.5" r="1" fill="#C8A96A"></circle><circle cx="22" cy="21.5" r="1" fill="#C8A96A"></circle><circle cx="26" cy="21.5" r="1" fill="#C8A96A"></circle><line x1="24" y1="30" x2="24" y2="40" stroke="rgba(200,169,106,0.4)" stroke-width="1.2"></line></svg>';
}

function iconButtoning() {
  return '<svg viewBox="0 0 48 48" fill="none" aria-hidden="true"><path d="M14 6 L14 42 L24 38 L34 42 L34 6 Z" stroke="#C8A96A" stroke-width="1.3" fill="rgba(200,169,106,0.08)"></path><line x1="24" y1="14" x2="24" y2="38" stroke="#C8A96A" stroke-width="0.9"></line><circle cx="24" cy="20" r="2.5" fill="rgba(200,169,106,0.5)" stroke="#C8A96A" stroke-width="1"></circle><circle cx="24" cy="29" r="2.5" fill="rgba(200,169,106,0.5)" stroke="#C8A96A" stroke-width="1"></circle></svg>';
}

function iconPockets() {
  return '<svg viewBox="0 0 48 48" fill="none" aria-hidden="true"><rect x="8" y="24" width="32" height="18" rx="2" stroke="#C8A96A" stroke-width="1.2" fill="rgba(200,169,106,0.08)"></rect><path d="M14 24c0 6.5 4.5 12 10 12s10-5.5 10-12" stroke="#C8A96A" stroke-width="1.2"></path></svg>';
}

function iconVents() {
  return '<svg viewBox="0 0 48 48" fill="none" aria-hidden="true"><path d="M13 9h22l-3 30H16L13 9Z" stroke="#C8A96A" stroke-width="1.2" fill="rgba(200,169,106,0.08)"></path><path d="M17 14h14" stroke="#C8A96A" stroke-width="1.1"></path><path d="M17 22h14" stroke="#C8A96A" stroke-width="1.1"></path><path d="M17 30h14" stroke="#C8A96A" stroke-width="1.1"></path></svg>';
}

function iconMonogram() {
  return '<svg viewBox="0 0 48 48" fill="none" aria-hidden="true"><path d="M9 34c6-13 11-19 15-19 2.5 0 4.4 1.8 4.4 4.2 0 4.6-7.4 6.6-7.4 11.6 0 2.6 1.9 4.3 4.5 4.3 4 0 7.3-2.9 11.5-9.9" stroke="#C8A96A" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"></path></svg>';
}

// ─── Bootstrap ───────────────────────────────────────────────────────────────

container.addEventListener("click", handleClick);
container.addEventListener("input", handleInput);
container.addEventListener("change", handleChange);
window.addEventListener("resize", schedulePreviewResize);
window.addEventListener("orientationchange", schedulePreviewResize);

render();
loadSceneCatalog();
loadDesignCatalog();
