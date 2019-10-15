//*********************************************************************************************************
//method to store current tab in a hidden field
function beforeSavePurchaseOrder()
{
	//Stock current tab in hidden form item
	var currentTabId = purchase_order_tabsJsTabs.activeTab.name;
	document.getElementById('current_tab').value = currentTabId;

	//stock order product log in textbox
	persistantProductGrid.storeLogInTargetInput();
	persistantDeliveryGrid.storeLogInTargetInput();
	
	//submit form
	editForm.submit();
}

//******************************************************************************
//Compute extended costs for 1 product
function getExtendedCostForOneProduct(unitPrice)
{
	var shippingCost = document.getElementById('po_shipping_cost').value;
	var zollCost = document.getElementById('po_zoll_cost').value;
	var ExtendedCostsAmount = parseFloat(shippingCost) + parseFloat(zollCost);
	
	var productCount = 0;
	var productPricesSum = 0;
	var inputs = document.getElementsByTagName('input');
	for (i=0; i < inputs.length; i++) 
	{
		if (inputs[i] && inputs[i].id != null)
		{
			if (inputs[i].id.indexOf('pop_qty_') != -1) 
			{
				var productId = inputs[i].id.replace('pop_qty_', '');
				var Price = parseFloat(document.getElementById('pop_price_ht_' + productId).value);	
				var qty = parseInt(inputs[i].value);
				productCount += qty;
				productPricesSum += qty * Price;
			}
		}
	}	

	var retour = 0;
	if (kCostRepartitionMethod ==  'by_amount')
	{
		if (productPricesSum > 0)
			retour = (ExtendedCostsAmount / productPricesSum) *  unitPrice;
	}
	else
	{	
		if (productCount > 0)
			retour = ExtendedCostsAmount / productCount;
	}

	return retour;
}


//**************************************************************************************************************************************
//open a popup menu to edit sell prices
var win;
function editSellPrice(productId, itemId)
{
	var changeRate = parseFloat(document.getElementById('po_currency_change_rate').value);
	var buyPrice = parseFloat(document.getElementById('pop_price_ht_' + itemId).value);
	var extendedCosts = getExtendedCostForOneProduct(buyPrice);
	var ecoTaxAmount = 0;
	if (kEnableEcoTax)
		parseFloat(document.getElementById('pop_eco_tax_' + itemId).value);
	buyPrice = (parseFloat(buyPrice) + parseFloat(extendedCosts) + parseFloat(ecoTaxAmount)) / changeRate;
	var sellPrice = parseFloat(document.getElementById('product_price_' + productId).value) / changeRate;
	
	document.getElementById('pricer_buy_price').value = buyPrice.toFixed(2);
	document.getElementById('pricer_sell_price').value = sellPrice.toFixed(4);
	document.getElementById('pricer_sell_price_ttc').value =  (sellPrice * kDefaultProductSalePriceRate).toFixed(2);
	
	if (buyPrice > 0)
		document.getElementById('pricer_margin').value = parseInt((sellPrice - buyPrice) / sellPrice * 100);
	else
		document.getElementById('pricer_margin').value = '?';
	document.getElementById('pricer_product_id').value = productId;
	document.getElementById('pricer_item_id').value = itemId;
	
	win = new Window({className: "alphacube", title: "Pricer", width:200, height:400, destroyOnClose:true,closable:true,draggable:false, recenterAuto:true, okLabel: "close"});
	win.setContent('div_dlg_pricer', true, true);
	win.setCloseCallback(onClose);
	win.showCenter();
}

//************************************************************************************************************************************************
//hide pricer popup
function onClose()
{
	document.getElementById('div_dlg_pricer').style.display = 'none';
	return true;
}

//************************************************************************************************************************************************
//
function refreshPricer(source)
{
	var buyPrice = parseFloat(document.getElementById('pricer_buy_price').value);	
	var sellPrice = parseFloat(document.getElementById('pricer_sell_price').value);	
	var sellPriceTtc = parseFloat(document.getElementById('pricer_sell_price_ttc').value);	
	var margin = parseFloat(document.getElementById('pricer_margin').value);	
	switch(source)
	{
		case 'sell_price':
			var margin = 0;
			if (sellPrice > 0)
				margin = (sellPrice - buyPrice) / sellPrice * 100;
			document.getElementById('pricer_margin').value = margin.toFixed(2);
			document.getElementById('pricer_sell_price_ttc').value = (sellPrice*kDefaultProductSalePriceRate).toFixed(2);			
			break;
		case 'sell_price_ttc':
			document.getElementById('pricer_sell_price').value = (sellPriceTtc/kDefaultProductSalePriceRate).toFixed(2);
			var margin = ((sellPriceTtc/kDefaultProductSalePriceRate) - buyPrice) / sellPrice * 100;
			document.getElementById('pricer_margin').value = margin.toFixed(2);
			break;
		case 'margin':
			var sellPrice = buyPrice * (1 + margin / 100);
			document.getElementById('pricer_sell_price').value = sellPrice.toFixed(2);			
			document.getElementById('pricer_sell_price_ttc').value = (sellPrice*kDefaultProductSalePriceRate).toFixed(2);			
			break;
	}
}

//************************************************************************************************************************************************
//
function savePricer()
{
	
	//met a jour dans la page
	var changeRate = parseFloat(document.getElementById('po_currency_change_rate').value);
	var itemId = document.getElementById('pricer_item_id').value;
	var productId = document.getElementById('pricer_product_id').value;
	eval('product_price_' + productId + ' = (parseFloat(document.getElementById(\'pricer_sell_price\').value) * changeRate).toFixed(2)');
	displayMargin(productId, itemId);
	
	//sauvegarde le prix en ajax
	var request = new Ajax.Request(kPricerSaveUrl,
	    {
	        method:'post',
	        onSuccess: function onSuccess(transport)
	        			{
							win.destroy();
							document.getElementById('div_dlg_pricer').style.display = 'none';
	        			},
	        onFailure: function onAddressFailure() 
	        			{
	        				alert('error');
							win.destroy();
							document.getElementById('div_dlg_pricer').style.display = 'none';
	        			},
            parameters: Form.serialize(document.getElementById('div_dlg_pricer'))
	    }
    );
	
}



//************************************************************************************************************************************************
//
function updateOrderProductInformation(orderProductId)
{
	

	//collect datas
	var sellPriceInbaseCurrency =  parseFloat(document.getElementById('product_price_' + orderProductId).value);
	var currencyChangeRate = document.getElementById('po_currency_change_rate').value;
	
	//get buy price
	var buyPriceInOrderCurrency = document.getElementById('pop_price_ht_' + orderProductId).value;
	if (document.getElementById('pop_discount_' + orderProductId))
		buyPriceInOrderCurrency = buyPriceInOrderCurrency * (1 - document.getElementById('pop_discount_' + orderProductId).value / 100);
	
	var extendedCosts = getExtendedCostForOneProduct(buyPriceInOrderCurrency);
	var ecoTaxAmount = 0;
	if (kEnableEcoTax )
		ecoTaxAmount = document.getElementById('pop_eco_tax_' + orderProductId).value;
	if (ecoTaxAmount == '')
		ecoTaxAmount = 0;
	buyPriceInOrderCurrency = parseFloat(buyPriceInOrderCurrency) + parseFloat(extendedCosts) + parseFloat(ecoTaxAmount);
	var margin = '?';
	var buyPriceInBaseCurrency = buyPriceInOrderCurrency / currencyChangeRate;
	
	//display margin
	if (sellPriceInbaseCurrency > 0)
		margin = parseInt((sellPriceInbaseCurrency - buyPriceInBaseCurrency) / sellPriceInbaseCurrency * 100);
	var editLink = ' <img src="' + kEditImageUrl + '" onclick="editSellPrice(' + orderProductId + ');">';
	var txt = sellPriceInbaseCurrency + editLink + '<br>' + margin + '%';
	document.getElementById('div_sellprice_' + orderProductId).innerHTML = txt;
	document.getElementById('div_price_with_extended_cost_' + orderProductId).innerHTML = buyPriceInOrderCurrency.toFixed(2);

	//update real qty if packaging is enabled
	displayFinalQty(orderProductId);
	
	//display subtotal
	if (document.getElementById('subtotal_' + orderProductId))
	{
		var realCost = document.getElementById('pop_price_ht_' + orderProductId).value;
		var qty = document.getElementById('pop_qty_' + orderProductId).value;
                var discountPercent = document.getElementById('pop_discount_' + orderProductId).value;
                var costWithDiscount = realCost * (1 - discountPercent / 100);
                costWithDiscount = costWithDiscount.toFixed(2);
		var subtotal = costWithDiscount * qty;
		document.getElementById('subtotal_' + orderProductId).innerHTML = subtotal.toFixed(2);
	}
}

//************************************************************************************************************************************************
//when packaging is enabled display qty * packaging coef
function displayFinalQty(popId)
{
	//check if packaging is enabled
	if (document.getElementById('span_final_qty_' + popId))
	{
		var qty = document.getElementById('pop_qty_' + popId).value;
		document.getElementById('span_final_qty_' + popId).innerHTML = qty;
	}
}

//**************************************************************************************************************************************
//update product qty based on package count and selected package 
function updateQtyFromPackageCount(itemId)
{
	if (document.getElementById('package_count_' + itemId))
	{
		var packageCount = document.getElementById('package_count_' + itemId).value;
		var packagingId = document.getElementById('pop_packaging_id_' + itemId).value;
		var packagingCoef = 1;
		if ((packagingId != '-1') && (packagingId != ''))
			packagingCoef = eval('packaging_' + packagingId);
			
		var totalQty = packageCount * packagingCoef;
		document.getElementById('pop_qty_' + itemId).value = totalQty;
				
		if (document.getElementById('span_final_qty_' + itemId))
		{
			document.getElementById('span_final_qty_' + itemId).innerHTML = totalQty;
		}
	}
	
	updateOrderProductInformation(itemId);
	
}

//***************************************************************************************************************************
//display serials count
function displaySerialsCount(id)
{
	var serials = document.getElementById('delivery_serials_' + id).value;
	var t_serials = serials.split("\n");
	var nb = 0;
	var i;
	for(i=0;i<t_serials.length;i++)
	{
		if (t_serials[i] != '')
			nb++;
	}
	document.getElementById('serials_count_' + id).innerHTML = '  ' + nb + 'x';
	
}
