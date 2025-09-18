<template>
  <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="col-md-6">
      <div class="card shadow-lg border-0 rounded-3">
        
        <!-- Card Header -->
        <div class="card-header text-center text-white fw-bold fs-5"
             style="background: linear-gradient(90deg, #198754, #20c997);">
          <span v-if="id == 0">➕ Add Category</span>
          <span v-else-if="id > 0">✏️ Update Category</span>
        </div>

        <!-- Card Body -->
        <div class="card-body p-4">
          <form @submit.prevent="submit" class="needs-validation">
            
            <!-- Category Name -->
            <div class="form-floating mb-3">
              <input
                id="name"
                name="name"
                v-model="form.name"
                placeholder="Category Name"
                class="form-control"
                :class="{
                  'is-invalid': form.errors.name || nameError,
                }"
              />
              <label for="name">Category Name</label>

              <!-- Error messages -->
              <div v-if="nameError" class="invalid-feedback">
                {{ nameError }}
              </div>
              <div v-else-if="form.errors.name" class="invalid-feedback">
                {{ form.errors.name }}
              </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-success w-100 py-2 fw-semibold shadow-sm">
              💾 Save Change
            </button>
          </form>
        </div>

      </div>
    </div>
  </div>
</template>


<script setup>
import { useForm, router, Link } from "@inertiajs/vue3";
import { usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const urlParams = new URLSearchParams(window.location.search);
let id = ref(parseInt(urlParams.get("id")));

const form = useForm({ name: "", id: id.value });
const page = usePage();

let URL = "/create-category";
let list = page.props.list;
if (id.value !== 0 && list !== null) {
    URL = "/update-category";
    form.name = list["name"];
    form.id = list["id"];
}

const nameError = computed(() => {
    if (form.name.length === 0) {
        return "Category name is required";
    }
    if (form.name.length > 255) {
        return "Category name must be less than 255 characters";
    }
    return "";
});

function submit() {
    if (nameError.value) {
        return;
    }
    form.post(URL, {
        onSuccess: () => {
            if (page.props.flash.status === true) {
                router.get("/CategoryPage");
            } else {
                alert(page.props.flash.message);
            }
        },
    });
}
</script>
