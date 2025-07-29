function mapCalculatorInit() {

    // 1. Variable Definitions
    const container = document.getElementById('asura-content-container');
    if (!container) return;

    const prices = container.querySelectorAll('.price');
    const counts = container.querySelectorAll('.count');
    const totals = container.querySelectorAll('.total');
    const divineRateInput = container.querySelector('#divineRate');
    const totalChaosEl = container.querySelector('#totalChaos');
    const totalDivineEl = container.querySelector('#totalDivine');
    const copyBtn = container.querySelector('#copySummary');

    if (!prices.length || !divineRateInput || !copyBtn) {
        console.error('Map Calculator essential elements are missing from the DOM.');
        return;
    }

    // 2. Core Calculation Logic
    function calculateAll() {
        let totalChaos = 0;
        for (let i = 0; i < prices.length; i++) {
            const p = Number(prices[i].value) || 0;
            const c = Number(counts[i].value) || 0;
            const s = p * c;
            totals[i].innerText = s.toLocaleString();
            totalChaos += s;
        }
        const divineRate = Number(divineRateInput.value) || 1;
        totalChaosEl.innerText = totalChaos.toLocaleString();
        totalDivineEl.innerText = Number((totalChaos / divineRate).toFixed(2)).toLocaleString();
    }

    // 3. Data Fetching Functions
    async function fetchMapPricesViaWP() {
        try {
            const response = await fetch('/wp-json/asura/v1/mapprices');
            if (!response.ok) throw new Error(`WP REST API Error: ${response.status}`);
            const mapPrices = await response.json();

            prices.forEach(inputEl => {
                const mapName = inputEl.dataset.map;
                if (mapName && mapPrices[mapName] !== undefined) {
                    inputEl.value = Number(mapPrices[mapName]).toFixed(0);
                }
            });
            calculateAll();
        } catch (err) {
            console.error("Failed to fetch map prices:", err);
        }
    }

    async function fetchDivineRate() {
        if (!divineRateInput) return;
        try {
            const response = await fetch('/wp-json/asura/v1/divinerate');
            if (!response.ok) throw new Error(`WP REST API Error: ${response.status}`);
            const data = await response.json();
            
            if (data && data.chaosEquivalent) {
                divineRateInput.value = Math.round(data.chaosEquivalent);
                calculateAll();
            }
        } catch (err) {
            console.error("Failed to fetch Divine Rate:", err);
            divineRateInput.value = '';
            divineRateInput.placeholder = 'Failed to load';
        }
    }

    // 4. Event Listeners
    prices.forEach(inputEl => inputEl.addEventListener('input', calculateAll));
    counts.forEach(inputEl => inputEl.addEventListener('input', calculateAll));
    divineRateInput.addEventListener('input', calculateAll);

    document.addEventListener('langChanged', function(event) {
        const lang = event.detail.lang;
        const btn = container.querySelector('#copySummary');
        if (btn) {
            btn.innerText = (lang === 'ko') ? '판매 문구 복사' : 'Copy Summary';
            btn.style.backgroundColor = ''; 
        }
    });

    // 5. Copy to Clipboard Logic
    copyBtn.addEventListener('click', () => {
        const lang = document.documentElement.lang || 'ko';
        const lines = [];
        let total = 0;

        for (let i = 0; i < prices.length; i++) {
            const priceVal = Number(prices[i].value) || 0;
            const countVal = Number(counts[i].value) || 0;

            if (countVal > 0 && priceVal > 0) {
                const row = prices[i].closest('tr');
                const name = row.querySelector('.map-name-cell').textContent.trim();
                lines.push(`${name} (${priceVal}c × ${countVal})`);
                total += priceVal * countVal;
            }
        }

        if (lines.length === 0) return;

        const divineRate = Number(divineRateInput.value) || 1;
        const div = Math.floor(total / divineRate);
        const restChaos = Math.round(total % divineRate);

        let postfix = '';
        if (div > 0 && restChaos > 0) {
            postfix = `${div} div ${restChaos}c`;
        } else if (div > 0) {
            postfix = `${div} div`;
        } else {
            postfix = `${restChaos}c`;
        }

        const result = 'WTS ' + lines.join(' + ') + ` = ${postfix} (by Asura)`;

        navigator.clipboard.writeText(result).then(() => {
            const originalKoText = '판매 문구 복사';
            const originalEnText = 'Copy Summary';
            
            copyBtn.textContent = (lang === 'ko') ? '복사됨!' : 'Copied!';
            copyBtn.style.backgroundColor = '#d43f3a';
            setTimeout(() => {
                copyBtn.textContent = (lang === 'ko') ? originalKoText : originalEnText;
                copyBtn.style.backgroundColor = '';
            }, 1500);
        });
    });

    // 6. Initializer Calls
    fetchMapPricesViaWP();
    fetchDivineRate();
}

// 7. Expose Initializer to Global Scope
window.mapCalculatorInit = mapCalculatorInit;