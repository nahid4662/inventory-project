<template>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Invoice List</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 d-flex justify-content-end">
                            <input placeholder="Search invoice..." class="form-control w-50" type="text" v-model="searchValue">
                        </div>
                        <EasyDataTable
                            buttons-pagination
                            alternating
                            :headers="Header"
                            :items="Item"
                            :rows-per-page="10"
                            :search-field="searchField"
                            :search-value="searchValue"
                            class="table table-hover table-bordered rounded"
                        >
                            <template #item-number="{ id, total }">
                                <div class="d-flex gap-2">
                                    <Link class="btn btn-outline-info btn-sm" :href="`/InvoiceDetailsPage?id=${id}`">
                                        <i class="fa fa-eye"></i> Details
                                    </Link>
                                    <button class="btn btn-outline-danger btn-sm" @click="InvoiceDelete(id)">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </div>
                            </template>
                        </EasyDataTable>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script setup>

import { ref } from "vue";
import { usePage, router, Link } from "@inertiajs/vue3";
let page = usePage();
import { createToaster } from "@meforma/vue-toaster";
const toaster = createToaster();

const Header = [
    { text: "ID", value: "id" },
    { text: "Customer", value: "customer.name" },
    { text: "Phone", value: "customer.mobile" },
    { text: "Total", value: "total" },
    { text: "Discount", value: "discount" },
    { text: "Vat", value: "vat" },
    { text: "Payable", value: "payable" },
    { text: "Action", value: "number" },
];

const Item = ref(page.props.list);
const searchField = "customer.name";
const searchValue = ref("");

if (page.props.flash.status === true) {
    toaster.success(page.props.flash.message);
}
if (page.props.flash.status === false) {
    toaster.warning(page.props.flash.message);
}

const InvoiceDelete = (id) => {
    if (confirm("Do you want to delete this invoice?")) {
        router.get(`/invoice-delete/${id}`);
    }
};


</script>
