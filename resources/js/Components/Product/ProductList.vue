<template>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Product List</h5>
                        <Link class="btn btn-light btn-sm" href="/ProductSavePage?id=0">
                            <i class="fa fa-plus"></i> Add New Product
                        </Link>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 d-flex justify-content-end">
                            <input placeholder="Search product..." class="form-control w-50" type="text" v-model="searchValue">
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
                            <template #item-number="{ id, name }">
                                <div class="d-flex gap-2">
                                    <Link class="btn btn-outline-success btn-sm" :href="`/ProductSavePage?id=${id}`">
                                        <i class="fa fa-edit"></i> Edit
                                    </Link>
                                    <button class="btn btn-outline-danger btn-sm" @click="DeleteClick(id)">
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
import {ref} from "vue";
import {usePage,router,Link} from "@inertiajs/vue3";
let page=usePage()
import { createToaster } from "@meforma/vue-toaster";
const toaster = createToaster();

const Header = [
    { text: "ID", value: "id" },
    { text: "Category", value: "category.name" },
    { text: "Name", value: "name" },
    { text: "Price", value: "price" },
    { text: "Unit", value: "unit" },
    { text: "Action", value: "number" },
];

const Item = ref(page.props.list);
const searchField = "name";
const searchValue = ref("");

if (page.props.flash.status === true) {
    toaster.success(page.props.flash.message);
}
if (page.props.flash.status === false) {
    toaster.warning(page.props.flash.message);
}

const DeleteClick = (id) => {
    if (confirm("Do you want to delete this product?")) {
        router.get(`/delete-product/${id}`);
    }
};


</script>
