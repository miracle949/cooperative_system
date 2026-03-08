const VEHICLES = [
    { label: "UV's",      qtyName: 'total_uv',        plateName: 'uv_plate_no' },
    { label: 'TAXI',      qtyName: 'total_taxi',       plateName: 'taxi_plate_no' },
    { label: 'BUS',       qtyName: 'total_bus',        plateName: 'bus_plate_no' },
    { label: 'MINI BUS',  qtyName: 'total_mini_bus',   plateName: 'mini_bus_plate_no' },
    { label: 'JEEP',      qtyName: 'total_jeep',       plateName: 'jeep_plate_no' },
    { label: 'MULTI-CAB', qtyName: 'total_multi_cab',  plateName: 'multi_cab_plate_no' },
    { label: 'TRICYCLE',  qtyName: 'total_tricycle',   plateName: 'tricycle_plate_no' },
];

const state = {};
VEHICLES.forEach(v => state[v.qtyName] = { qty: 0, plates: [] });

function syncHiddenInputs(vehicle) {
    const form = document.getElementById('form');
    if (!form) return;

    form.querySelectorAll(`.vh-${vehicle.qtyName}`).forEach(el => el.remove());

    const qty = state[vehicle.qtyName].qty;
    const plates = state[vehicle.qtyName].plates;

    if (qty <= 0 || !plates.length) return;

    plates.forEach((plateValue, index) => {
        const trimmed = (plateValue || '').trim();
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = vehicle.plateName + '[' + index + ']';
        hidden.value = trimmed;
        hidden.classList.add('vh-' + vehicle.qtyName);
        form.appendChild(hidden);
    });
}

function buildCards() {
    const grid = document.getElementById('vehiclesGrid');
    grid.innerHTML = '';

    VEHICLES.forEach(vehicle => {
        const cardId = 'card-' + vehicle.qtyName;
        const containerId = 'plates-' + vehicle.qtyName;

        const card = document.createElement('div');
        card.className = 'vehicle-card';
        card.id = cardId;

        card.innerHTML = `
            <div class="card-header">
                <span class="vehicle-type">${vehicle.label}</span>
                <div class="qty-wrapper">
                    <span class="qty-label">#</span>
                    <input
                        class="qty-input"
                        type="number"
                        name="${vehicle.qtyName}"
                        min="0" max="99"
                        placeholder="0"
                        onkeydown="if(event.key==='e'||event.key==='E'||event.key==='+'||event.key==='-') event.preventDefault();"
                    />
                </div>
            </div>
            <div class="card-body">
                <div class="plates-container" id="${containerId}">
                    <span class="empty-state">Enter quantity to add plates</span>
                </div>
            </div>
        `;

        grid.appendChild(card);

        card.querySelector('.qty-input').addEventListener('input', function () {
            const val = parseInt(this.value) || 0;
            const clamped = Math.max(0, Math.min(99, val));
            if (val !== clamped) this.value = clamped;
            handleQtyChange(vehicle, clamped, this);
        });
    });
}

function handleQtyChange(vehicle, qty, inputEl) {
    const card = document.getElementById('card-' + vehicle.qtyName);
    const container = document.getElementById('plates-' + vehicle.qtyName);

    const existingValues = Array.from(container.querySelectorAll('.plate-input')).map(i => i.value);
    state[vehicle.qtyName].qty = qty;

    if (qty > 0) {
        inputEl.classList.add('has-value');
        card.classList.add('has-data');
    } else {
        inputEl.classList.remove('has-value');
        card.classList.remove('has-data');
    }

    container.innerHTML = '';

    if (qty === 0) {
        container.innerHTML = '<span class="empty-state">Enter quantity to add plates</span>';
        state[vehicle.qtyName].plates = [];
        syncHiddenInputs(vehicle);
        return;
    }

    state[vehicle.qtyName].plates = Array(qty).fill('').map((_, i) => existingValues[i] || '');

    for (let i = 0; i < qty; i++) {
        const row = document.createElement('div');
        row.className = 'plate-row';
        row.style.animationDelay = (i * 25) + 'ms';

        const idx = document.createElement('span');
        idx.className = 'plate-index';
        idx.textContent = (i + 1) + '.';

        const input = document.createElement('input');
        input.className = 'plate-input';
        input.type = 'text';
        input.name = vehicle.plateName + '[' + i + ']'; 
        input.maxLength = 12;
        input.placeholder = 'Enter plate number';
        input.value = state[vehicle.qtyName].plates[i];
        if (input.value) input.classList.add('filled');

        input.onkeydown = function (event) {
            if (['+', '=', '?', '.', ',', '(', ')'].includes(event.key)) event.preventDefault();
        };

        input.oninput = function () {
            this.value = this.value.toUpperCase();
            state[vehicle.qtyName].plates[i] = this.value;
            this.classList.toggle('filled', this.value.length > 0);
            syncHiddenInputs(vehicle);
        };

        row.appendChild(idx);
        row.appendChild(input);
        container.appendChild(row);
    }

    syncHiddenInputs(vehicle);
}

buildCards();