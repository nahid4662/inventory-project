<script setup>
import { useForm, router, usePage } from "@inertiajs/vue3";
import { ref } from "vue";

const page = usePage();

let product = page.props.product;
let categories = page.props.categories;

const urlParams = new URLSearchParams(window.location.search);
let id = ref(parseInt(urlParams.get("id")));

const form = useForm({
    id: product?.id ?? "",
    name: product?.name ?? "",
    price: product?.price ?? "",
    unit: product?.unit ?? "",
    category_id: product?.category_id ?? "",
});

let URL = "/create-product";

if (id.value !== 0 && product !== null) {
    URL = "/update-product";
    // fill the form with existing
    form.name = product.name;
    form.id = product.id;
    form.price = product.price;
    form.unit = product.unit;
    form.category_id = product.category_id;
}

function submit() {
    form.post(URL, {
        onSuccess: () => {
            if (page.props.flash?.status === true) {
                router.get("/ProductPage");
            } else {
                alert(page.props.flash?.message ?? "Something went wrong");
            }
        },
    });
}
</script>

<template>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            {{
                                form.id === 0
                                    ? "Create Product"
                                    : "Edit Product"
                            }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="submit">
                            <input
                                id="id"
                                name="id"
                                v-model="form.id"
                                type="hidden"
                            />
                            <div class="mb-3">
                                <label for="name" class="form-label"
                                    >Product Name</label
                                >
                                <input
                                    id="name"
                                    name="name"
                                    v-model="form.name"
                                    placeholder="Enter product name"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.name }"
                                    type="text"
                                    autocomplete="off"
                                />
                                <div
                                    v-if="form.errors.name"
                                    class="invalid-feedback"
                                >
                                    {{ form.errors.name }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label"
                                    >Product Price</label
                                >
                                <input
                                    id="price"
                                    name="price"
                                    v-model="form.price"
                                    placeholder="Enter product price"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.price }"
                                    type="number"
                                    min="0"
                                    step="0.01"
                                />
                                <div
                                    v-if="form.errors.price"
                                    class="invalid-feedback"
                                >
                                    {{ form.errors.price }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="unit" class="form-label"
                                    >Product Unit</label
                                >
                                <input
                                    id="unit"
                                    name="unit"
                                    v-model="form.unit"
                                    placeholder="Enter product unit"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.unit }"
                                    type="text"
                                />
                                <div
                                    v-if="form.errors.unit"
                                    class="invalid-feedback"
                                >
                                    {{ form.errors.unit }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="category_id" class="form-label"
                                    >Category</label
                                >
                                <select
                                    id="category_id"
                                    name="category_id"
                                    v-model="form.category_id"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.category_id,
                                    }"
                                >
                                    <option value="">Select Category</option>
                                    <option
                                        v-for="category in categories"
                                        :key="category.id"
                                        :value="category.id"
                                    >
                                        {{ category.name }}
                                    </option>
                                </select>
                                <div
                                    v-if="form.errors.category_id"
                                    class="invalid-feedback"
                                >
                                    {{ form.errors.category_id }}
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success w-100">
                                {{
                                    form.id === 0 ? "Create" : "Update"
                                }}
                                Product
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
