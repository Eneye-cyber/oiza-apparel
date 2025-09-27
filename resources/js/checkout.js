// checkout.js — production-ready refactor
(() => {
  'use strict';

  /* -------------------------
     Configuration
     ------------------------- */
  const API = {
    countries: '/v1/countries',
    country: (code) => `/v1/countries/${encodeURIComponent(code)}`,
    shipping: (type, id) => `/v1/shipping/${encodeURIComponent(type)}/${encodeURIComponent(id)}`
  };
  const FETCH_TIMEOUT_MS = 15000; // 15s
  const CURRENCY_CODE = 'NGN';
  const LOCALE = 'en-NG';

  /* -------------------------
     Utilities
     ------------------------- */
  function safeSlug(str) {
    if (!str) return '';
    return String(str).toLowerCase().replace(/[^\w\s-]/g, '').trim().replace(/\s+/g, '-');
  }

  function timeoutSignal(timeoutMs = FETCH_TIMEOUT_MS) {
    const controller = new AbortController();
    const timeout = setTimeout(() => controller.abort(), timeoutMs);
    return { signal: controller.signal, clear: () => clearTimeout(timeout), controller };
  }

  async function fetchJson(url, { signal } = {}) {
    const opts = { method: 'GET', headers: { 'Accept': 'application/json' }, signal };
    const res = await fetch(url, opts);
    if (!res.ok) {
      const text = await res.text().catch(() => '');
      const msg = `Request failed (${res.status})`;
      const err = new Error(msg);
      err.status = res.status;
      err.body = text;
      throw err;
    }
    const data = await res.json();
    return data;
  }

  function formatMoneySafe(amount, currencyCode = CURRENCY_CODE, locale = LOCALE, options = {}) {
    if (amount === null || amount === undefined) return '';
    const n = Number(amount);
    if (Number.isNaN(n)) return '';
    const defaultOptions = { minimumFractionDigits: 2, maximumFractionDigits: 2, ...options };
    try {
      return new Intl.NumberFormat(locale, { style: 'currency', currency: currencyCode, ...defaultOptions }).format(n);
    } catch (err) {
      // fallback simple formatting
      return `${currencyCode} ${(n).toFixed(defaultOptions.minimumFractionDigits)}`;
    }
  }

  function createOption({ value, text, dataset = {} }) {
    const opt = document.createElement('option');
    opt.value = value;
    opt.textContent = text;
    Object.keys(dataset).forEach(k => (opt.dataset[k] = dataset[k]));
    return opt;
  }

  /* -------------------------
     DOM Elements (cached)
     ------------------------- */
  const dom = {
    country: document.getElementById('country'),
    state: document.getElementById('state'),
    shippingMethodsContainer: document.getElementById('shipping_methods'),
    shippingFeeEl: document.getElementById('shipping_fee'),
    billingSameAsShipping: document.getElementById('billing_same_as_shipping'),
    billingFields: document.getElementById('billing_fields'),
    // createAccount: document.getElementById('create_account'),
    passwordFields: document.getElementById('password_fields'),
    termsAgreement: document.getElementById('terms_agreement'),
    submitButton: document.querySelector('button[type="submit"]'),
    subtotalEl: document.querySelector('[data-subtotal]') || null, // optional: markup can have data attributes
    totalEl: document.querySelector('.space-y-4 .font-semibold > span:last-child') || null, // fallback selector
    shippingAriaLive: (function () { // create aria-live region if not present
      let el = document.querySelector('#shipping-status-live');
      if (!el) {
        el = document.createElement('div');
        el.id = 'shipping-status-live';
        el.setAttribute('aria-live', 'polite');
        el.className = 'sr-only'; // visually hidden (Tailwind .sr-only)
        document.body.appendChild(el);
      }
      return el;
    })()
  };

  /* -------------------------
     State
     ------------------------- */
  let countriesCache = null;
  let activeFetchControllers = {
    countryFetch: null,
    stateFetch: null
  };
  let selectedShipping = null;
  // subtotal must be pulled from server-rendered value; parse from DOM if available
  let subtotalValue = null;
  (function initSubtotal() {
    // try to read subtotal from server markup: value inside element (e.g. ₦123.45)
    const subtotalText = document.querySelector('[data-subtotal]')?.textContent
      || document.querySelector('.space-y-4 .text-black.opacity-60 + span')?.textContent
      || null;
    if (subtotalText) {
      // remove non-digits except decimal
      const cleaned = subtotalText.replace(/[^\d.-]+/g, '');
      const n = Number(cleaned);
      subtotalValue = Number.isFinite(n) ? n : null;
    }
  })();

  /* -------------------------
     UI helpers
     ------------------------- */
  function setLoadingShipping(isLoading) {
    const container = dom.shippingMethodsContainer;
    if (!container) return;
    container.setAttribute('aria-busy', isLoading ? 'true' : 'false');
    if (isLoading) {
      container.innerHTML = '';
      const p = document.createElement('p');
      p.className = 'text-sm text-black opacity-60';
      p.textContent = 'Loading shipping methods...';
      container.appendChild(p);
      dom.shippingAriaLive.textContent = 'Loading shipping methods';
    }
  }

  function setShippingError(message) {
    dom.shippingMethodsContainer.innerHTML = '';
    const err = document.createElement('div');
    err.className = 'text-sm text-red-600';
    err.textContent = message || 'Could not load shipping methods. Please try again.';
    dom.shippingMethodsContainer.appendChild(err);
    dom.shippingAriaLive.textContent = message || 'Error loading shipping methods';
  }

  function clearShippingMethods() {
    dom.shippingMethodsContainer.innerHTML = '';
    const p = document.createElement('p');
    p.className = 'text-sm text-black opacity-60';
    p.textContent = 'Select your shipping method based on your location.';
    dom.shippingMethodsContainer.appendChild(p);
  }

  function updateShippingFeeDisplay(amount) {
    dom.shippingFeeEl.textContent = amount !== null ? formatMoneySafe(amount) : 'TBD';
  }

  function updateTotalDisplay() {
    // if subtotalValue available, update total
    if (subtotalValue === null) return;
    const shippingCost = selectedShipping?.delivery_cost ? Number(selectedShipping.delivery_cost) : 0;
    const total = subtotalValue + shippingCost;
    // find the total element and update textual representation
    // Attempt to update your visible total
    const totalEl = dom.totalEl;
    if (totalEl) {
      totalEl.textContent = formatMoneySafe(total);
    }
  }

  function setSubmitEnabled(enabled) {
    if (!dom.submitButton) return;
    dom.submitButton.disabled = !enabled;
  }

  function validateFormReady() {
    // Basic client-side readiness: shipping chosen and terms checked.
    const termsOk = dom.termsAgreement ? dom.termsAgreement.checked : true;
    const shippingOk = !!selectedShipping;
    // also ensure the form required fields are satisfied? Leave detailed validation to server,
    // but we can require shipping and terms
    setSubmitEnabled(termsOk && shippingOk);
  }

  /* -------------------------
     Populate helpers
     ------------------------- */
  function populateCountryDropdown(countries) {
    if (!dom.country) return;
    dom.country.innerHTML = '';
    const old = dom.country.dataset.old || '';
    dom.country.dataset.old = ''; // reset stored value

    dom.country.appendChild(createOption({ value: '', text: 'Select Country' }));

    countries.forEach(country => {
      const code = country.code ?? country.id ?? country.iso ?? '';
      const name = country.name ?? country.title ?? code;
      const opt = createOption({ value: code, text: name });
      dom.country.appendChild(opt);
    });
    dom.country.value = old;
    dom.country.dispatchEvent(new Event('change'))
  }

  function populateStateDropdown(states) {
    if (!dom.state) return;
    dom.state.innerHTML = '';
    const old = dom.state.dataset.old || '';
    dom.state.dataset.old = ''; // reset stored value
    dom.state.appendChild(createOption({ value: '', text: 'Select State' }));
    if (!Array.isArray(states) || states.length === 0) {
      return;
    }
    states.forEach(state => {
      const opt = createOption({
        value: state.id ?? state.code ?? state.name ?? '',
        text: state.name ?? state.title ?? ''

      });
      dom.state.appendChild(opt);
    });
    dom.state.value = old;
    dom.state.dispatchEvent(new Event('change'))
  }

  function addBusinessDays(startDate, days) {
    const result = new Date(startDate);
    let addedDays = 0;
    while (addedDays < days) {
      result.setDate(result.getDate() + 1);
      const dayOfWeek = result.getDay();
      if (dayOfWeek !== 0 && dayOfWeek !== 6) { // Skip Sundays (0) and Saturdays (6)
        addedDays++;
      }
    }
    return result;
  }

  function formatDate(date) {
    return date.toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric'
    });
  }

  function populateShippingMethods(methods) {
    const container = dom.shippingMethodsContainer;
    container.innerHTML = '';

    if (!Array.isArray(methods) || methods.length === 0) {
      const noMethodDiv = document.createElement('div');
      noMethodDiv.className = 'text-sm text-black opacity-60';
      noMethodDiv.textContent = 'No shipping methods available for this country.';
      container.appendChild(noMethodDiv);
      selectedShipping = null;
      updateShippingFeeDisplay(null);
      updateTotalDisplay();
      validateFormReady();
      return;
    }

    // ensure container is accessible as a radiogroup
    container.setAttribute('role', 'radiogroup');
    container.setAttribute('aria-labelledby', 'shipping-methods-label');

    const today = new Date();

    methods.forEach((method, idx) => {
      // sanitize / validate method
      console.log(method, idx)
      const id = method.id ?? method.code ?? `generated-${idx}-${safeSlug(method.name)}`;
      const name = String(method.name ?? `Method ${idx + 1}`);
      const cost = Number(method.delivery_cost ?? 0);
      const minDays = method.delivery_min_days ?? method.min_days ?? 0;
      const maxDays = method.delivery_max_days ?? method.max_days ?? 0;

      const estimatedMinDate = addBusinessDays(today, minDays);
      const estimatedMaxDate = addBusinessDays(today, maxDays);
      const estimatedDateRange = `${formatDate(estimatedMinDate)} – ${formatDate(estimatedMaxDate)}`;

      const wrapper = document.createElement('div');
      wrapper.className = 'flex items-start py-2 border-b border-gray-100 last:border-b-0';

      const radio = document.createElement('input');
      radio.type = 'radio';
      radio.id = `shipping-method-${safeSlug(id)}`;
      radio.name = 'shipping_method';
      radio.value = id;
      radio.dataset.cost = String(cost);
      radio.required = true;
      radio.className = 'mt-1 mr-3 flex-shrink-0';

      radio.addEventListener('change', (e) => {
        selectedShipping = { id, name, delivery_cost: cost, delivery_min_days: minDays, delivery_max_days: maxDays };
        updateShippingFeeDisplay(cost);
        updateTotalDisplay();
        validateFormReady();
        dom.shippingAriaLive.textContent = `Selected ${name} ${formatMoneySafe(cost)}. Estimated delivery ${estimatedDateRange}.`;
      });

      const label = document.createElement('label');
      label.htmlFor = radio.id;
      label.className = 'flex justify-between items-start w-full cursor-pointer';

      const methodInfo = document.createElement('div');
      methodInfo.className = 'flex flex-col space-y-1';

      const methodName = document.createElement('span');
      methodName.textContent = name;
      methodName.className = 'text-sm font-medium text-gray-900';

      const deliverySpan = document.createElement('span');
      deliverySpan.textContent = `Estimated delivery ${estimatedDateRange}`;
      deliverySpan.className = 'text-xs text-gray-500';

      methodInfo.appendChild(methodName);
      methodInfo.appendChild(deliverySpan);

      const costSpan = document.createElement('span');
      costSpan.textContent = formatMoneySafe(cost);
      costSpan.className = 'text-sm font-semibold text-gray-900 ml-auto';

      label.appendChild(methodInfo);
      label.appendChild(costSpan);

      wrapper.appendChild(radio);
      wrapper.appendChild(label);
      container.appendChild(wrapper);
    });

    // focus first radio for keyboard users
    const firstRadio = container.querySelector('input[type="radio"]');
    if (firstRadio) firstRadio.focus();
  }

  /* -------------------------
     Fetch flows with abort & timeout
     ------------------------- */
  async function loadCountries() {
    if (countriesCache) {
      populateCountryDropdown(countriesCache);
      return;
    }
    const t = timeoutSignal();
    activeFetchControllers.countryFetch = t.controller;

    try {
      const data = await fetchJson(API.countries, { signal: t.signal });
      if (!Array.isArray(data)) throw new Error('Invalid countries response');
      countriesCache = data;
      populateCountryDropdown(data);
    } catch (err) {
      console.error('Error fetching countries:', err);
      // show minimal UI fallback: keep single select option
      setShippingError('Unable to load countries. Please refresh the page or try again later.');
    } finally {
      t.clear();
      activeFetchControllers.countryFetch = null;
    }
  }

  async function loadCountryDetails(countryCode) {
    if (!countryCode) {
      populateStateDropdown([]);
      clearShippingMethods();
      selectedShipping = null;
      updateShippingFeeDisplay(null);
      validateFormReady();
      return;
    }

    // abort previous country detail fetch if any
    if (activeFetchControllers.countryFetch) {
      try { activeFetchControllers.countryFetch.abort(); } catch (_) { }
    }

    setLoadingShipping(true);
    const t = timeoutSignal();
    activeFetchControllers.countryFetch = t.controller;

    try {
      const data = await fetchJson(API.country(countryCode), { signal: t.signal });
      // expected shape: { states: [...], active_methods: [...] }
      const states = Array.isArray(data.states) ? data.states : [];
      const shippingMethods = Array.isArray(data.active_methods) ? data.active_methods : [];
      populateStateDropdown(states);
      populateShippingMethods(shippingMethods);
    } catch (err) {
      console.error('Error fetching country details:', err);
      populateStateDropdown([]);
      setShippingError('Could not load states/shipping for selected country.');
    } finally {
      setLoadingShipping(false);
      t.clear();
      activeFetchControllers.countryFetch = null;
      validateFormReady();
    }
  }

  async function loadStateShipping(stateId) {
    if (!stateId) {
      // if state cleared, fallback to country-level methods (no change)
      selectedShipping = null;
      updateShippingFeeDisplay(null);
      updateTotalDisplay();
      validateFormReady();
      return;
    }

    if (activeFetchControllers.stateFetch) {
      try { activeFetchControllers.stateFetch.abort(); } catch (_) { }
    }

    setLoadingShipping(true);
    const t = timeoutSignal();
    activeFetchControllers.stateFetch = t.controller;

    try {
      const data = await fetchJson(API.shipping('state', stateId), { signal: t.signal });
      const shippingMethods = Array.isArray(data.active_methods) ? data.active_methods : [];
      populateShippingMethods(shippingMethods);
    } catch (err) {
      console.error('Error fetching state shipping:', err);
      setShippingError('Could not load shipping methods for selected state.');
    } finally {
      setLoadingShipping(false);
      t.clear();
      activeFetchControllers.stateFetch = null;
      validateFormReady();
    }
  }

  /* -------------------------
     Event wiring
     ------------------------- */
  function wireEvents() {
    // create account toggle
    // dom.createAccount?.addEventListener('change', function () {
    //   if (!dom.passwordFields) return;
    //   dom.passwordFields.classList.toggle('hidden', !this.checked);
    //   // If hiding, clear password fields for safety
    //   if (!this.checked) {
    //     const pw = dom.passwordFields.querySelectorAll('input[type="password"]');
    //     pw.forEach(i => i.value = '');
    //   }
    // });

    // billing same as shipping toggle
    dom.billingSameAsShipping?.addEventListener('change', function () {
      if (!dom.billingFields) return;
      dom.billingFields.classList.toggle('hidden', this.checked);
    });

    // country select change
    dom.country?.addEventListener('change', function () {
      const countryCode = this.value;
      // reset state and shipping area while loading
      dom.state.innerHTML = '';
      dom.state.disabled = true;
      loadCountryDetails(countryCode).finally(() => {
        dom.state.disabled = false;
      });
    });

    // state select change
    dom.state?.addEventListener('change', function () {
      const stateId = this.value;
      dom.state.disabled = true;
      loadStateShipping(stateId).finally(() => {
        dom.state.disabled = false;
      });
    });

    // terms and shipping selection guard
    dom.termsAgreement?.addEventListener('change', validateFormReady);

    // initial validation
    validateFormReady();

    // form submission: final validation + attach shipping method to form
    const form = document.querySelector('form');
    if (form) {
      form.addEventListener('submit', (evt) => {
        // guard: ensure shipping selected and terms checked
        if (!selectedShipping) {
          evt.preventDefault();
          setShippingError('Please select a shipping method before continuing.');
          return;
        }
        if (dom.termsAgreement && !dom.termsAgreement.checked) {
          evt.preventDefault();
          alert('Please accept Terms & Conditions to proceed.');
          return;
        }
        // Attach shipping method id / cost to form as hidden inputs
        const existingInputs = form.querySelectorAll('input[name="selected_shipping_id"], input[name="selected_shipping_cost"]');
        existingInputs.forEach(i => i.remove());

        const hidId = document.createElement('input');
        hidId.type = 'hidden';
        hidId.name = 'selected_shipping_id';
        hidId.value = selectedShipping.id;
        form.appendChild(hidId);

        const hidCost = document.createElement('input');
        hidCost.type = 'hidden';
        hidCost.name = 'selected_shipping_cost';
        hidCost.value = selectedShipping.delivery_cost;
        form.appendChild(hidCost);

        // let the form submit normally
      });
    }
  }

  /* -------------------------
     Init
     ------------------------- */
  function init() {
    // populate UI placeholders
    clearShippingMethods();
    updateShippingFeeDisplay(null);
    updateTotalDisplay();

    // wire events
    wireEvents();

    // load countries
    loadCountries();
  }

  // run on DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
