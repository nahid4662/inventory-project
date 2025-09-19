<template>
  <form @submit.prevent="submit">
    <div class="mb-3">
      <label for="customer_id" class="form-label">Customer</label>
      <select v-model="form.customer_id" class="form-control" :class="{'is-invalid': form.errors.customer_id}">
        <option value="">Select Customer</option>
        <option v-for="customer in customers" :key="customer.id" :value="customer.id">
          {{ customer.name }} ({{ customer.mobile }})
        </option>
      </select>
      <div v-if="form.errors.customer_id" class="invalid-feedback">{{ form.errors.customer_id }}</div>
    </div>

    <div class="mb-3">
      <label class="form-label">Products</label>
      <div v-for="(item, idx) in form.products" :key="idx" class="row mb-2">
        <div class="col-md-4">
          <select v-model="item.product_id" class="form-control" :class="{'is-invalid': form.errors[`products.${idx}.product_id`]}">
            <option value="">Select Product</option>
            <option v-for="product in products" :key="product.id" :value="product.id">
              {{ product.name }}
            </option>
          </select>
        </div>
        <div class="col-md-3">
          <input v-model="item.unit" type="number" min="1" class="form-control" placeholder="Unit" :class="{'is-invalid': form.errors[`products.${idx}.unit`]}"/>
        </div>
        <div class="col-md-3">
          <input v-model="item.price" type="number" min="0" step="0.01" class="form-control" placeholder="Sale Price" :class="{'is-invalid': form.errors[`products.${idx}.price`]}"/>
        </div>
        <div class="col-md-2">
          <button type="button" class="btn btn-danger" @click="removeProduct(idx)">Remove</button>
        </div>
      </div>
      <button type="button" class="btn btn-outline-primary" @click="addProduct">Add Product</button>
    </div>

    <div class="mb-3">
      <label for="total" class="form-label">Total</label>
      <input v-model="form.total" type="number" class="form-control" readonly />
    </div>
    <div class="mb-3">
      <label for="discount" class="form-label">Discount</label>
      <input v-model="form.discount" type="number" class="form-control" />
    </div>
    <div class="mb-3">
      <label for="vat" class="form-label">VAT</label>
      <input v-model="form.vat" type="number" class="form-control" />
    </div>
    <div class="mb-3">
      <label for="payable" class="form-label">Payable</label>
      <input v-model="form.payable" type="number" class="form-control" readonly />
    </div>
    <button type="submit" class="btn btn-success w-100">Create Sale</button>
  </form>
</template>

<script setup>
import { useForm, router, usePage } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const page = usePage();
console.log(page.props);
const customers = page.props.customers;
const products = page.props.products;


const form = useForm({
  customer_id: "",
  products: [{ product_id: "", unit: 1, price: 0 }],
  total: 0,
  discount: 0,
  vat: 0,
  payable: 0,
});

function addProduct() {
  form.products.push({ product_id: "", unit: 1, price: 0 });
}
function removeProduct(idx) {
  form.products.splice(idx, 1);
}

// Auto-fill price and unit when product is selected
watch(
  () => form.products.map(p => p.product_id),
  (ids, oldIds) => {
    ids.forEach((id, idx) => {
      if (id) {
        const selected = products.find(p => p.id == id);
        if (selected) {
          form.products[idx].price = selected.price;
          form.products[idx].unit = 1; // default unit to 1
        }
      } else {
        form.products[idx].price = 0;
        form.products[idx].unit = 1;
      }
    });
  },
  { deep: true }
);

// Calculate total and payable
watch(
  () => form.products,
  (products) => {
    let total = products.reduce((sum, item) => sum + (parseFloat(item.unit) * parseFloat(item.price) || 0), 0);
    form.total = total;
    form.payable = total - (parseFloat(form.discount) || 0) + (parseFloat(form.vat) || 0);
  },
  { deep: true }
);

watch([() => form.discount, () => form.vat], () => {
  form.payable = form.total - (parseFloat(form.discount) || 0) + (parseFloat(form.vat) || 0);
});

function submit() {
  form.post("/invoice-create", {
    onSuccess: () => {
      if (page.props.flash.status === true) {
        router.get("/InvoiceListPage");
      } else {
        alert(page.props.flash.message ?? "Something went wrong");
      }
    }
  });
}
</script>