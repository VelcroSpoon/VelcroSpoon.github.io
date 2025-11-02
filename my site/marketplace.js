"use strict";


function Cart(taxRate) {
  this.itemGroups = [];
  this.taxRate = typeof taxRate === "number" ? taxRate : 0.15; 
}


Cart.prototype.addItemGroup = function (itemGroup) {
  if (!itemGroup) throw new Error("addItemGroup: missing itemGroup");
  if (typeof itemGroup.pricePerItem !== "number" || typeof itemGroup.quantity !== "number") {
    throw new Error("addItemGroup: pricePerItem and quantity must be numbers");
  }
  this.itemGroups.push(itemGroup);
};

Cart.prototype.getTotalAmount = function () {
  var amount = 0;
  for (var i = 0; i < this.itemGroups.length; i++) {
    var g = this.itemGroups[i];
    amount += g.pricePerItem * g.quantity;
  }
  return amount;
};


Cart.prototype.showTotalAmount = function () {
  if (this.itemGroups.length === 0) {
    document.write("<p>You have 0 item group, for a total amount of $0.00, in your cart!</p>");
    return;
  }

  var count = this.itemGroups.length;
  var subtotal = this.getTotalAmount();
  var totalWithTax = subtotal * (1 + this.taxRate);

  document.write(
    "<p>Cart has <strong>" + count + "</strong> item group" + (count !== 1 ? "s" : "") +
    ". Subtotal: <strong>$" + subtotal.toFixed(2) + "</strong>. " +
    "Total with taxes (" + (this.taxRate * 100).toFixed(1) + "%): " +
    "<strong>$" + totalWithTax.toFixed(2) + "</strong>.</p>"
  );
};

function ItemGroup(name, pricePerItem, quantity) {
  this.name = String(name || "");
  this.pricePerItem = Number(pricePerItem) || 0;
  this.quantity = Number(quantity) || 0;
}


document.write("<h2> 1) Creating the cart </h2>");
var my_cart = new Cart();
my_cart.showTotalAmount();

document.write("<h2> 2) Adding 15 pants at 10.05$ each to the cart! </h2>");
var pants = new ItemGroup("pants", 10.05, 15);
my_cart.addItemGroup(pants);
my_cart.showTotalAmount();

document.write("<h2> 3) Adding 1 coat at 99.99$ to the cart! </h2>");
var coat = new ItemGroup("coat", 99.99, 1);
my_cart.addItemGroup(coat);
my_cart.showTotalAmount();