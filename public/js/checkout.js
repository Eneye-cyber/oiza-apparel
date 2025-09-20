document.getElementById('create_account').addEventListener('change', function () {
  document.getElementById('password_fields').classList.toggle('hidden', !this.checked);
});

document.getElementById('billing_same_as_shipping').addEventListener('change', function () {
  document.getElementById('billing_fields').classList.toggle('hidden', this.checked);
});
document.addEventListener('DOMContentLoaded', function () {

  const populateCountryDropdown = (countries) => {
    const countrySelect = document.getElementById('country');
    countries.forEach(country => {
      const option = document.createElement('option');
      option.value = country.code;
      option.textContent = country.name;
      countrySelect.appendChild(option);
    });
  };
  const fetchCountries = async () => {
    try {
      const response = await fetch('/v1/countries');
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      const countries = await response.json();
      populateCountryDropdown(countries);
    } catch (error) {
      console.error('Error fetching countries:', error);
    }
  };
  fetchCountries();
});

const fetchStates = async (countryCode) => {
  try {
    const response = await fetch(`/v1/countries/${countryCode}`);
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    const states = await response.json();
    return states;
  } catch (error) {
    console.error('Error fetching states:', error);
    return [];
  }
};

const fetchShippingMethods = async (type, id) => {
  try {
    const response = await fetch(`/v1/shipping/${type}/${id}`);
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    const states = await response.json();
    return states;
  } catch (error) {
    console.error('Error fetching states:', error);
    return [];
  }
}

const populateShippingMethods = (methods) => {
  const shippingMethodsContainer = document.getElementById('shipping_methods');
  shippingMethodsContainer.innerHTML = ''; // Reset shipping methods

  if (Array.isArray(methods) && methods.length > 0) {
    methods.forEach(method => {
      const methodDiv = document.createElement('div');
      methodDiv.classList.add('flex', 'items-center');

      const radioInput = document.createElement('input');
      radioInput.type = 'radio';
      radioInput.id = method.name.replace(/\s+/g, '-').toLowerCase();
      radioInput.name = 'shipping_method';
      radioInput.value = method.name;
      radioInput.dataset.cost = method.delivery_cost;
      radioInput.classList.add('mr-2');
      radioInput.required = true;
      radioInput.onchange = function(e) {
          const elem = e.target
          const shippingFee = document.getElementById('shipping_fee');
          shippingFee.textContent = formatMoney(parseFloat(elem.dataset.cost), 'NGN', 'en-NG', { minimumFractionDigits: 2 });
      }

      const label = document.createElement('label');
      label.htmlFor = method.name.replace(/\s+/g, '-').toLowerCase();
      label.classList.add('text-sm');
      label.textContent = `${method.name} (${method.delivery_min_days} - ${method.delivery_max_days} business days) - ${formatMoney(parseFloat(method.delivery_cost), 'NGN', 'en-NG', { minimumFractionDigits: 2 })}`;

      methodDiv.appendChild(radioInput);
      methodDiv.appendChild(label);
      shippingMethodsContainer.appendChild(methodDiv);
    });
  } else {
    const noMethodDiv = document.createElement('div');
    noMethodDiv.classList.add('text-sm', 'text-black', 'opacity-60');
    noMethodDiv.textContent = 'No shipping methods available for this country.';
    shippingMethodsContainer.appendChild(noMethodDiv);
  }

}
const populateStateDropdown = (states) => {
  const stateSelect = document.getElementById('state');
  stateSelect.innerHTML = '<option value="">Select state</option>'

   if (Array.isArray(states) && states.length > 0) {
    states.forEach(state => {
      const option = document.createElement('option');
      option.value = state.id;
      option.dataset.id = state.id
      option.textContent = state.name;
      stateSelect.appendChild(option);
    });
   }else{

   }
}
document.getElementById('country').addEventListener('change', async function () {
  const selectedCountry = this.value;
  const stateSelect = document.getElementById('state');
  stateSelect.innerHTML = '<option value="">Select State</option>'; // Reset states
  // const shippingFee = document.getElementById('shipping_fee');

  stateSelect.disabled = true; // Disable while loading
  // shippingFee.textContent = 'Loading...';

  const countryData = await fetchStates(selectedCountry);
  const shippingMethods = countryData.active_methods || [];
  populateShippingMethods(shippingMethods)
  populateStateDropdown(countryData.states || []);
  stateSelect.disabled = false; // Disable while loading
  console.log(countryData)
})



document.getElementById('state').addEventListener('change', async function () {
  const selectedState = this.value;
  const stateSelect = document.getElementById('state');

  stateSelect.disabled = true; // Disable while loading
  // shippingFee.textContent = 'Loading...';

  const stateData = await fetchShippingMethods('state', selectedState);
  console.log(stateData)
  const shippingMethods = stateData.active_methods || [];
  populateShippingMethods(shippingMethods)
  stateSelect.disabled = false; // Disable while loading

})

function formatMoney(amount, currencyCode = 'USD', locale = 'en-US', options = {}) {
  // Default options
  const defaultOptions = {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
    ...options
  };

  // Validate inputs
  if (typeof amount !== 'number' || isNaN(amount)) {
    throw new Error('Amount must be a valid number');
  }

  if (typeof currencyCode !== 'string' || currencyCode.length !== 3) {
    throw new Error('Currency code must be a 3-letter string (e.g., USD, EUR)');
  }

  try {
    // Use Intl.NumberFormat for robust formatting
    return new Intl.NumberFormat(locale, {
      style: 'currency',
      currency: currencyCode,
      ...defaultOptions
    }).format(amount);
  } catch (error) {
    // Fallback formatting if something goes wrong
    console.error('Error formatting money:', error);
    return `${currencyCode} ${amount.toFixed(2)}`;
  }
}