<template>
  <SideNavLayout>
    <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
          <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0">Create Sale (Invoice)</h5>
            </div>
            <div class="card-body">
              <SaleForm :customers="customers" :products="products" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </SideNavLayout>
</template>

<script setup>
import SideNavLayout from "../Layout/SideNavLayout.vue";
import SaleForm from "../Components/Sale/SaleForm.vue";
import { usePage } from "@inertiajs/vue3";
const page = usePage();
const customers = page.props.customers;
const products = page.props.products;
</script>