
<template>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Category List</h5>
                        <Link class="btn btn-light btn-sm" href="/CategorySavePage?id=0">
                            <i class="fa fa-plus"></i> Create New
                        </Link>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 d-flex justify-content-end">
                            <input placeholder="Search category..." class="form-control w-50" type="text" v-model="searchValue">
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
                                    <Link class="btn btn-outline-success btn-sm" :href="`/CategorySavePage?id=${id}`">
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
import {useForm,router,Link} from '@inertiajs/vue3'
import {ref} from "vue";
import {usePage} from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
const toaster = createToaster();

let page=usePage()


const Header = [
    { text: "ID", value: "id" },
    { text: "Name", value: "name" },
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
    if (confirm("Do you want to delete this category?")) {
        router.get(`/delete-category/${id}`);
    }
};



</script>
