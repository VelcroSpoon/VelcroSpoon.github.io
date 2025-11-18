"use strict";

// ----- data structures -----
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
Cart.prototype.renderInto = function (el) {
  if (!el) return;

  var count = this.itemGroups.length;
  var subtotal = this.getTotalAmount();
  var totalWithTax = subtotal * (1 + this.taxRate);

  var html = "";
  html += "<h2>1) Creating the cart</h2>";
  if (count === 0) {
    html += "<p>You have 0 item group, for a total amount of $0.00, in your cart!</p>";
  } else {
    html += "<p>Cart has <strong>" + count + "</strong> item group" + (count !== 1 ? "s" : "") +
            ". Subtotal: <strong>$" + subtotal.toFixed(2) + "</strong>. " +
            "Total with taxes (" + (this.taxRate * 100).toFixed(1) + "%): " +
            "<strong>$" + totalWithTax.toFixed(2) + "</strong>.</p>";
  }

  if (count > 0) {
    html += "<ul>";
    for (var i = 0; i < this.itemGroups.length; i++) {
      var it = this.itemGroups[i];
      html += "<li>" + it.name + " — $" + it.pricePerItem.toFixed(2) + " × " + it.quantity + "</li>";
    }
    html += "</ul>";
  }

  el.innerHTML = html;
};

function ItemGroup(name, pricePerItem, quantity) {
  this.name = String(name || "");
  this.pricePerItem = Number(pricePerItem) || 0;
  this.quantity = Number(quantity) || 0;
}

// ----- demo render on load -----
(function () {
  var out = document.getElementById("market-output");
  if (!out) return;

  var my_cart = new Cart();      // default 15% tax
  my_cart.renderInto(out);       // initial empty view

  // sample data (pants, coat)
  var pants = new ItemGroup("pants", 10.05, 15);
  my_cart.addItemGroup(pants);
  my_cart.renderInto(out);

  var coat = new ItemGroup("coat", 99.99, 1);
  my_cart.addItemGroup(coat);
  my_cart.renderInto(out);
})();
