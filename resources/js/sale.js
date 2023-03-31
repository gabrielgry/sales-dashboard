import IMask from "imask";
import currency from "currency.js";

/**
 * Items
 */
(function () {
    const addItemButton = document.getElementById('add-item-button');
    const itemTemplate = document.getElementById('item-template');
    const itemTable = document.getElementById('item-table');
    const totalPriceElement = document.getElementById('total-price');
    const installmentList = document.getElementById('installments-list');

    let index = 0;

    let itemsElements = itemTable.querySelectorAll('tr');
    itemsElements.forEach(function (item) {
        ++index;
        registerItem(index, item);
        calculateTotalPrice();
    })

    addItemButton.addEventListener('click', function () {
        ++index;
        let item = itemTemplate.content.cloneNode(true).querySelector('tr');

        registerItem(index, item);
        itemTable.append(item);
    });

    function registerItem(index, item) {
        let productElement = item.querySelector('[product]');
        let priceElement = item.querySelector('[price]');
        let removeButton = item.querySelector('[remove]');

        productElement.setAttribute('name', "item[" + index + "][product]");
        priceElement.setAttribute('name', "item[" + index + "][price]");

        let priceMask = new IMask(priceElement, {
            mask: Number,
            scale: 2,
            min: 0,
            normalizeZeros: true,
            radix: '.',
            mapToRadix: [','],
        })

        priceMask.on('complete', function () {
            calculateTotalPrice();
            installmentList.replaceChildren();
        });

        removeButton.addEventListener('click', function () {
            priceMask.destroy();
            item.remove();
            installmentList.replaceChildren();
            calculateTotalPrice();
        })
    }

    // Total price calculated in cents to avoid foat point precision errors
    function calculateTotalPrice() {
        const priceElements = document.querySelectorAll('#item-table input[price]');

        let totalPrice = currency(0);
        priceElements.forEach(function (priceElement) {
            const price = currency(priceElement.value);

            totalPrice = currency(totalPrice).add(price);
        });

        totalPriceElement.setAttribute('data-total', totalPrice);
        totalPriceElement.innerText = new Intl.NumberFormat('pt-BR',
            { style: 'currency', currency: 'BRL'
        }).format(totalPrice);
    }
})();

/**
 * Installments
 */
(function () {
    const generateInstallmentsButton = document.getElementById('generate-installments-button');
    const installmentTotalError = document.getElementById('installment-total-error');
    const totalPriceElement = document.getElementById('total-price');
    const installmentsTemplate = document.getElementById('installment-template');
    const installmentsQuantityInput = document.getElementById('installments_quantity');
    const installmentList = document.getElementById('installments-list');

    let installmentsQuantityMask = new IMask(installmentsQuantityInput, {
        mask: Number,
        scale: 0,
        min: 1,
        max: 24,
    })

    let installmentElements = installmentList.querySelectorAll('li');
    installmentElements.forEach(function (installmentElement) {
        let valueElement = installmentElement.querySelector('input[amount]');
        let valueMask = new IMask(valueElement, {
            mask: Number,
                scale: 2,
                min: 0,
                normalizeZeros: true,
                radix: '.',
                mapToRadix: [','],
        })
        valueElement.addEventListener('input', verifyInstallmentsTotal);
    });

    generateInstallmentsButton.addEventListener('click', function () {
        installmentList.replaceChildren();

        const installmentsQuantity = parseInt(installmentsQuantityMask.value);
        const totalPrice = currency(totalPriceElement.getAttribute('data-total'));

        let installmentValues = currency(totalPrice).distribute(installmentsQuantity);

        for (let index = 0; index < installmentsQuantity; index++) {
            const installment = installmentsTemplate.content.cloneNode(true).querySelector('li');

            let dateElement = installment.querySelector('input[date]');
            let valueElement = installment.querySelector('input[amount]');
            let observationsElement = installment.querySelector('[observations]');

            dateElement.setAttribute('name', "installment[" + index + "][date]");
            const now = new Date();
            const dueDate = new Date(now.getFullYear(), now.getMonth() + 2 + index, now.getDay())
            dateElement.valueAsDate = dueDate;

            valueElement.setAttribute('name', "installment[" + index + "][value]");
            let valueMask = new IMask(valueElement, {
                mask: Number,
                scale: 2,
                min: 0,
                normalizeZeros: true,
                radix: '.',
                mapToRadix: [','],
            })

            valueMask.value = installmentValues[index].toString();
            valueElement.addEventListener('input', verifyInstallmentsTotal);

            observationsElement.setAttribute('name', "installment[" + index + "][observations]");

            installmentList.append(installment);
        }
    })

    function verifyInstallmentsTotal() {
        const valueElements = installmentList.querySelectorAll('input[amount]');
        const totalPrice = currency(totalPriceElement.getAttribute('data-total'));

        let installmentTotal = currency(0);
        valueElements.forEach(function (valueElement) {
            let value = currency(valueElement.valueAsNumber);

            installmentTotal = currency(installmentTotal).add(value);
        })

        if (installmentTotal.intValue != totalPrice.intValue) {
            installmentTotalError.classList.remove('hidden');
        } else if (!installmentTotalError.classList.contains('hidden')) {
            installmentTotalError.classList.add('hidden');
        }
    }
})();
